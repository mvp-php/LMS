<?php

namespace CyberEd\Core\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    public $table = 'permissions';
    public $fillable = [
        'id', 'title', 'description', 'table_name', 'action_name', 'module_name', 'activated', 'created_at', 'updated_at', 'deleted_at'
    ];
    protected $casts =['id'=>'string'];
    public static function GetPermissionList(){
        $oPermissions = Permission::where('activated',1)->get();
        return $oPermissions;
    }

}
