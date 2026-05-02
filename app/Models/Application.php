<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    use HasFactory;

    protected $table = 'applications';

    protected $fillable = [
        'reference_number',
        // Step 1
        'applied_level', 'incoming_grade_level', 'student_status',
        'student_category', 'subsidy_prev_school_type', 'subsidy_certificate_no',
        'is_transferee', 'previous_school', 'previous_school_address',
        // SHS
        'track', 'strand', 'pathway', 'shs_student_type',
        // Step 2
        'first_name', 'middle_name', 'last_name', 'suffix',
        'gender', 'date_of_birth', 'lrn', 'nationality', 'mother_tongue', 'religion',
        'personal_email', 'mobile_number', 'home_address', 'city', 'zip_code',
        // Step 3
        'father_name', 'father_contact',
        'mother_name', 'mother_maiden_name', 'mother_contact',
        'guardian_name', 'guardian_relationship', 'guardian_contact',
        'guardian_address', 'guardian_occupation', 'guardian_email',
        // Step 4
        'psa_uploaded', 'psa_filename', 'psa_path', 'psa_status', 'psa_submitted',
        'report_card_uploaded', 'report_card_filename', 'report_card_path', 'report_card_status', 'report_card_submitted',
        'good_moral_uploaded', 'good_moral_filename', 'good_moral_path', 'good_moral_status', 'good_moral_submitted',
        'medical_uploaded', 'medical_filename', 'medical_path', 'medical_status', 'medical_submitted',
        // Meta
        'school_year', 'application_status',
        'consent_given', 'consent_date', 'parent_name_consent',
        'submitted_at',
        // Finance clearance gate
        'finance_clearance', 'finance_clearance_notes', 'finance_clearance_updated_at',
        'finance_total_assessment', 'finance_amount_paid', 'finance_next_due_date', 'finance_cleared_by',
    ];

    protected $casts = [
        'is_transferee'        => 'boolean',
        'psa_uploaded'         => 'boolean',
        'report_card_uploaded' => 'boolean',
        'good_moral_uploaded'  => 'boolean',
        'consent_given'        => 'boolean',
        'date_of_birth'                  => 'date',
        'submitted_at'                   => 'datetime',
        'consent_date'                   => 'datetime',
        'finance_clearance_updated_at'   => 'datetime',
        'finance_next_due_date'          => 'date',
        'finance_total_assessment'       => 'decimal:2',
        'finance_amount_paid'            => 'decimal:2',
    ];

    protected static $nameFields = [
        'first_name', 'middle_name', 'last_name', 'suffix',
        'father_name', 'mother_name', 'mother_maiden_name', 'guardian_name',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::saving(function (self $model) {
            foreach (self::$nameFields as $field) {
                if (!empty($model->$field)) {
                    $model->$field = strtoupper($model->$field);
                }
            }
        });
    }

    // ── Generate unique reference number ──
    public static function generateReferenceNumber(): string
    {
        $year   = date('Y');
        $prefix = 'APP-' . $year . '-';

        // Use the highest existing sequence for this year, not COUNT (COUNT breaks on gaps/deletions).
        $last = self::where('reference_number', 'like', $prefix . '%')
            ->orderByRaw('CAST(SUBSTRING_INDEX(reference_number, \'-\', -1) AS UNSIGNED) DESC')
            ->value('reference_number');

        $seq = $last ? ((int) substr($last, strlen($prefix)) + 1) : 1;

        return $prefix . str_pad($seq, 6, '0', STR_PAD_LEFT);
    }

    // ── Accessors ──
    public function getFullNameAttribute(): string
    {
        return trim(
            $this->first_name . ' ' .
            ($this->middle_name ? $this->middle_name . ' ' : '') .
            $this->last_name .
            ($this->suffix ? ' ' . $this->suffix : '')
        );
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->application_status) {
            'pending'      => 'Pending',
            'pre_approved' => 'Pre-Approved',
            'approved'     => 'Approved',
            'rejected'     => 'Rejected',
            'incomplete'   => 'Incomplete',
            default        => 'Unknown',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->application_status) {
            'pending'      => 'bg-amber-100 text-amber-700',
            'pre_approved' => 'bg-blue-100 text-blue-700',
            'approved'     => 'bg-green-100 text-green-700',
            'rejected'     => 'bg-red-100 text-red-700',
            'incomplete'   => 'bg-orange-100 text-orange-700',
            default        => 'bg-slate-100 text-slate-600',
        };
    }

    // ── Application type label ──
    public function getApplicationTypeAttribute(): string
    {
        if ($this->is_transferee) return 'Transfer';
        return $this->student_status === 'Old' ? 'Return' : 'New';
    }
}