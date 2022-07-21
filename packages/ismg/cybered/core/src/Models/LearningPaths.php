<?php

namespace CyberEd\Core\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use CyberEd\Core\Helpers\UtilityHelper;
class LearningPaths extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    public $table = "learning_paths";
    protected $casts =['id'=>'string'];
    public $fillable = ['id', "title", 'description', 'requirement','image_name','intro_video','estimated_time','price','is_published','average_rating','activated', "created_at","updated_at","deleted_at"];
    
    public function entitySubCategoryRelation(){
        return $this->belongsTo(EntityCategories::class , 'id','entity_id');
    }

    public static function getAllData()
    {

        $query = LearningPaths::selectRaw("id,title,is_published,activated,created_at")->whereHas('entitySubCategoryRelation', function ($query) {
            $query->where('entity_type','LearningPath');
        })->with('entitySubCategoryRelation.courseSubCategoryrelation:id,title,parent_category_id')->with('entitySubCategoryRelation.courseSubCategoryrelation.categoryRelation');
        return $query = $query->OrderBy('id', 'desc')->paginate(10);

    }
    public static function getDataById($id)
    {
        return LearningPaths::where('id', $id)->whereHas('entitySubCategoryRelation', function ($query) {
            $query->where('entity_type','LearningPath');
        })->with('entitySubCategoryRelation.courseSubCategoryrelation:id,title,parent_category_id')->with('entitySubCategoryRelation.courseSubCategoryrelation.categoryRelation')->first();
    }
    public static function checkExistOrNot($name,$id=""){
        $query = LearningPaths::where('title',$name);
        if($id !=""){
            $query->where('id','!=',$id);
        }
        $mysql =  $query->count();
        return $mysql;
    }
    public static function updateData($data, $where)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $update = LearningPaths::where($where)->update($data);
        return $update;
    }
    public static function SoftDelete($data, $where)
    {
        $data['deleted_at'] = date('Y-m-d H:i:s');
        $update = LearningPaths::where($where)->update($data);
        return $update;
    }

}