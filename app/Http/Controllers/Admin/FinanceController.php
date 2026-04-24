<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentFinance;
use App\Models\StudentPayment;
use App\Models\StudentPaymentMonth;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Mail\FinanceReminderMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FinanceController extends Controller
{
    // ── Get finance record for an application (pre-enrollment) ──────
    public function getForApplication(Request $request)
    {
        $ref = $request->query('reference_number');
        if (!$ref) return response()->json(['finance' => null]);

        $finance = StudentFinance::where('reference_number', $ref)
            ->with(['paymentMonths', 'payments'])
            ->orderByDesc('created_at')->first();

        return response()->json(['finance' => $finance]);
    }

    // ── Get finance record for an enrolled student ───────────────────
    public function getForStudent(Request $request)
    {
        $studentId = $request->query('student_id');
        if (!$studentId) return response()->json(['finance' => null]);

        $student = Student::find($studentId);
        if (!$student) return response()->json(['finance' => null, 'error' => 'Student not found']);

        $finance = StudentFinance::where('student_id', $studentId)
            ->with(['paymentMonths', 'payments'])
            ->orderByDesc('created_at')->first();

        if ($finance) {
            $finance->syncOverdueStatuses();
            $finance->refresh()->load(['paymentMonths', 'payments']);
        }

        return response()->json(['finance' => $finance, 'student' => $student]);
    }

    // ── Store / update finance configuration ────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'payment_plan'    => 'required|string|max:5',
            'enrollment_fee'  => 'required|numeric|min:0',
            'monthly_amount'  => 'required|numeric|min:0',
            'monthly_months'  => 'required|integer|min:0|max:12',
            'misc_fee'        => 'required|numeric|min:0',
            'referral_count'  => 'required|integer|min:0|max:5',
            'total_fee'       => 'required|numeric|min:0',
            'grade_level'     => 'required|string',
            'student_category'=> 'required|string',
            'school_year'     => 'required|string',
        ]);

        $noReferral       = (bool) $request->input('no_referral', false);
        $referralCount    = $noReferral ? 0 : (int) $request->referral_count;
        $referralDiscount = $referralCount * StudentFinance::REFERRAL_PER_HEAD;
        $isFullPayment    = $request->payment_plan === 'A' && (int) $request->monthly_months === 0;

        // Recompute total server-side for safety
        $enrollmentFee = (float) $request->enrollment_fee;
        $miscFee       = (float) $request->misc_fee;
        $tuition       = $enrollmentFee + ((float) $request->monthly_amount * (int) $request->monthly_months);
        $total         = max(0, $tuition + $miscFee - $referralDiscount);

        // Plan A (full cash): tuition paid at enrollment, misc fee remains as balance.
        // Other plans: down payment paid at enrollment, monthly fees + misc remain as balance.
        $amountPaidInitial = $enrollmentFee;                      // tuition / down payment
        $balanceInitial    = max(0, $total - $enrollmentFee);     // misc + monthly remainder

        $refNumber = $request->reference_number ?: null;
        $studentId = $request->student_id       ?: null;

        // Locate existing record
        $finance = null;
        if ($studentId) {
            $finance = StudentFinance::where('student_id', $studentId)
                ->where('school_year', $request->school_year)->first();
        } elseif ($refNumber) {
            $finance = StudentFinance::where('reference_number', $refNumber)->first();
        }

        $planChanged = $finance && $finance->payment_plan !== $request->payment_plan;

        $data = [
            'school_year'       => $request->school_year,
            'grade_level'       => $request->grade_level,
            'student_category'  => $request->student_category,
            'payment_plan'      => $request->payment_plan,
            'enrollment_fee'    => $request->enrollment_fee,
            'monthly_amount'    => $request->monthly_amount,
            'monthly_months'    => $request->monthly_months,
            'misc_fee'          => $miscFee,
            'referral_count'    => $referralCount,
            'referral_discount' => $referralDiscount,
            'no_referral'       => $noReferral,
            'is_full_payment'   => $isFullPayment,
            'total_fee'         => $total,
            'amount_paid'       => $amountPaidInitial,
            'balance'           => $balanceInitial,
            'finance_clearance' => 'pending',
        ];
        if ($studentId) $data['student_id']        = $studentId;
        if ($refNumber) $data['reference_number']  = $refNumber;

        if ($finance) {
            if ($planChanged) {
                $finance->paymentMonths()->delete();
                $finance->payments()->delete(); // reset payments if plan changes
            }
            $finance->update($data);
            $finance->refresh();
            if ($planChanged) $finance->generateMonthlySchedule();
            // For Plan A: create one lump-sum payment record if it doesn't exist
            if ($isFullPayment && $finance->payments()->count() === 0) {
                $this->createFullPaymentRecord($finance);
            }
        } else {
            $finance = StudentFinance::create($data);
            $finance->generateMonthlySchedule();
            if ($isFullPayment) {
                $this->createFullPaymentRecord($finance);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Finance configuration saved.',
            'finance' => $finance->fresh()->load(['paymentMonths', 'payments']),
        ]);
    }

    /**
     * Record the tuition payment for Plan A (full cash) configurations.
     * Only the enrollment_fee (tuition) is recorded as paid; misc fee remains as balance.
     */
    private function createFullPaymentRecord(StudentFinance $finance): void
    {
        StudentPayment::create([
            'student_finance_id' => $finance->id,
            'receipt_number'     => StudentPayment::generateReceiptNumber(),
            'amount'             => $finance->enrollment_fee,
            'payment_date'       => now()->toDateString(),
            'payment_method'     => 'cash',
            'notes'              => 'Full cash tuition payment (Plan A) — recorded at enrollment',
            'recorded_by'        => Auth::id(),
        ]);
        // Tuition paid; misc fee (total - enrollment_fee) remains as balance
        $finance->update([
            'amount_paid' => $finance->enrollment_fee,
            'balance'     => max(0, $finance->total_fee - $finance->enrollment_fee),
        ]);
    }

    // ── Record a new payment against a student's balance ────────────
    public function recordPayment(Request $request)
    {
        $request->validate([
            'student_finance_id' => 'required|integer|exists:student_finance,id',
            'amount'             => 'required|numeric|min:0.01',
            'payment_date'       => 'required|date',
            'payment_method'     => 'required|in:cash,gcash,paymaya,paypal',
            'online_reference'   => 'nullable|string|max:100',
            'month_ids'          => 'nullable|array',
            'month_ids.*'        => 'integer|exists:student_payment_months,id',
            'notes'              => 'nullable|string|max:500',
        ]);

        $finance = StudentFinance::with('paymentMonths')->findOrFail($request->student_finance_id);

        // Clamp amount to remaining balance
        $amount = min((float) $request->amount, (float) $finance->balance);
        if ($amount <= 0) {
            return response()->json(['success' => false, 'message' => 'Balance is already fully paid.'], 422);
        }

        // Create payment record
        $payment = StudentPayment::create([
            'student_finance_id' => $finance->id,
            'receipt_number'     => StudentPayment::generateReceiptNumber(),
            'amount'             => $amount,
            'payment_date'       => $request->payment_date,
            'payment_method'     => $request->payment_method,
            'online_reference'   => $request->payment_method !== 'cash' ? $request->online_reference : null,
            'month_ids'          => $request->month_ids,
            'notes'              => $request->notes,
            'recorded_by'        => Auth::id(),
        ]);

        // Mark specified months as paid / partial
        if (!empty($request->month_ids)) {
            $remaining = $amount;
            $months = $finance->paymentMonths()->whereIn('id', $request->month_ids)->orderBy('due_date')->get();
            foreach ($months as $month) {
                $owed = $month->amount_due - $month->amount_paid;
                if ($owed <= 0) continue;
                if ($remaining >= $owed) {
                    $month->update(['amount_paid' => $month->amount_due, 'status' => 'paid', 'paid_date' => $request->payment_date]);
                    $remaining -= $owed;
                } else {
                    $month->update(['amount_paid' => $month->amount_paid + $remaining, 'status' => 'partial', 'paid_date' => $request->payment_date]);
                    $remaining = 0;
                }
                if ($remaining <= 0) break;
            }
        }

        // Recalculate balance
        $finance->recalcBalance();
        $finance->refresh();

        // Auto-clear if fully paid
        if ($finance->balance <= 0) {
            $finance->update(['finance_clearance' => 'cleared']);
        }

        return response()->json([
            'success'  => true,
            'message'  => 'Payment recorded.',
            'receipt'  => $payment->receipt_number,
            'payment'  => $payment,
            'finance'  => $finance->fresh()->load(['paymentMonths', 'payments']),
        ]);
    }

    // ── Get finance details (for modal/details view) ─────────────────
    public function getDetails(Request $request)
    {
        $financeId = $request->query('finance_id');
        $studentId = $request->query('student_id');

        $finance = $financeId
            ? StudentFinance::with(['paymentMonths', 'payments.recorderUser'])->findOrFail($financeId)
            : StudentFinance::with(['paymentMonths', 'payments'])->where('student_id', $studentId)->orderByDesc('created_at')->firstOrFail();

        $finance->syncOverdueStatuses();
        $student = $finance->student;

        return response()->json([
            'finance' => $finance->fresh()->load(['paymentMonths', 'payments']),
            'student' => $student,
        ]);
    }

    // ── Generate printable receipt data ─────────────────────────────
    public function getReceipt(Request $request)
    {
        $paymentId = $request->query('payment_id');
        $financeId = $request->query('finance_id'); // latest payment for finance

        if ($paymentId) {
            $payment = StudentPayment::with('finance.student')->findOrFail($paymentId);
        } else {
            $finance = StudentFinance::with(['student', 'payments'])->findOrFail($financeId);
            $payment = $finance->payments()->latest()->first();
            if (!$payment) return response()->json(['error' => 'No payments found.'], 404);
            $payment->load('finance.student');
        }

        return response()->json(['payment' => $payment]);
    }

    // ── Send payment reminder email ──────────────────────────────────
    public function sendReminder(Request $request)
    {
        $request->validate([
            'student_id'    => 'required|integer|exists:students,id',
            'reminder_type' => 'nullable|in:overdue,upcoming,general',
            'note'          => 'nullable|string|max:500',
        ]);

        $student = Student::findOrFail($request->student_id);
        $finance = StudentFinance::where('student_id', $student->id)
            ->orderByDesc('created_at')->first();

        $reminderType = $request->input('reminder_type', 'general');
        $name         = trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''));
        $balance      = $finance ? number_format((float) $finance->balance, 2) : '0.00';
        $schoolYear   = $finance?->school_year ?? '—';
        $note         = $request->input('note');

        // Always send to personal email and guardian email
        $recipients = array_filter([
            $student->personal_email ?: null,
            $student->guardian_email  ?: null,
        ]);

        if (empty($recipients)) {
            return response()->json([
                'success' => false,
                'message' => 'No email address on record for this student or guardian.',
            ], 422);
        }

        $mailable = new FinanceReminderMail($name, $reminderType, $balance, $schoolYear, $note);
        $sent = 0;
        $errors = [];

        foreach ($recipients as $email) {
            try {
                Mail::to($email)->send($mailable);

                DB::table('finance_reminder_notifications')->insert([
                    'student_id'         => $student->id,
                    'student_finance_id' => $finance?->id,
                    'reminder_type'      => $reminderType,
                    'recipient_email'    => $email,
                    'recipient_type'     => $email === $student->personal_email ? 'student' : 'guardian',
                    'email_subject'      => $mailable->envelope()->subject,
                    'status'             => 'sent',
                    'queued_by'          => Auth::id(),
                    'queued_at'          => now(),
                    'sent_at'            => now(),
                ]);
                $sent++;
            } catch (\Exception $e) {
                DB::table('finance_reminder_notifications')->insert([
                    'student_id'         => $student->id,
                    'student_finance_id' => $finance?->id,
                    'reminder_type'      => $reminderType,
                    'recipient_email'    => $email,
                    'recipient_type'     => $email === $student->personal_email ? 'student' : 'guardian',
                    'email_subject'      => $mailable->envelope()->subject,
                    'status'             => 'failed',
                    'error_message'      => $e->getMessage(),
                    'queued_by'          => Auth::id(),
                    'queued_at'          => now(),
                ]);
                $errors[] = $email;
                Log::error('Finance reminder failed', ['email' => $email, 'error' => $e->getMessage()]);
            }
        }

        if ($sent === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send reminder. Please check mail configuration.',
            ], 500);
        }

        $emailList = implode(', ', $recipients);
        $msg = "Reminder sent to {$name} → {$emailList}.";
        if (!empty($errors)) {
            $msg .= ' (Some addresses failed: ' . implode(', ', $errors) . ')';
        }

        return response()->json(['success' => true, 'message' => $msg]);
    }

    // ── Link pre-enrollment finance to newly created student ─────────
    public static function linkToStudent(string $referenceNumber, int $studentId): void
    {
        StudentFinance::where('reference_number', $referenceNumber)
            ->whereNull('student_id')
            ->update(['student_id' => $studentId]);
    }
}
