<?php

namespace CyberEd\Core\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCustomPayment extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    public $table = "user_custom_payments";
    public $fillable = ['id', "user_id", 'entity_id', 'entity_type','price','valid_from','valid_till','payment_url','activated', "created_at","updated_at","deleted_at"];
    protected $casts =['id'=>'string'];

    public function paymentPlans(){
        return $this->belongsTo(PaymentPlan::class, 'entity_id', 'id');
    }

    public static function saveData($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $insert = new UserCustomPayment($data);
        $insert->save();
        $insertId = $insert->id;
        return $insertId;
    }
    public static function updateData($data, $where)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $update = UserCustomPayment::where($where)->update($data);
        return $update;
    }
    public static function SoftDelete($data, $where)
    {
        $data['deleted_at'] = date('Y-m-d H:i:s');
        $update = UserCustomPayment::where($where)->update($data);
        return $update;
    }
    public static function getPaymentPlanList(){
        $query = PaymentPlan::select('id','title')->orderBy('title','asc')->get();
        return $query;
    }

    public static function getUserPaymentAddOrNot($id){
        $query = UserCustomPayment::where('user_id',$id)->count();
        return $query;
    }
}
