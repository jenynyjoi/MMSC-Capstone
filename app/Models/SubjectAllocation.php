<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SubjectAllocation extends Model
{
    protected $table = 'subject_allocation';
    protected $fillable = [
        'section_id','subject_id','school_year','teacher_id',
        'subject_code','subject_name','hours_per_week','created_by',
    ];

    public function section()  { return $this->belongsTo(Section::class); }
    public function subject()  { return $this->belongsTo(Subject::class); }
    public function teacher()  { return $this->belongsTo(\App\Models\User::class,'teacher_id'); }
    public function schedules(){ return $this->hasMany(SubjectSchedule::class,'allocation_id'); }
    public function assessments(){ return $this->hasMany(Assessment::class,'allocation_id'); }
}