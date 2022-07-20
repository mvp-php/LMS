<?php

namespace CyberEd\Core\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use CyberEd\Core\Helpers\UtilityHelper;
class Category extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    public $table = "categories";
    protected $casts =['id'=>'string'];
    public $fillable = ['id', "parent_category_id", 'title', 'description','image_name','activated', "created_at","updated_at","deleted_at"];
    
    public function categoryRelation(){
        return $this->belongsTo(Category::class , 'parent_category_id');
    }
    public static function updateData($data, $where)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $update = Category::where($where)->update($data);
        return $update;
    }
    public static function SoftDelete($data, $where)
    {
        $data['deleted_at'] = date('Y-m-d H:i:s');
        $update = Category::where($where)->update($data);
        return $update;
    }

    public static function checkExistOrNot($name,$id=""){
        
        $query = Category::where('title',$name);
        if($id !=""){
            $query->where('id','!=',$id);
        }
$mysql =  $query->count();
return $mysql;
    }
    public static function getAllData($search)
    {
        $query = Category::selectRaw("id,title,description,created_at,parent_category_id")->whereNull('parent_category_id');
        if($search !=""){
            $query->whereRaw("LOWER(title) LIKE '%".strtolower($search)."%' OR  description LIKE '%".strtolower($search)."%' ")->orwhereDate('created_at',UtilityHelper::getConvertMDYToYMD($search));
        }
        
        return $query = $query->OrderBy('id', 'desc')->paginate(10);
    }

    public static function getMailCategoryList(){
        $query = Category::select('id','title')->whereNull('parent_category_id')->orderBy('title','asc')->get();
        return $query;
    }
    public static function getDataById($id)
    {
        return Category::where('id', $id)->first();
    }

   
    
}