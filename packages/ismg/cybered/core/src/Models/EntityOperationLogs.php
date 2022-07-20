<?php

namespace CyberEd\Core\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use CyberEd\Core\Helpers\UtilityHelper;
class EntityOperationLogs extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    public $table = "entity_operation_logs";

    public $fillable = ['id', "user_id", 'entity_id', 'entity_type','action_taken','request_params', "created_at"];
    protected $casts =['id'=>'string'];
    public static function saveData($data)
    {
        $auth=auth()->user();
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['user_id'] = $auth['id'];
        $insert = new EntityOperationLogs($data);
        $insert->save();
        $insertId = $insert->id;
        return $insertId;
    }
    
}