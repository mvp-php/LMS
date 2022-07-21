<?php

namespace CyberEd\Core\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use CyberEd\Core\Helpers\UtilityHelper;
class EntityCategories extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    public $table = "entity_categories";
    protected $casts =['id'=>'string'];
    public $fillable = ['id', "category_id", 'entity_id', 'entity_type','activated', "created_at","updated_at","deleted_at"];
    
    
    public function courseRelation()
    {
        return $this->hasMany(Courses::class,'id','entity_id');
    }
    
    public function courseSubCategoryrelation()
    {
        return $this->hasMany(Category::class,'id','category_id');
    }
    
    public function courseCategoryrelation()
    {
       return $this->belongsTo(Category::class,'id');
    }
    public static function checkExistOrNot($category_id,$entity_id){
        $query = EntityCategories::where('category_id',$category_id)->where('entity_id',$entity_id)->where('entity_type','LearningPath');
        $mysql =  $query->count();
        return $mysql;
    }
    public static function updateData($data, $where)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $update = EntityCategories::where($where)->update($data);
        return $update;
    }
}