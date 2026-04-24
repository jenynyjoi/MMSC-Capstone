<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    use HasFactory;

    protected $table = 'sections';

    protected $fillable = [
        'section_id', 'school_year', 'grade_level', 'section_name', 'full_name',
        'capacity', 'current_enrollment', 'waitlist_count',
        'room', 'homeroom_adviser_id', 'homeroom_adviser_name', 'adviser_status',
        'track', 'strand', 'program_level',
        'availability', 'section_status', 'section_type',
        'is_subject_section', 'subject_id', 'subject_name',
        'teacher_id', 'teacher_name',
        'schedule_day', 'schedule_time_start', 'schedule_time_end',
    ];

    protected $casts = [
        'is_subject_section' => 'boolean',
    ];

    // ── Accessors ──────────────────────────────────────────
    public function getAvailableSlotsAttribute(): int
    {
        return max(0, $this->capacity - $this->current_enrollment);
    }

    public function getIsFullAttribute(): bool
    {
        return $this->current_enrollment >= $this->capacity;
    }

    public function getIsAvailableAttribute(): bool
    {
        return $this->section_status === 'active' && !$this->is_full;
    }

    public function getCapacityDisplayAttribute(): string
    {
        return $this->current_enrollment . '/' . $this->capacity;
    }

    // ── Availability label ─────────────────────────────────
    public function updateAvailability(): void
    {
        $slots = $this->available_slots;

        $this->availability = match (true) {
            $this->current_enrollment >= $this->capacity         => 'full',
            $slots <= 5                                          => 'near_capacity',
            $this->current_enrollment < 20                       => 'below_minimum',
            default                                              => 'available',
        };

        $this->save();
    }

    // ── Section display name ───────────────────────────────
    // SHS (Grade 11/12): "Grade 12 STEM E"
    // All others:        "Grade 7 - A"
    public static function formatName(string $gradeLevel, string $sectionName, ?string $strand = null): string
    {
        if (in_array($gradeLevel, ['Grade 11', 'Grade 12']) && $strand) {
            return "{$gradeLevel} {$strand} {$sectionName}";
        }
        return "{$gradeLevel} - {$sectionName}";
    }

    public function getDisplayNameAttribute(): string
    {
        return static::formatName($this->grade_level, $this->section_name, $this->strand);
    }

    // ── Relationships ──────────────────────────────────────
    public function enrollments()
    {
        return $this->hasMany(StudentEnrollment::class, 'section_id');
    }

    public function allocationConfig()
    {
        return $this->hasOne(SectionAllocationConfig::class, 'section_id');
    }
}