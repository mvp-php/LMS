<?php

namespace CyberEd\App\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use CyberEd\Core\Models\Assignments;
use CyberEd\App\Requests\CategoryRequest;
use CyberEd\App\Controllers\Common\BaseController;


class AssignmentManagementController extends  BaseController
{

    public function callAssignmentList(Request $request)
    {
        
        $assignmentsData = Assignments::getAllData($request->search);
        if (count($assignmentsData) > 0) {
            $statusMsg = trans('package_lang::messages.success_res');
        } else {
            $statusMsg = trans('package_lang::messages.no_record_available');
        }
        return response()->json(['response_msg' => $statusMsg,  'data' => $assignmentsData], $this->successStatus);
    }
   
  
}
