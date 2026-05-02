<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherProfile extends Model
{
    protected $table = 'teacher_profiles';

    protected $fillable = [
        'user_id', 'teacher_id_code',
        'first_name', 'last_name', 'middle_name', 'contact_number', 'personal_email',
        'academic_rank', 'employment_status', 'status',
        'department', 'specializations', 'grade_levels', 'advisory_class', 'school_year',
        'weekly_days_available', 'available_from', 'available_to',
        'lunch_start', 'lunch_end',
    ];

    protected $casts = [
        'specializations' => 'array',
        'grade_levels'    => 'array',
    ];

    protected static $nameFields = ['first_name', 'last_name', 'middle_name'];

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

    public function getFormattedNameAttribute(): string
    {
        $last  = strtoupper($this->last_name  ?? '');
        $first = strtoupper($this->first_name ?? '');
        $mi    = $this->middle_name ? ' ' . strtoupper(substr($this->middle_name, 0, 1)) . '.' : '';
        return $last . ', ' . $first . $mi;
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    // Current weekly hours auto-calculated from teacher_load
    public function teacherLoad()
    {
        return $this->hasOneThrough(
            TeacherLoad::class,
            \App\Models\User::class,
            'id',        // users.id
            'teacher_id', // teacher_load.teacher_id
            'user_id',   // teacher_profiles.user_id
            'id'         // users.id
        );
    }

    // Accessor: load status
    public function getLoadStatusAttribute(): string
    {
        $load = TeacherLoad::where('teacher_id', $this->user_id)
            ->where('school_year', $this->school_year ?? '2026-2027')
            ->first();

        if (!$load) return 'unassigned';
        $pct = $load->max_weekly_hours > 0
            ? ($load->current_weekly_hours / $load->max_weekly_hours) * 100
            : 0;

        if ($pct >= 100) return 'overloaded';
        if ($pct >= 60)  return 'loaded';
        return 'underloaded';
    }
}