<?php

namespace CyberEd\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use CyberEd\Core\Helpers\UtilityHelper;
use Illuminate\Support\Facades\DB;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    
    public $fillable = ['id', "first_name", 'last_name', 'email','email_verified_at','mobile_number','remember_token','okta_id','activated', "created_at","updated_at","deleted_at"];
    protected $casts =['id'=>'string'];

    public function userRoleRelationShip(){
        return $this->belongsTo(UserRole::class, 'id', 'user_id');
    }
    public function userCustomPayment(){
        return $this->belongsTo(UserCustomPayment::class, 'id', 'user_id');
    }
    public function userInstructor(){
        return $this->belongsTo(Instructor::class, 'id', 'user_id');
    }
    

    public static function saveData($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $insert = new User($data);
        $insert->save();
        $insertId = $insert->id;
        return $insertId;
    }
    public static function updateData($data, $where)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $update = User::where($where)->update($data);
        return $update;
    }
    public static function SoftDelete($data, $where)
    {
        $data['deleted_at'] = date('Y-m-d H:i:s');
        $update = User::where($where)->update($data);
        return $update;
    }
    public static function getUserDetailByOktaId($oktaId){
       
        $query = User::where('okta_id',$oktaId)->first();
        return $query;
    }

    public static function checkEmailExistOrNot($email,$id=""){
        $query = User::where('email',$email);
                if($id !=""){
                    $query->where('id','!=',$id);
                }
        $mysql =  $query->count();
        return $mysql;
    }

    public static function getUserList($search){
     
        $auth = auth()->user();
        $query = User::selectRaw("'' as key,users.id,CONCAT(users.first_name,' ',users.last_name) as first_name,users.email,users.created_at")->with('userRoleRelationShip.roleRelationShip')->whereNull('users.deleted_at');
        if($search !=""){
            $query->whereRaw("LOWER(CONCAT(users.first_name,' ',users.last_name)) LIKE '%".strtolower($search)."%' OR LOWER(users.email) LIKE '".strtolower($search)."' ")
            ->orwhereDate('users.created_at',UtilityHelper::getConvertMDYToYMD($search))->orwhereHas('userRoleRelationShip.roleRelationShip', function($q)use($search){
                $q->where(DB::raw("LOWER(title)"),'LIKE','%'.strtolower($search).'%');
            });
        }
        $query = $query->orderBy('users.created_at','desc')->paginate(10);
      
  
        return $query;
    }

    public static function getDetailsById($id){
        $query = User::selectRaw("users.id,users.first_name,users.last_name,users.email,users.created_at")->with('userRoleRelationShip.roleRelationShip','userInstructor','userCustomPayment.paymentPlans')

        ->where('users.id',$id)->first();
      
        return $query;
    }

    public static function checkDeleteUserDetail($email){
            
            $query = User::whereNotNull('deleted_at')->whereRaw("email LIKE '%".strtolower($email)."%'")->first();
            return $query;
    }
}
