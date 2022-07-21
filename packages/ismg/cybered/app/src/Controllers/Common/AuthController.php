<?php

namespace CyberEd\App\Controllers\Common;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use CyberEd\Core\Helpers\OktaHelper;
use CyberEd\Core\Helpers\MailHelper;
use Illuminate\Support\Facades\Auth;
use CyberEd\Core\Models\User;
use CyberEd\App\Requests\LoginRequest;
use CyberEd\App\Requests\ForgotPasswordRequest;
use CyberEd\App\Requests\ResetPasswordRequest;
use Validator;
use CyberEd\App\Controllers\Common\BaseController;
class AuthController extends  BaseController
{

    public function signUp(Request $request){
        
        $oktaDetails = OktaHelper::registration($request->all());
        $oktaDetailsMaster = json_decode($oktaDetails);
        if(isset($oktaDetailsMaster->id)){
         
            $final = array(
                'okta_id'=> $oktaDetailsMaster->id,
                'first_name'=>$request->first_name,
                'last_name'=>$request->last_name,
                'email'=>$request->email,
                'mobile_number'=>$request->mobile_no
            );
            User::saveData($final);
        }
    }

    public function callLogin(LoginRequest $request)
    {
       
        $oktaDetailsMaster = OktaHelper::login($request->email, $request->password);
        $response = json_decode($oktaDetailsMaster);
        if (isset($response->status) && $response->status == 'SUCCESS') {
            $user = User::getUserDetailByOktaId($response->_embedded->user->id);
            $authorize = Auth::loginUsingId($user->id, TRUE);
            $tokendetails =  $authorize->createToken('MyApp')->plainTextToken;
            $authorize->token = $tokendetails;

            User::updateData(array('user_token' => $response->sessionToken), array('id' => $user->id));
            return response()->json(['response_msg' => trans('package_lang::messages.loginSuccess'),  'data' => array($authorize)], $this->successStatus);
        } else {
            return response()->json(['response_msg' => trans('package_lang::messages.loginError'),  'data' => array()], $this->errorStatus);
        }
    }


    function callForgotPassword(ForgotPasswordRequest $request)
    {
     
        $getUserDetails = OktaHelper::getUserDetailsByEmail($request->email);
        $getUserDetailsAll = json_decode($getUserDetails,true);
        
        if (isset($getUserDetailsAll[0]['id']) && $getUserDetailsAll[0]['id'] != '') {
            $mailstatus = $this->sendMail($getUserDetails[0]['id'],'forgot','Password Reset Email');
           
            if ($mailstatus) {
                return response()->json(['response_msg' => trans('package_lang::messages.link_success'),  'data' => array()], $this->successStatus);
            } else {
                return response()->json(['response_msg' => trans('package_lang::messages.error_msg'),  'data' => array()], $this->errorStatus);
            }
        } else {
            return response()->json(['response_msg' =>trans('package_lang::messages.email_not_available'),  'data' => array()], $this->errorStatus);
        }
    }
    function callResetPassword(ResetPasswordRequest $request, $id)
    {
       
        $updatePassword = OktaHelper::resetPassword($request->password, $id);
        $response = json_decode($updatePassword);
        if (isset($response->statu) && $response->statu == 'ACTIVE') {
           
            $mailstatus = $this->sendMail($id,'resetPassword','Reset Password Successful');
            return response()->json(['response_msg' => trans('package_lang::messages.password_change_success'),  'data' => array()], $this->successStatus);
        } else {
            return response()->json(['response_msg' => trans('package_lang::messages.error_msg'),  'data' => array()], $this->errorStatus);
        }
    }

    function callLogout(Request $request){

        $request->user()->currentAccessToken()->delete();


        return response()->json(['message' => 'Successfully logged out', 'status' => 1, 'data' => array()], $this->successStatus);
    }
    function imageUpload(Request $request){
        $validator = Validator::make($request->all(), [
            'image' => 'required|mimes:jpeg,jpg,png,gif',
        ]);
        if ($validator->fails()) {
            return response()->json(['response_msg' => $validator->errors()->all()[0],  'data' => array()], $this->validationStatus);
        } else {
            $images = $request->file('image');
            $n = 0;
            $testaray = "";
            $path = "dropzoneimages/";
            $imageName = time() . '_' . rand(111, 999) . '.' . $images->getClientOriginalExtension();
            $images->move(storage_path($path), $imageName);
            $name =   storage_path().'/'.$path . '' . $imageName;
            $testaray = $name;
            $n++;
            if ($testaray != '') {
                return $testaray;
            }
        }
    }

    function sendMail($okta_id,$type,$message){
        $userDetails = User::getUserDetailByOktaId($okta_id);
        $fname = isset($userDetails->first_name)?$userDetails->first_name:"";
        $lname = isset($userDetails->last_name)?$userDetails->last_name:"";

    
        $details = [
            'title' => $message,
            'username'=>$fname.' '.$lname,
            'subject'=> $message,
            'user_id'=>$okta_id,
            'type'=>$type
        ];
        $mailstatus = MailHelper::sendMail($userDetails->email,$details);
        return 1;
    }

    
}