<?php

namespace CyberEd\Core\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use CyberEd\Core\Helpers\UtilityHelper;
class CourseModules extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    public $table = "course_modules";
    protected $casts =['id'=>'string'];
    public $fillable = ['id', "course_id", 'title', 'description','estimated_time','order','unlock_criteria','activated', "created_at","updated_at","deleted_at"];
    
    

}