<?php

namespace CyberEd\Core\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermissionRole extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    public $table = 'permission_role';
    public $fillable = [
        'id', 'role_id', 'permission_id', 'activated', 'created_at', 'updated_at', 'deleted_at'
    ];
    protected $casts =['id'=>'string'];
    public static function saveData($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $insert = new PermissionRole($data);
        $insert->save();
        $insertId = $insert->id;
        return $insertId;
    }
    public static function updateData($data, $where)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $update = PermissionRole::where($where)->update($data);
        return $update;
    }
    public static function SoftDelete($data, $where)
    {
        $data['deleted_at'] = date('Y-m-d H:i:s');
        $update = PermissionRole::where($where)->update($data);
        return $update;
    }
    public static function getPermission($id){
        $query = PermissionRole::select('permission_id')->where('role_id',$id)->get();
        return $query;
    }
}
