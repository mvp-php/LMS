<?php

namespace CyberEd\Core\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use CyberEd\Core\Helpers\UtilityHelper;
class LearningPaths extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    public $table = "learning_paths";
    protected $casts =['id'=>'string'];
    public $fillable = ['id', "title", 'description', 'requirement','image_name','intro_video','estimated_time','price','is_published','average_rating','activated', "created_at","updated_at","deleted_at"];
    
    

}