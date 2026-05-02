<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';

    protected $fillable = [
        'subject_code', 'subject_name', 'description',
        'department', 'grade_level', 'program_level', 'subject_type', 'track', 'strand',
        'hours_per_meeting', 'meetings_per_week',
        'has_semester', 'default_semester', 'is_active', 'created_by',
    ];

    protected $casts = [
        'has_semester' => 'boolean',
        'is_active'    => 'boolean',
    ];

    // Computed accessor — hours_per_meeting × meetings_per_week
    public function getHoursPerWeekAttribute(): float
    {
        return (float) $this->hours_per_meeting * (int) $this->meetings_per_week;
    }

    // Make it available in toArray() / JSON
    protected $appends = ['hours_per_week'];

    public function allocations()
    {
        return $this->hasMany(SubjectAllocation::class);
    }
}