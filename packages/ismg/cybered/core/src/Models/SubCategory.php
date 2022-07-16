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

   
    
}