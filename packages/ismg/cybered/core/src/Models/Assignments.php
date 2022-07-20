<?php

namespace CyberEd\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use CyberEd\Core\Helpers\UtilityHelper;

class Assignments extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    public $table = "assignments";
    protected $casts = ['id' => 'string'];
    public $fillable = ['id', "title",'description', 'estimated_time','passing_criteria','activated', "created_at", "updated_at", "deleted_at"];


    public static function updateData($data, $where)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $update = Assignments::where($where)->update($data);
        return $update;
    }
    public static function SoftDelete($data, $where)
    {
        $data['deleted_at'] = date('Y-m-d H:i:s');
        $update = Assignments::where($where)->update($data);
        return $update;
    }

    public static function checkExistOrNot($name, $id = "")
    {

        $query = Assignments::where('title', $name);
        if ($id != "") {
            $query->where('id', '!=', $id);
        }
        $mysql =  $query->count();
        return $mysql;
    }
    public static function getAllData($search)
    {
        $query = Assignments::selectRaw("id,title,description,estimated_time,passing_criteria,created_at");
        if ($search != "") {
            $query->whereRaw("LOWER(title) LIKE '%" . strtolower($search) . "%' OR  description LIKE '%" . strtolower($search) . "%' ")->orwhereDate('created_at', UtilityHelper::getConvertMDYToYMD($search));
        }

        return $query = $query->OrderBy('id', 'desc')->paginate(10);
    }


    public static function getDataById($id)
    {
        return Assignments::where('id', $id)->first();
    }
}
