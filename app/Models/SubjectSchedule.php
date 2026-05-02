<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SubjectSchedule extends Model
{
    protected $table = 'subject_schedule';
    protected $fillable = ['allocation_id','day_of_week','time_start','time_end','room'];

    public function allocation()
    {
        return $this->belongsTo(SubjectAllocation::class, 'allocation_id');
    }
}