<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolCalendarEvent extends Model
{
    protected $fillable = [
        'school_year',
        'date',
        'day_type',
        'event_title',
        'description',
        'time_from',
        'time_to',
        'early_dismissal_time',
        'attendance_rule',
        'applies_to',
        'notify_teachers',
        'notify_parents',
        'add_to_public',
        'send_reminder',
    ];

    protected $casts = [
        'date'             => 'date',
        'notify_teachers'  => 'boolean',
        'notify_parents'   => 'boolean',
        'add_to_public'    => 'boolean',
        'send_reminder'    => 'boolean',
    ];

    // ── Scopes ──────────────────────────────────────────────

    public function scopeForYear($query, $year)
    {
        return $query->where('school_year', $year);
    }

    public function scopeForMonth($query, $year, $month)
    {
        return $query->whereYear('date', $year)->whereMonth('date', $month);
    }

    // ── Helpers ─────────────────────────────────────────────

    /** Tailwind bg colour for calendar grid badges */
    public function badgeClass(): string
    {
        return match ($this->day_type) {
            'holiday'         => 'bg-purple-100 text-purple-700',
            'suspended'       => 'bg-red-100 text-red-700',
            'early_dismissal' => 'bg-amber-100 text-amber-700',
            'exam_day'        => 'bg-blue-100 text-blue-700',
            'school_event'    => 'bg-yellow-100 text-yellow-700',
            'break'           => 'bg-orange-100 text-orange-700',
            default           => 'bg-green-100 text-green-700',   // regular
        };
    }

    /** Human-readable day type label */
    public function dayTypeLabel(): string
    {
        return match ($this->day_type) {
            'regular'         => 'Regular Class',
            'holiday'         => 'Holiday',
            'suspended'       => 'Suspended',
            'early_dismissal' => 'Early Dismissal',
            'exam_day'        => 'Exam Day',
            'school_event'    => 'School Event',
            'break'           => 'Break',
            default           => ucfirst($this->day_type),
        };
    }
}