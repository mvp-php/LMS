<?php

namespace CyberEd\Core\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use CyberEd\Core\Helpers\UtilityHelper;
class ModuleChapters extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    public $table = "module_chapters";
    protected $casts =['id'=>'string'];
    public $fillable = ['id', "module_id", 'title', 'description','estimated_time','order','content_metadata','content_type','unlock_criteria','show_as_chapter','activated', "created_at","updated_at","deleted_at"];
    
    

}