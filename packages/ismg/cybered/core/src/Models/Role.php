<?php

namespace CyberEd\Core\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use CyberEd\Core\Helpers\UtilityHelper;
class Role extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    public $table = "roles";
    public $fillable = ['id', "title", 'description', 'flag','is_system_role','activated', "created_at","updated_at","deleted_at"];
    protected $casts =['id'=>'string'];
    public static function saveData($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $insert = new Role($data);
        $insert->save();
        $insertId = $insert->id;
        return $insertId;
    }
    public static function updateData($data, $where)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $update = Role::where($where)->update($data);
        return $update;
    }
    public static function SoftDelete($data, $where)
    {
        $data['deleted_at'] = date('Y-m-d H:i:s');
        $update = Role::where($where)->update($data);
        return $update;
    }

    public static function getRoleList($search){
        $query  = Role::selectRaw("id,title,created_at,is_system_role")->where('activated',1);
        if($search !=""){
            $query->whereRaw("LOWER(title) LIKE '%".strtolower($search)."%' or DATE_FORMAT('created_at','%Y-%m-%d') ='".date('Y-m-d',strtotime($search))."'");
        }
            
        $query  = $query->orderBy('created_at','desc')->paginate(10);

        return $query;
    }

    public static function getDetailsById($id){
        $query = Role::selectRaw('*,id')->where('id',$id)->where('activated',1)->first();
        return $query;
    }

    public static function getAllRoleList(){
        $query = Role::selectRaw('id,title,is_system_role,flag')->where('activated',1)->orderBy('title','asc')->get();
        return $query;
    }

       
    public static function checkRoleExistOrNot($roleTitle,$id=""){
       $query = Role::where('activated',1)->whereRaw("LOWER(title) LIKE '%".strtolower($roleTitle)."%'");
       if($id != ''){
        $query->where('id','!=',$id);
       }

       $query = $query->count();

       return $query;
    }

    public static function getSystemRole(){
        $query = Role::selectRaw('id')->where('activated',1)->where('is_system_role',1)->get();
        return $query;
    }

   
}
