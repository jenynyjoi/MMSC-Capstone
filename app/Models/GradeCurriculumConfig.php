<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeCurriculumConfig extends Model
{
    protected $fillable = [
        'grade_level', 'program_level', 'school_year', 'total_subjects_required',
    ];

    public function subjects()
    {
        return $this->hasMany(GradeCurriculumSubject::class, 'curriculum_config_id');
    }
}
