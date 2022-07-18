<?php

namespace CyberEd\Core\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use CyberEd\Core\Helpers\UtilityHelper;
class Courses extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    public $table = "courses";
    protected $casts =['id'=>'string'];
    public $fillable = ['id', "title", 'description', 'requirement','image_name','intro_video','estimated_time','price','is_published','average_rating','activated', "created_at","updated_at","deleted_at"];
    
    public function entitySubCategoryRelation(){
        return $this->belongsTo(EntityCategories::class , 'id','entity_id');
    }

    public static function getAllData()
    {
        $query = Courses::selectRaw("id,title,description,requirement,is_published,activated,created_at")->whereHas('entitySubCategoryRelation', function ($query) {
            $query->where('entity_type','Course');
        })->with('entitySubCategoryRelation.courseSubCategoryrelation:id,title')->with('entitySubCategoryRelation.courseCategoryrelation:id,title');
        return $query = $query->OrderBy('id', 'desc')->paginate(10);
    }

}