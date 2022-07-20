<?php

namespace CyberEd\Core\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentPlan extends Model
{
    use SoftDeletes;
    public $timestamps = false;

    public $table = "payment_plans";
    public $fillable = ['id', "title", 'price', 'duration','including_item','activated', "created_at","updated_at","deleted_at"];
    protected $casts =['id'=>'string'];
    public static function getPaymentPlanList(){
        $query = PaymentPlan::select('id','title')->orderBy('title','asc')->get();
        return $query;
    }
}
