<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $table = 'assessments';
    protected $fillable = ['allocation_id','component_id','quarter','assessment_name','max_score','assessment_date','created_by'];
    protected $casts = ['assessment_date'=>'date'];

    public function allocation(){ return $this->belongsTo(SubjectAllocation::class,'allocation_id'); }
    public function component() { return $this->belongsTo(GradeComponent::class,'component_id'); }
}