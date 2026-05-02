<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GradeComponent extends Model
{
    protected $table = 'grade_components';
    protected $fillable = ['component_code','component_name','grade_percentage','grade_level','is_active'];
    protected $casts = ['is_active'=>'boolean'];
}