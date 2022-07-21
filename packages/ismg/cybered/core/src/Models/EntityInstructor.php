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
    
    
    public static function checkExistOrNot($instructor_id,$entity_id){
        $query = EntityInstructor::where('instructor_id',$instructor_id)->where('entity_id',$entity_id)->where('entity_type','LearningPath');
        $mysql =  $query->count();
        return $mysql;
    }
    public static function updateData($data, $where)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $update = EntityInstructor::where($where)->update($data);
        return $update;
    }

}