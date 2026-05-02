<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BehavioralRecord extends Model
{
    protected $table = 'behavioral_records';

    protected $fillable = [
        'student_id', 'enrollment_id', 'school_year',
        'grade_level', 'section_name',
        'incident_date', 'behavior_type', 'severity',
        'action_taken', 'action_details', 'referral_to',
        'description', 'resolution_notes', 'status',
        'parent_notified', 'parent_notified_at',
        'recorded_by', 'updated_by',
    ];

    protected $casts = [
        'incident_date'      => 'date',
        'parent_notified'    => 'boolean',
        'parent_notified_at' => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function enrollment()
    {
        return $this->belongsTo(StudentEnrollment::class, 'enrollment_id');
    }

    public function documents()
    {
        return $this->hasMany(BehavioralDocument::class, 'behavioral_record_id');
    }

    public function recorder()
    {
        return $this->belongsTo(\App\Models\User::class, 'recorded_by');
    }

    // ── Accessors ──────────────────────────────────────────
    public function getDisplayNameAttribute(): string
    {
        $strand = $this->enrollment?->strand;
        return Section::formatName($this->grade_level ?? '—', $this->section_name ?? '—', $strand);
    }

    public function getSeverityColorAttribute(): string
    {
        return match($this->severity) {
            'Minor'    => 'bg-blue-50 text-blue-700',
            'Moderate' => 'bg-orange-50 text-orange-700',
            'Major'    => 'bg-red-50 text-red-700',
            'Critical' => 'bg-red-100 text-red-800',
            default    => 'bg-slate-100 text-slate-600',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'bg-yellow-100 text-yellow-700',
            'resolved'  => 'bg-green-100 text-green-700',
            'dismissed' => 'bg-slate-100 text-slate-500',
            'escalated' => 'bg-red-100 text-red-700',
            default     => 'bg-slate-100 text-slate-600',
        };
    }
}