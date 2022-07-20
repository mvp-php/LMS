<?php

namespace CyberEd\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use CyberEd\Core\Helpers\UtilityHelper;
class SubCategory extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = ["id"];
    protected $table = "categories";
    protected $casts =['id'=>'string'];

    public function categoryRelation(){
        return $this->belongsTo(Category::class , 'parent_category_id');
    }

    public static function getAllSubcategoryList($parent_category_id,$search)
    {
        $query = SubCategory::selectRaw("id,title,description,created_at,parent_category_id")->with('categoryRelation')->where('parent_category_id',$parent_category_id)->whereNull('deleted_at');
        if($search !=""){
            $query->whereRaw("LOWER(title) LIKE '%".strtolower($search)."%'")->orwhereDate('created_at',UtilityHelper::getConvertMDYToYMD($search))->orwhereHas('categoryRelation', function($q)use($search){
                $q->whereRaw("LOWER(title)  LIKE '%".strtolower($search)."%'");
            });
        }
        
        return $query = $query->OrderBy('id', 'desc')->paginate(10);
    }
}