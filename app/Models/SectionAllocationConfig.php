<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SectionAllocationConfig extends Model
{
    protected $table = 'section_allocation_config';
    protected $fillable = [
        'section_id','school_year',
        'total_subjects_required','total_subjects_allocated','allocation_status',
    ];

    public function section() { return $this->belongsTo(Section::class); }

    public function recalculate(): void
    {
        $this->total_subjects_allocated = SubjectAllocation::where('section_id',$this->section_id)
            ->where('school_year',$this->school_year)->count();

        if ($this->total_subjects_required > 0) {
            if ($this->total_subjects_allocated >= $this->total_subjects_required)
                $this->allocation_status = 'complete';
            elseif ($this->total_subjects_allocated > 0)
                $this->allocation_status = 'in_progress';
            else
                $this->allocation_status = 'pending';
        } else {
            $this->allocation_status = $this->total_subjects_allocated > 0 ? 'in_progress' : 'pending';
        }
        $this->save();
    }
}