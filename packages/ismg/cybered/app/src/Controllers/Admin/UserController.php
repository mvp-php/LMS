<?php

namespace CyberEd\App\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use CyberEd\Core\Models\User;
use CyberEd\Core\Helpers\UtilityHelper;
use CyberEd\App\Requests\UserRequest;
use CyberEd\Core\Helpers\OktaHelper;
use CyberEd\Core\Helpers\MailHelper;
use CyberEd\Core\Models\PaymentPlan;
use URL;
use CyberEd\Core\Models\UserRole;
use CyberEd\Core\Models\Role;
use CyberEd\Core\Models\UserCustomPayment;
use CyberEd\Core\Models\Instructor;
use CyberEd\Core\Models\EntityOperationLogs;
use CyberEd\App\Controllers\Common\BaseController;

class UserController extends  BaseController
{
    public function createAdminUser()
    {
        $final_array = array('first_name' => 'Cybered', 'last_name' => 'Ed', 'email' => 'admin@cybered.com', 'password' => 'Newpass123!', 'mobile_no' => '1234567890');
        $oktaDetails = OktaHelper::registration($final_array);
        if (isset($oktaDetails['id'])) {

            $final = array(
                'okta_id' => $oktaDetails['id'],
                'first_name' => 'Cybered',
                'last_name' => 'Ed',
                'email' => 'admin@cybered.com',
                'mobile_number' => '1234567890'
            );
            User::saveData($final);
        }
    }
    public function callUserList(Request $request)
    {

        $userList = User::getUserList($request->search);
        if (count($userList) > 0) {
            $statusMsg = trans('package_lang::messages.success_res');
        } else {
            $statusMsg = trans('package_lang::messages.no_record_available');
        }

        return response()->json(['response_msg' => $statusMsg,  'data' => $userList], $this->successStatus);
    }

    public function callSaveUser(UserRequest $request)
    {
        $auth = auth()->user();

        $checkEmailExistOrNot = User::checkEmailExistOrNot($request->email);
        if ($checkEmailExistOrNot != 0) {
            return response()->json(['response_msg' => trans('package_lang::messages.emailExist'),  'data' => array()], $this->validationStatus);
        }

        $oktaArray = array(
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        );

        $oktaDetails = OktaHelper::userRegistration($oktaArray);

        if (isset($oktaDetails['id'])) {

            $final = array(
                'okta_id' => $oktaDetails['id'],
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'mobile_number' => ''
            );
            $userDetails = User::saveData($final);
            if ($request->role_id != "") {
                $roleUserArray = array(
                    'role_id' => $request->role_id,
                    'user_id' => $userDetails
                );
                UserRole::saveData($roleUserArray);
            }
            $getRoleDetails = Role::getDetailsById($request->role_id);

            if (trim($getRoleDetails->flag) == 'Student' && $getRoleDetails->is_system_role == 1) {
                UserCustomPayment::saveData(array('user_id' => $userDetails, 'entity_id' => $request->entity_id, 'entity_type' => 'PaymentPlan', 'price' => $request->amount, 'valid_from' => UtilityHelper::convertMDYToYMD($request->valid_from), 'valid_till' => UtilityHelper::convertMDYToYMD($request->valid_till), 'payment_url' => ''));
            } else if (trim($getRoleDetails->flag) == 'Instructor' && $getRoleDetails->is_system_role == 1) {
                if ($request->valid_from != '') {
                    $fromDate = UtilityHelper::convertMDYToYMD($request->valid_from);
                    $toDate = UtilityHelper::convertMDYToYMD($request->valid_till);
                } else {
                    $fromDate = UtilityHelper::getCurrentDate();
                    $toDate = UtilityHelper::getYears($fromDate, 2);
                }


                Instructor::saveData(array('user_id' => $userDetails, 'role_id' => $request->role_id, 'approved_by' => $auth['id'], 'instructor_name' => $request->first_name . ' ' . $request->last_name, 'price' => $request->amount, 'valid_from' => $fromDate, 'valid_till' => $toDate));
            }

            $details = [
                'title' => 'Welcome Email',
                'username' => $request->first_name . ' ' . $request->last_name,
                'subject' => 'Welcome Email',
                'user_id' => $oktaDetails['id'],
                'type' => 'reqister',
                'sub_type'=>'admin'
            ];
            // $mailstatus = MailHelper::sendMail($request->email, $details);
            $this->callEntityLog($userDetails, 'Add', $request->all());
            return response()->json(['response_msg' => trans('package_lang::messages.userSuccess'),  'data' => array()], $this->successStatus);
        } else {

            return response()->json(['response_msg' => trans('package_lang::messages.error_msg'),  'data' => array()], $this->errorStatus);
        }
    }

    function callEditUserDetails($id)
    {
        $getUserDetails = User::getDetailsById($id);
        return response()->json(['response_msg' => trans('package_lang::messages.success_res'),  'data' => array($getUserDetails)], $this->successStatus);
    }

    function callUpdateUserDetails(UserRequest $request, $id)
    {
        $auth = auth()->user();
        $checkEmailExistOrNot = User::checkEmailExistOrNot($request->email, $id);
        if ($checkEmailExistOrNot != 0) {
            return response()->json(['response_msg' => trans('package_lang::messages.emailExist'),  'data' => array()], $this->validationStatus);
        }
        $getUserDetails = User::getDetailsById($id);
        $oktaArray = array(
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        );

        $oktaDetails = OktaHelper::updateOktaDetails($oktaArray, $getUserDetails->okta_id);
    
       
            
        $final = array(
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,

        );

        $userDetails = User::updateData($final, array('id' => $request->id));
    
        if ($request->role_id != "") {
            $getRoleAddOrNot = UserRole::getRoleAddorNot($request->id);
            $roleUserArray = array(
                'role_id' => $request->role_id,

            );
            if ($getRoleAddOrNot == 0) {
                $roleUserArray['user_id'] = $request->id;
                UserRole::saveData($roleUserArray);
            } else {
                UserRole::updateData($roleUserArray, array('user_id' => $request->id));
            }
        }
        $getRoleDetails = Role::getDetailsById($request->role_id);
        
        if (trim($getRoleDetails->flag) == 'Student' && $getRoleDetails->is_system_role == 1) {
            $getUserPaymentOrNot = UserCustomPayment::getUserPaymentAddOrNot($request->id);

            if ($getUserPaymentOrNot != 0) {
                UserCustomPayment::updateData(array('entity_id' => $request->entity_id, 'entity_type' => 'PaymentPlan', 'price' => $request->amount, 'valid_from' => UtilityHelper::convertMDYToYMD($request->valid_from), 'valid_till' => UtilityHelper::convertMDYToYMD($request->valid_till), 'payment_url' => ''), array('user_id' => $request->id));
            } else {
                UserCustomPayment::saveData(array('user_id' => $request->id, 'entity_id' => $request->entity_id, 'entity_type' => 'PaymentPlan', 'price' => $request->amount, 'valid_from' => UtilityHelper::convertMDYToYMD($request->valid_from), 'valid_till' => UtilityHelper::convertMDYToYMD($request->valid_till), 'payment_url' => ''));
            }
        } else {
            UserCustomPayment::SoftDelete(array(), array('user_id' => $request->id));
        }

        if (trim($getRoleDetails->flag) == 'Instructor' && $getRoleDetails->is_system_role == 1) {
            $getInstructor = Instructor::getInstructorExistOrNot($request->id);

            if ($request->valid_from != "") {
                $fromDate = UtilityHelper::convertMDYToYMD($request->valid_from);
                $toDate = UtilityHelper::convertMDYToYMD($request->valid_till);
            } else {
                $fromDate = UtilityHelper::getCurrentDate();
                $toDate = UtilityHelper::getYears($fromDate, 2);
            }


            if ($getInstructor != 0) {
                Instructor::updateData(array('instructor_name' => $request->first_name . ' ' . $request->last_name,  'valid_from' => $fromDate, 'valid_till' => $toDate), array('user_id' => $request->id));
            } else {
                Instructor::saveData(array('user_id' => $request->id, 'role_id' => $request->role_id, 'approved_by' => $auth['id'], 'instructor_name' => $request->first_name . ' ' . $request->last_name,  'valid_from' => $fromDate, 'valid_till' => $toDate));
            }
        } else {
            Instructor::SoftDelete(array(), array('user_id' => $request->id));
        }

        $this->callEntityLog($request->id, 'Edit', $request->all());
        return response()->json(['response_msg' => trans('package_lang::messages.userUpdate'),  'data' => array()], $this->successStatus);
        
    }
    function callPaymentPlan()
    {
        $query = PaymentPlan::getPaymentPlanList();
        return response()->json(['response_msg' => trans('package_lang::messages.success_res'),  'data' => $query], $this->successStatus);
    }

    function callUserDetails($id)
    {
        $getUserDetails = User::getDetailsById($id);
        return response()->json(['response_msg' => trans('package_lang::messages.success_res'),  'data' => $getUserDetails], $this->successStatus);
    }

    function callDeleteUser(Request $request)
    {

        $save = User::SoftDelete(array(), array('id' => $request->id));
        if ($save) {
            $this->callEntityLog($request->id, 'Delete', array());
            return response()->json(['response_msg' => trans('package_lang::messages.userDelete'),  'data' => array(array('id' => $save))], $this->successStatus);
        } else {
            return response()->json(['response_msg' => trans('package_lang::messages.error_msg'),  'data' => array()], $this->errorStatus);
        }
    }

    function callBulkUserDelete(Request $request)
    {
        $bulkId = $request->id;
        if (count($bulkId) > 0) {
            $save = 0;
            foreach ($bulkId as $val) {
                $save = User::SoftDelete(array(), array('id' => $val));
                $this->callEntityLog($val, 'BulkDelete', array());
            }
            if ($save) {
                return response()->json(['response_msg' => trans('package_lang::messages.userDelete'),  'data' => array(array('id' => $save))], $this->successStatus);
            } else {
                return response()->json(['response_msg' => trans('package_lang::messages.error_msg'),  'data' => array()], $this->errorStatus);
            }
        } else {
            return response()->json(['response_msg' => trans('package_lang::messages.error_msg'),  'data' => array()], $this->errorStatus);
        }
    }
    function callImportCSV(Request $request)
    {
        if ($request->file('file') != '') {
            $files = $path = $request->file('file');
            $name = time() . 'user.' . $files->getClientOriginalExtension();
            $destination = public_path('allupload');
            $files->move($destination, $name);
            $img = $name;
        }
        $path = public_path() . '/allupload/' . $img;
        $import_data = array_map('str_getcsv', file($path));
        $i = 0;
        $textsInsertArray = array();
        $deletedUserList  = array();
        foreach ($import_data as $user) {

            if ($i != 0) {
                $checkUserExist = User::checkEmailExistOrNot($user[2]);

                if ($checkUserExist  == 0) {
                    $temp = array(
                        'first_name' => $user[0],
                        'last_name' => $user[1],
                        'email' => $user[2],
                    );
                    $oktaDetails = OktaHelper::userRegistration($temp);
                    if (isset($oktaDetails['id'])) {
                        $final = array(
                            'okta_id' => $oktaDetails['id'],
                            'first_name' => $user[0],
                            'last_name' => $user[1],
                            'email' => $user[2],
                            'mobile_number' => ''
                        );
                        $userDetails = User::saveData($final);
                        $this->callEntityLog($userDetails, 'Import', $final);
                    }
                } else {
                    $checkUserDelete = User::checkDeleteUserDetail($user[2]);

                    if (isset($checkUserDelete->id)) {

                        $textsInsertArray['id'] = "$checkUserDelete->id";
                        $textsInsertArray['first_name'] = $checkUserDelete->first_name;
                        $textsInsertArray['last_name'] = $checkUserDelete->last_name;
                        $textsInsertArray['email'] = $checkUserDelete->email;
                        $deletedUserList[] = $textsInsertArray;
                    }
                }
            }
            $i++;
        }

        return response()->json(['response_msg' => trans('package_lang::messages.import_success'),  'data' => $deletedUserList], $this->successStatus);
    }

    public function callReactiveUser(Request $request)
    {
        $save = User::updateData(array('deleted_at' => Null), array('id' => $request->id));
        if ($save) {
            $this->callEntityLog($request->id, 'Reactive', array());
            return response()->json(['response_msg' => trans('package_lang::messages.user_reactive'),  'data' => array()], $this->successStatus);
        } else {
            return response()->json(['response_msg' => trans('package_lang::messages.error_msg'),  'data' => array()], $this->errorStatus);
        }
    }

    public function callEntityLog($entity_id, $action_taken, $request_params)
    {
        $final_array = array(
            'entity_id' => $entity_id,
            'entity_type' => 'users',
            'action_taken' => $action_taken,
            'request_params' => json_encode($request_params)
        );
        EntityOperationLogs::saveData($final_array);
    }
}
