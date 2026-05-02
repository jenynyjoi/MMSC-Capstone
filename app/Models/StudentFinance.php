<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentFinance extends Model
{
    protected $table = 'student_finance';

    protected $fillable = [
        'student_id', 'reference_number', 'school_year',
        'grade_level', 'student_category', 'payment_plan',
        'enrollment_fee', 'monthly_amount', 'monthly_months',
        'misc_fee', 'referral_count', 'referral_discount',
        'no_referral', 'is_full_payment',
        'total_fee', 'amount_paid', 'balance',
        'finance_clearance', 'notes',
    ];

    protected $casts = [
        'enrollment_fee'    => 'decimal:2',
        'monthly_amount'    => 'decimal:2',
        'misc_fee'          => 'decimal:2',
        'referral_discount' => 'decimal:2',
        'total_fee'         => 'decimal:2',
        'amount_paid'       => 'decimal:2',
        'balance'           => 'decimal:2',
        'no_referral'       => 'boolean',
        'is_full_payment'   => 'boolean',
        'referral_count'    => 'integer',
    ];

    // ── Payment plan rates ───────────────────────────────────────────
    // Format: [enrollment_fee, monthly_amount, monthly_months]
    public static array $PLANS = [
        // Elementary Grade 1–3
        'elem_1_3' => [
            'A' => [14600, 0,    0],  // full cash
            'B' => [5600,  1000, 9],
            'C' => [4700,  1100, 9],
            'D' => [3800,  1200, 9],
        ],
        // Elementary Grade 4–6
        'elem_4_6' => [
            'A' => [15500, 0,    0],
            'B' => [6500,  1000, 9],
            'C' => [5600,  1100, 9],
            'D' => [4700,  1200, 9],
        ],
        // JHS Regular payee
        'jhs_regular' => [
            'A' => [16000, 0,    0],
            'B' => [7500,  1000, 9],
            'C' => [6600,  1100, 9],
            'D' => [5700,  1200, 9],
        ],
        // JHS ESC grantee
        'jhs_esc' => [
            'A' => [7500, 0,   0],
            'B' => [3500, 500, 9],
        ],
        // SHS — 3 options (modes of payment, not installment plans)
        // A = No voucher (regular payee)
        // B = With SHS voucher from ESC subsidiary (80% discount — student pays ₱1,400)
        // C = With SHS voucher from public school completer (100% covered — student pays ₱0 tuition, only misc)
        'shs' => [
            'A' => [17500, 0, 0],
            'B' => [1400,  0, 0],
            'C' => [0,     0, 0], // tuition free; only misc recorded; no referral
        ],
    ];

    // Referral: ₱500 per referral, max 5 referrals = max ₱2,500
    public const REFERRAL_PER_HEAD  = 500;
    public const REFERRAL_MAX_COUNT = 5;

    // Monthly schedule
    public static array $MONTH_SCHEDULE = [
        ['July',      7,  0],
        ['August',    8,  0],
        ['September', 9,  0],
        ['October',   10, 0],
        ['November',  11, 0],
        ['December',  12, 0],
        ['January',   1,  1],
        ['February',  2,  1],
        ['March',     3,  1],
    ];

    /**
     * Resolve plan group key from grade_level + student_category
     */
    public static function resolvePlanGroup(string $gradeLevel, string $category): string
    {
        $level = strtolower($gradeLevel);
        if (preg_match('/grade\s*[123]\b|kinder/i', $level)) return 'elem_1_3';
        if (preg_match('/grade\s*[456]\b/i', $level))         return 'elem_4_6';
        if (preg_match('/grade\s*(7|8|9|10)\b|junior/i', $level)) {
            return str_contains(strtolower($category), 'esc') ? 'jhs_esc' : 'jhs_regular';
        }
        return 'shs'; // Grade 11, 12 or anything else
    }

    /**
     * Compute total fee.
     * Plan A (full cash) = enrollment_fee + misc_fee - referral_discount
     * Others             = enrollment_fee + (monthly_amount × months) + misc_fee - referral_discount
     * SHS C              = misc_fee only (no referral)
     */
    public static function computeTotal(
        float $enrollmentFee,
        float $monthlyAmount,
        int   $monthlyMonths,
        float $miscFee,
        int   $referralCount,
        bool  $noReferral = false
    ): array {
        $tuition  = $enrollmentFee + ($monthlyAmount * $monthlyMonths);
        $referral = $noReferral ? 0 : min($referralCount, self::REFERRAL_MAX_COUNT) * self::REFERRAL_PER_HEAD;
        $total    = max(0, $tuition + $miscFee - $referral);
        return [
            'referral_discount' => $referral,
            'total_fee'         => $total,
        ];
    }

    /**
     * Generate monthly payment schedule records.
     */
    public function generateMonthlySchedule(): void
    {
        if ($this->monthly_months <= 0) return;

        $startYear = (int) explode('-', $this->school_year ?? (date('Y') . '-' . (date('Y') + 1)))[0];
        $months    = array_slice(self::$MONTH_SCHEDULE, 0, $this->monthly_months);

        foreach ($months as [$name, $monthNum, $yearOffset]) {
            $year = $startYear + $yearOffset;
            $this->paymentMonths()->create([
                'month_name'   => $name,
                'month_number' => $monthNum,
                'month_year'   => $year,
                'due_date'     => sprintf('%04d-%02d-10', $year, $monthNum),
                'amount_due'   => $this->monthly_amount,
                'amount_paid'  => 0,
                'status'       => 'pending',
            ]);
        }
    }

    /**
     * Sync overdue statuses: any pending month past its due date is now overdue.
     */
    public function syncOverdueStatuses(): void
    {
        $today = now()->toDateString();
        $this->paymentMonths()
            ->where('status', 'pending')
            ->where('due_date', '<', $today)
            ->update(['status' => 'overdue']);
    }

    /**
     * Recalculate amount_paid and balance from all payment records.
     */
    public function recalcBalance(): void
    {
        $totalPaid = $this->payments()->sum('amount');
        $this->update([
            'amount_paid' => $totalPaid,
            'balance'     => max(0, $this->total_fee - $totalPaid),
        ]);
    }

    // ── Relationships ─────────────────────────────────────────────────
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function paymentMonths()
    {
        return $this->hasMany(StudentPaymentMonth::class, 'student_finance_id')->orderBy('due_date');
    }

    public function payments()
    {
        return $this->hasMany(StudentPayment::class, 'student_finance_id')->orderByDesc('payment_date');
    }
}
