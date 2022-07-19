<?php

namespace CyberEd\App\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use CyberEd\Core\Helpers\UtilityHelper;
use CyberEd\App\Requests\CategoryRequest;
use CyberEd\App\Requests\SubCategoryRequest;
use CyberEd\App\Controllers\Common\BaseController;
use CyberEd\Core\Models\Courses;
use CyberEd\Core\Models\EntityOperationLogs;
use CyberEd\Core\Models\Instructor;

class CoursesController extends  BaseController
{

    public function callCoursesList(Request $request)
    {
        
        $coursesData = Courses::getAllData();
        if (count($coursesData) > 0) {
            $statusMsg = trans('package_lang::messages.success_res');
        } else {
            $statusMsg = trans('package_lang::messages.no_record_available');
        }
        return response()->json(['response_msg' => $statusMsg,  'data' => $coursesData], $this->successStatus);
    }
    public function callInstructorsList(Request $request){
        $instructorsData = Instructor::getAllData();
        if (count($instructorsData) > 0) {
            $statusMsg = trans('package_lang::messages.success_res');
        } else {
            $statusMsg = trans('package_lang::messages.no_record_available');
        }
        return response()->json(['response_msg' => $statusMsg,  'data' => $instructorsData], $this->successStatus);
    }
    
}
