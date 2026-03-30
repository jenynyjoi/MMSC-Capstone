<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'student_id', 'reference_number',
        'first_name', 'middle_name', 'last_name', 'suffix',
        'gender', 'date_of_birth', 'place_of_birth', 'nationality',
        'mother_tongue', 'religion', 'lrn',
        'home_address', 'city', 'province', 'zip_code',
        'mobile_number', 'personal_email', 'school_email',
        'father_name', 'father_occupation', 'father_contact',
        'mother_name', 'mother_maiden_name', 'mother_occupation', 'mother_contact',
        'guardian_name', 'guardian_relationship', 'guardian_contact',
        'guardian_address', 'guardian_occupation', 'guardian_email',
        'emergency_contact_number',
        'school_year', 'applied_level', 'grade_level',
        'section_id', 'section_name', 'track', 'strand',
        'admission_type', 'student_category',
        'enrollment_date', 'enrolled_at',
        'student_status', 'academic_status', 'clearance_status', 'enrollment_status',
        'user_id', 'portal_account_created', 'account_created_at',
        'password_changed', 'last_login',
    ];

    protected $casts = [
        'date_of_birth'          => 'date',
        'enrollment_date'        => 'date',
        'enrolled_at'            => 'datetime',
        'account_created_at'     => 'datetime',
        'last_login'             => 'datetime',
        'portal_account_created' => 'boolean',
        'password_changed'       => 'boolean',
    ];

    // ── Generate student ID ──
    public static function generateStudentId(): string
    {
        $year = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;
        return $year . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    // ── Generate school email ──
    public static function generateSchoolEmail(string $firstName, string $lastName, string $middleName = ''): string
    {
        $first = strtolower(preg_replace('/[^a-zA-Z]/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $firstName)));
        $last  = strtolower(preg_replace('/[^a-zA-Z]/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $lastName)));
        $base  = $first . '.' . $last;
        $email = $base . '@mmsc.edu.ph';

        // Check for duplicates
        if (self::where('school_email', $email)->exists()) {
            if ($middleName) {
                $mi    = strtolower(substr(preg_replace('/[^a-zA-Z]/', '', $middleName), 0, 1));
                $email = $first . '.' . $mi . '.' . $last . '@mmsc.edu.ph';
            }
            if (self::where('school_email', $email)->exists()) {
                $count = 1;
                while (self::where('school_email', $base . $count . '@mmsc.edu.ph')->exists()) {
                    $count++;
                }
                $email = $base . $count . '@mmsc.edu.ph';
            }
        }

        return $email;
    }

    // ── Create from application ──
    public static function createFromApplication(Application $app): self
    {
        $studentId  = self::generateStudentId();
        $schoolEmail = self::generateSchoolEmail($app->first_name, $app->last_name, $app->middle_name ?? '');

        return self::create([
            'student_id'       => $studentId,
            'reference_number' => $app->reference_number,
            'first_name'       => $app->first_name,
            'middle_name'      => $app->middle_name,
            'last_name'        => $app->last_name,
            'suffix'           => $app->suffix,
            'gender'           => $app->gender,
            'date_of_birth'    => $app->date_of_birth,
            'nationality'      => $app->nationality,
            'mother_tongue'    => $app->mother_tongue,
            'religion'         => $app->religion,
            'lrn'              => $app->lrn,
            'home_address'     => $app->home_address,
            'city'             => $app->city,
            'zip_code'         => $app->zip_code,
            'mobile_number'    => $app->mobile_number,
            'personal_email'   => $app->personal_email,
            'school_email'     => $schoolEmail,
            'father_name'      => $app->father_name,
            'father_contact'   => $app->father_contact,
            'mother_name'      => $app->mother_name,
            'mother_maiden_name' => $app->mother_maiden_name,
            'mother_contact'   => $app->mother_contact,
            'guardian_name'    => $app->guardian_name,
            'guardian_relationship' => $app->guardian_relationship,
            'guardian_contact' => $app->guardian_contact,
            'guardian_address' => $app->guardian_address,
            'guardian_occupation' => $app->guardian_occupation,
            'guardian_email'   => $app->guardian_email,
            'emergency_contact_number' => $app->emergency_contact_number,
            'school_year'      => $app->school_year,
            'applied_level'    => $app->applied_level,
            'grade_level'      => $app->incoming_grade_level,
            'track'            => $app->track,
            'strand'           => $app->strand,
            'admission_type'   => $app->is_transferee ? 'Transferee' : ($app->student_status === 'Old' ? 'Return' : 'New'),
            'student_category' => $app->student_category,
            'enrolled_at'      => now(),
            'enrollment_date'  => now(),
            'student_status'   => 'active',
            'academic_status'  => 'in_progress',
            'clearance_status' => 'pending',
            'enrollment_status'=> 'enrolled',
            'portal_account_created' => false,
        ]);
    }

    // ── Full name accessor ──
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . ($this->middle_name ? $this->middle_name . ' ' : '') . $this->last_name . ($this->suffix ? ' ' . $this->suffix : ''));
    }

    // ── Relationship to user ──
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}