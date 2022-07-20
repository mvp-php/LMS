<?php

namespace CyberEd\App\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use CyberEd\Core\Models\Permission;
use CyberEd\Core\Models\Role;
use CyberEd\Core\Helpers\UtilityHelper;
use CyberEd\Core\Models\UserRole;
use CyberEd\Core\Models\PermissionRole;
use CyberEd\App\Requests\RoleRequest;
use CyberEd\App\Requests\SetDefaultRoleRequest;
use CyberEd\Core\Models\EntityOperationLogs;
use CyberEd\App\Controllers\Common\BaseController;

class RoleController extends  BaseController
{
    public function callEntitiesList()
    {
        $permissions = Permission::GetPermissionList();
        $finalPermission = array();
        $tempPermissionArray = array();
        foreach ($permissions as $permission) {
            $tempPermissionArray['id'] = "$permission->id";
            $tempPermissionArray['title'] = $permission->title;
            $tempPermissionArray['module_name'] = $permission->entity_type;

            $finalPermission[$permission->module_name][] = $tempPermissionArray;
        }

        return response()->json(['response_msg' => trans('package_lang::messages.success_res'),  'data' => array($finalPermission)], $this->successStatus);
    }

    public function callRoleList(Request $request)
    {

        $roleDetails = Role::getRoleList($request->search);
        foreach ($roleDetails as $role) {
            $getUserRole = UserRole::getTotalUserCountByRoleId($role->mid);
            $role->no_of_user = (int) $getUserRole;
        }

        if (count($roleDetails) > 0) {
            $statusMsg = trans('package_lang::messages.success_res');
        } else {
            $statusMsg = trans('package_lang::messages.no_record_available');
        }
        return response()->json(['response_msg' => $statusMsg,  'data' => $roleDetails, 'st' => true], $this->successStatus);
    }

    public function callSaveRole(RoleRequest $request)
    {
        $checkRoleExistOrNot = Role::checkRoleExistOrNot($request->title);

        if ($checkRoleExistOrNot != 0) {
            return response()->json(['response_msg' => trans('package_lang::messages.commonExist', ["attribute" => "Role"]),  'data' => array()], $this->validationStatus);
        }
        $roleArray = array(
            'title' => $request->title,
            'description' => $request->description,
            'flag' => NULL,
            'is_system_role' => 0
        );
        $save = Role::saveData($roleArray);
        if ($save) {
            $permission = $request->permission;
            if (count($permission) > 0) {
                foreach ($permission as $val) {
                    PermissionRole::saveData(array('role_id' => $save, 'permission_id' => $val));
                }
            }
            $this->callEntityLog($save, 'Add', $request->all());
            return response()->json(['response_msg' => trans('package_lang::messages.roleSuccess'),  'data' => array(array('id' => $save))], $this->successStatus);
        } else {
            return response()->json(['response_msg' => trans('package_lang::messages.error_msg'),  'data' => array()], $this->errorStatus);
        }
    }

    public function callEditRole($id)
    {

        $getRoleDetails = Role::getDetailsById($id);

        if (isset($getRoleDetails->id)) {
            $getPermissionRoles = PermissionRole::getPermission($getRoleDetails->id);
            $checkPermission = array();
            foreach ($getPermissionRoles as $val) {
                $checkPermission[] = "$val->permission_id";
            }
            $getRoleDetails->permission = $checkPermission;
            return response()->json(['response_msg' => trans('package_lang::messages.success_res'),  'data' => $getRoleDetails], $this->successStatus);
        } else {
            return response()->json(['response_msg' => trans('package_lang::messages.success_res'),  'data' => $getRoleDetails], $this->successStatus);
        }
    }

    public function callUpdateRole(RoleRequest $request, $id)
    {
        $checkRoleExistOrNot = Role::checkRoleExistOrNot($request->title, $id);
        if ($checkRoleExistOrNot != 0) {
            return response()->json(['response_msg' => trans('package_lang::messages.commonExist', ["attribute" => "Role"]),  'data' => array()], $this->validationStatus);
        }
        $roleArray = array(
            'title' => $request->input('title'),
            'description' => $request->input('description'),

        );
        $save = Role::updateData($roleArray, array('id' => $id));
        $permission = $request->permission;
        if (count($permission) > 0) {
            PermissionRole::SoftDelete(array(), array('role_id' => $id));
            foreach ($permission as $val) {
                PermissionRole::saveData(array('role_id' => $id, 'permission_id' => $val));
            }
        }
        $this->callEntityLog($id, 'Edit', $request->all());
        return response()->json(['response_msg' => trans('package_lang::messages.roleUpdate'),  'data' => array()], $this->successStatus);
    }

    public function callUserRoleList()
    {
        $roleList = Role::getAllRoleList();
        return response()->json(['response_msg' => trans('package_lang::messages.success_res'),  'data' => $roleList], $this->successStatus);
    }


    public function callRoleDetail($id)
    {
        $getRoleDetails = Role::getDetailsById($id);
        if (isset($getRoleDetails->id)) {
            $getUserRole = UserRole::getTotalUserCountByRoleId($id);
            $getRoleDetails->no_of_user = (int)$getUserRole;
            return response()->json(['response_msg' => trans('package_lang::messages.success_res'),  'data' => $getRoleDetails], $this->successStatus);
        } else {
            return response()->json(['response_msg' => trans('package_lang::messages.error_msg'),  'data' => array()], $this->errorStatus);
        }
    }

    public function callDeleteRole(Request $request)
    {
        $save = Role::SoftDelete(array(), array('id' => $request->id));
        if ($save) {
            $this->callEntityLog($request->id, 'Delete', array());
            return response()->json(['response_msg' => trans('package_lang::messages.roleDelete'),  'data' => array(array('id' => $save))], $this->successStatus);
        } else {
            return response()->json(['response_msg' => trans('package_lang::messages.error_msg'),  'data' => array()], $this->errorStatus);
        }
    }

    public function callBulkRoleDelete(Request $request)
    {
        $bulkId = $request->id;
        if (count($bulkId) > 0) {
            $save = 0;
            foreach ($bulkId as $val) {
                $getRoleDetails = Role::getDetailsById($val);
                if ($getRoleDetails->is_system_role != 1) {
                    $save = Role::SoftDelete(array(), array('id' => $val));
                    $this->callEntityLog($val, 'BulkDelete', array());
                }
            }
            if ($save) {
                return response()->json(['response_msg' => trans('package_lang::messages.roleDelete'),  'data' => array(array('id' => $save))], $this->successStatus);
            } else {
                return response()->json(['response_msg' => trans('package_lang::messages.error_msg'),  'data' => array()], $this->errorStatus);
            }
        } else {
            return response()->json(['response_msg' => trans('package_lang::messages.error_msg'),  'data' => array()], $this->errorStatus);
        }
    }
    public function callEntityLog($entity_id, $action_taken, $request_params)
    {

        $final_array = array(
            'entity_id' => $entity_id,
            'entity_type' => 'roles',
            'action_taken' => $action_taken,
            'request_params' => json_encode($request_params)
        );
        EntityOperationLogs::saveData($final_array);
    }

    public function callSetDefaultRole(Request $request)
    {
        $roleDetails = Role::getSystemRole();
        foreach ($roleDetails as $val) {
            Role::updateData(array('flag' => NULL, 'is_system_role' => 0), array('id' => $val->id));
        }
        Role::updateData(array('flag' => "Admin", 'is_system_role' => 1), array('id' => $request->admin));
        $this->existingRole($val->id, $request->admin);
        Role::updateData(array('flag' => "Student", 'is_system_role' => 1), array('id' => $request->student));
        $this->existingRole($val->id, $request->student);
        Role::updateData(array('flag' => "Instructor", 'is_system_role' => 1), array('id' => $request->instructor));
        $this->existingRole($val->id, $request->instructor);

        return response()->json(['response_msg' => trans('package_lang::messages.success_res'),  'data' => array()], $this->successStatus);
      //  $this->callEntityLog("", 'Set Default Role', $request->all());
    }

    function existingRole($existing_role_id, $new_role_id)
    {
        $getUserRoleList = UserRole::getExistingRole($existing_role_id);
        foreach ($getUserRoleList as $val) {
            UserRole::updateData(array('role_id' => $new_role_id), array('id' => $val->id));
        }
    }
}
