<?php

namespace CyberEd\Core\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use CyberEd\Core\Helpers\UtilityHelper;
class EntityInstructor extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    public $table = "entity_instructor";
    protected $casts =['id'=>'string'];
    public $fillable = ['id', "instructor_id", 'entity_id', 'entity_type','activated', "created_at","updated_at","deleted_at"];
    
    
   

}