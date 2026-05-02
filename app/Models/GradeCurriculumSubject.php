<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeCurriculumSubject extends Model
{
    protected $fillable = [
        'curriculum_config_id', 'subject_id',
        'hours_per_week', 'meetings_per_week', 'hours_per_meeting',
        'subject_type', 'is_required', 'semester',
    ];

    protected $casts = ['is_required' => 'boolean'];

    public function config()
    {
        return $this->belongsTo(GradeCurriculumConfig::class, 'curriculum_config_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
