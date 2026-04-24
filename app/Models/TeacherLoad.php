<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TeacherLoad extends Model
{
    protected $table = 'teacher_load';
    protected $fillable = ['teacher_id','school_year','max_weekly_hours','current_weekly_hours'];
    public function teacher() { return $this->belongsTo(\App\Models\User::class, 'teacher_id'); }
}