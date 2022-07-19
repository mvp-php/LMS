<?php

namespace CyberEd\Core\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instructor extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    public $table = "instructors";
    protected $guarded = ["id"];
    public $fillable = ['id', "user_id", 'role_id', 'approved_by','instructor_name','profile_description','profile_image_name','average_rating','valid_from','valid_till','activated', "created_at","updated_at","deleted_at"];
    protected $casts =['id'=>'string'];
    
    public static function saveData($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $insert = new Instructor($data);
        $insert->save();
        $insertId = $insert->id;
        return $insertId;
    }
    public static function updateData($data, $where)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $update = Instructor::where($where)->update($data);
        return $update;
    }
    public static function SoftDelete($data, $where)
    {
        $data['deleted_at'] = date('Y-m-d H:i:s');
        $update = Instructor::where($where)->update($data);
        return $update;
    }
  

    public static function getInstructorExistOrNot($id){
        $query = Instructor::where('user_id',$id)->count();
        return $query;
    }

    public static function getAllData(){
        $query = Instructor::where('activated','1')->get();
        return $query;
    }

    
}