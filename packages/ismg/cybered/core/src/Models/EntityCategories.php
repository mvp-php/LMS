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
        return $this->hasmany(Courses::class,'id','entity_id');
    }
    
    public function courseSubCategoryrelation()
    {
        return $this->hasmany(Category::class,'id','category_id');
    }
    
    public function courseCategoryrelation()
    {
        return $this->belongsTo(Category::class,'id');
    }

}