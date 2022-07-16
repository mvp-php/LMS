<?php

namespace CyberEd\Core\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserRole extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    public $table = "role_user";
    public $fillable = ['id', "role_id", 'user_id', 'activated', "created_at","updated_at","deleted_at"];
    protected $casts =['id'=>'string'];

    public function roleRelationShip(){
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
    
    public static function saveData($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $insert = new UserRole($data);
        $insert->save();
        $insertId = $insert->id;
        return $insertId;
    }
    public static function updateData($data, $where)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $update = UserRole::where($where)->update($data);
        return $update;
    }
    public static function SoftDelete($data, $where)
    {
        $data['deleted_at'] = date('Y-m-d H:i:s');
        $update = UserRole::where($where)->update($data);
        return $update;
    }
    public static function getTotalUserCountByRoleId($id){
        $query = UserRole::join('users',function($join){
            $join->on('users.id','=','role_user.user_id');
            $join->whereNull('users.deleted_at');
        })->where('role_id',$id)->count();
        return $query;
    }
    public static function getRoleAddorNot($id){
        $auth = auth()->user();
        $query = UserRole::where('user_id',$id)->where('user_id','!=',$auth['id'])->count();
        return $query;
    }


    public static function getExistingRole($role_id){
        $query = UserRole::select('id')->where('role_id',$role_id)->get();
        return $query;
    }
}
