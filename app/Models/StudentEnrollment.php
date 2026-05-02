<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentEnrollment extends Model
{
    use HasFactory;

    protected $table = 'student_enrollment';

    protected $fillable = [
        'student_id', 'application_id', 'school_year',
        'grade_level', 'grade_level_applied', 'program_level',
        'track', 'strand',
        'student_type', 'enrollment_type', 'gender',
        'section_id', 'section_name',
        'enrollment_date', 'enrollment_status', 'assignment_status',
        'assigned_at', 'assigned_by',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'assigned_at'     => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    // ── Type helpers ───────────────────────────────────────
    public function isRegular(): bool
    {
        return $this->student_type === 'regular';
    }

    public function isIrregular(): bool
    {
        return $this->student_type === 'irregular_shs';
    }

    public function isPending(): bool
    {
        return $this->assignment_status === 'pending';
    }

    public function isAssigned(): bool
    {
        return $this->assignment_status === 'assigned';
    }

    // ══════════════════════════════════════════════════════
    // Create enrollment record from approved application
    //
    // IRREGULAR LOGIC:
    //   A student is irregular_shs ONLY when ALL of these are true:
    //     1. applied_level === 'Senior High School'
    //     2. AND the shs_student_type field is explicitly 'Irregular'
    //        (the admin/applicant toggled the Irregular radio button)
    //
    //   Everyone else (Elementary, JHS, SHS Regular) → 'regular'
    //
    //   This means:
    //   - Elementary students   → regular
    //   - JHS students          → regular
    //   - SHS + Regular type    → regular
    //   - SHS + Irregular type  → irregular_shs  ← goes to Irregular tab
    // ══════════════════════════════════════════════════════
    public static function createFromApplication(Application $app, Student $student): self
    {
        $isShs      = $app->applied_level === 'Senior High School';
        $isIrregular = $isShs && $app->shs_student_type === 'Irregular';

        $studentType = $isIrregular ? 'irregular_shs' : 'regular';

        $enrollmentType = match(true) {
            $app->is_transferee           => 'transferee',
            $app->student_status === 'Old'=> 'return',
            default                       => 'new',
        };

        return self::firstOrCreate(
            [
                'student_id'  => $student->id,
                'school_year' => $app->school_year,
            ],
            [
                'application_id'      => $app->id,
                'school_year'         => $app->school_year,
                'grade_level'         => $app->incoming_grade_level,
                'grade_level_applied' => $app->incoming_grade_level,
                'program_level'       => $app->applied_level,
                'track'               => $app->track,
                'strand'              => $app->strand,
                'student_type'        => $studentType,
                'enrollment_type'     => $enrollmentType,
                'gender'              => $student->gender ?? $app->gender ?? null,
                'enrollment_date'     => now()->toDateString(),
                'enrollment_status'   => 'enrolled',
                'assignment_status'   => 'pending',
                'assigned_at'         => null,
                'assigned_by'         => null,
            ]
        );
    }
}