<?php

namespace CyberEd\App\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use CyberEd\Core\Models\Assignments;
use CyberEd\Core\Models\EntityOperationLogs;
use CyberEd\App\Requests\CategoryRequest;
use CyberEd\App\Controllers\Common\BaseController;


class AssignmentManagementController extends  BaseController
{

    public function callAssignmentList(Request $request)
    {

        $assignmentsData = Assignments::getAllData($request->search);
        $statusMsg = trans('package_lang::messages.no_record_available');
        if (count($assignmentsData) > 0) {
            $statusMsg = trans('package_lang::messages.success_res');
        }

        return response()->json(['response_msg' => $statusMsg,  'data' => $assignmentsData], $this->successStatus);
    }
    public function callSaveAssignment(CategoryRequest $request)
    {
        $getDuplicateExist = Assignments::checkExistOrNot($request->title, '');
        if ($getDuplicateExist != 0) {
            return response()->json(['response_msg' => trans('package_lang::messages.commonExist', ["attribute" => "Assignments"]),  'data' => array()], $this->validationStatus);
        }

        $dataArray = array(
            'title' => $request->title,
            'description' => $request->description,
            'created_at' => date("Y-m-d H:i:s"),
            'image_name' => $request->image_name,

        );

        $save = Assignments::create($dataArray);

        $lastId = $save->id;
        if ($save) {
            $this->callEntityLog($lastId, 'Add', $request->all());
            return response()->json(['response_msg' => trans('package_lang::messages.commonSuccess', ["attribute" => "Assignments"]), 'status' => 1, 'data' => array($dataArray)], $this->successStatus);
        }
        return response()->json(['response_msg' => trans('package_lang::messages.error_msg'), 'data' => array()], $this->errorStatus);
    }
    function callDeleteAssignment(Request $request)
    {

        $save = Assignments::SoftDelete(array(), array('id' => $request->id));
        if ($save) {
            $this->callEntityLog($request->id, 'Delete', array());
            return response()->json(['response_msg' => trans('package_lang::messages.commonDelete', ["attribute" => "Assignments"]),  'data' => array(array('id' => $save))], $this->successStatus);
        }
        return response()->json(['response_msg' => trans('package_lang::messages.error_msg'),  'data' => array()], $this->errorStatus);
    }

    function callBulkAssignmentDelete(Request $request)
    {
        $bulkId = $request->id;
        if (count($bulkId) > 0) {
            $save = 0;
            foreach ($bulkId as $val) {
                $save = Assignments::SoftDelete(array(), array('id' => $val));
                $this->callEntityLog($val, 'BulkDelete', array());
            }
            if ($save) {
                return response()->json(['response_msg' => trans('package_lang::messages.commonDelete', ["attribute" => "Assignments"]),  'data' => array(array('id' => $save))], $this->successStatus);
            }
            return response()->json(['response_msg' => trans('package_lang::messages.error_msg'),  'data' => array()], $this->errorStatus);
        }
        return response()->json(['response_msg' => trans('package_lang::messages.error_msg'),  'data' => array()], $this->errorStatus);
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
