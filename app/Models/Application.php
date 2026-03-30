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
        'student_category', 'is_transferee', 'previous_school', 'previous_school_address',
        // Step 2
        'first_name', 'middle_name', 'last_name', 'suffix',
        'gender', 'date_of_birth', 'lrn', 'nationality', 'mother_tongue', 'religion',
        'personal_email', 'mobile_number', 'home_address', 'city', 'zip_code',
        // Step 3
        'father_name', 'father_contact',
        'mother_name', 'mother_maiden_name', 'mother_contact',
        'guardian_name', 'guardian_relationship', 'guardian_contact',
        'guardian_address', 'guardian_occupation', 'guardian_email',
        'emergency_contact_number',
        // Step 4
        'psa_uploaded', 'psa_filename', 'psa_path',
        'report_card_uploaded', 'report_card_filename', 'report_card_path',
        'good_moral_uploaded', 'good_moral_filename', 'good_moral_path',
        // Academic
        'track', 'strand', 'pathway',
        // Meta
        'school_year', 'application_status',
        'consent_given', 'consent_date', 'parent_name_consent',
        'submitted_at',
    ];

    protected $casts = [
        'is_transferee'         => 'boolean',
        'psa_uploaded'          => 'boolean',
        'report_card_uploaded'  => 'boolean',
        'good_moral_uploaded'   => 'boolean',
        'consent_given'         => 'boolean',
        'date_of_birth'         => 'date',
        'submitted_at'          => 'datetime',
        'consent_date'          => 'datetime',
    ];

    // ── Generate unique reference number ──
    public static function generateReferenceNumber(): string
    {
        $year = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;
        return 'APP-' . $year . '-' . str_pad($count, 6, '0', STR_PAD_LEFT);
    }

    // ── Full name accessor ──
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . ($this->middle_name ? $this->middle_name . ' ' : '') . $this->last_name . ($this->suffix ? ' ' . $this->suffix : ''));
    }

    // ── Status label ──
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

    // ── Status color ──
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
}