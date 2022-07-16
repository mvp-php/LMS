<?php

namespace CyberEd\App\Controllers\Common;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use CyberEd\Core\Models\User;
use CyberEd\App\Requests\UpdatePasswordRequest;
use CyberEd\App\Requests\ProfileUpdateRequest;
use CyberEd\Core\Helpers\OktaHelper;
use CyberEd\App\Controllers\Common\BaseController;

class ProfileController extends  BaseController
{

    public  function callProfileDetail(Request  $request)
    {
        $auth = auth()->user();
        $query = User::getDetailsById($auth['id']);
        $finalArray['first_name'] = $query->first_name;
        return response()->json(['response_msg' => trans('package_lang::messages.success_res'),  'data' => $query], $this->successStatus);
    }
    public  function callProfileUpdate(ProfileUpdateRequest  $request)
    {
        $auth = auth()->user();
        return $request->all();
        $checkEmailExistOrNot = User::checkEmailExistOrNot($request->email, $auth['id']);
        if ($checkEmailExistOrNot != 0) {
            return response()->json(['response_msg' => trans('package_lang::messages.emailExist'),  'data' => array()], $this->validationStatus);
        }
        $updateOktaDetail = OktaHelper::updateOktaDetails($request->all(), $auth->okta_id);
        if (isset($updateOktaDetail['status']) && $updateOktaDetail['status'] == 'ACTIVE') {
            $flag = 1;
            if ($request->email  !=  $auth['email']) {
                $flag = 0;
            }
            $tempArray  = array(
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,

            );

            $updateDetails =    User::updateData($tempArray, array('id' => $auth['id']));
            return response()->json(['response_msg' => trans('package_lang::messages.profile_update'),  'data' => array(), 'email_status' => $flag], $this->successStatus);
        }
    }

    public function callUpdatePassword(UpdatePasswordRequest $request)
    {
        $auth = auth()->user();
        $updatePassword = OktaHelper::changePassword($request->existing_password, $request->new_password, $auth->okta_id);

        if (isset($updatePassword['errorCode']) && $updatePassword['errorCode'] != '') {

            return response()->json(['response_msg' => $updatePassword['errorCauses'][0]['errorSummary'],  'data' => array()], $this->errorStatus);
        } else {
            return response()->json(['response_msg' => trans('package_lang::messages.password_change_success'),  'data' => array()], $this->successStatus);
        }
    }
    public function callImageUpload(Request $request){
     
        $img = '';
        if ($request->file('file') != '') {
            $files = $path = $request->file('file');
            $name = time() . 'user.' . $files->getClientOriginalExtension();
            $destination = public_path($request->upload_folder);
            $files->move($destination, $name);
            $img = $name;
            $final_response = array('response'=>$img);
            return response()->json(['response_msg' =>"Success",  'data' => array($final_response)], $this->successStatus);
        }

        
    }
}
