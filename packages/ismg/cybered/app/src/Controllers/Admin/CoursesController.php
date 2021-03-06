<?php

namespace CyberEd\App\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use CyberEd\Core\Helpers\UtilityHelper;
use CyberEd\App\Requests\CategoryRequest;
use CyberEd\App\Requests\CoursesRequest;
use CyberEd\App\Requests\CoursesDetailRequest;
use CyberEd\App\Requests\CoursesPreviewRequest;
use CyberEd\App\Controllers\Common\BaseController;
use CyberEd\Core\Models\CourseModules;
use CyberEd\Core\Models\Courses;
use CyberEd\Core\Models\EntityCategories;
use CyberEd\Core\Models\EntityInstructor;
use CyberEd\Core\Models\EntityOperationLogs;
use CyberEd\Core\Models\Instructor;
use CyberEd\Core\Models\ModuleChapters;

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
    public function callAddCourses(CoursesRequest $request){
        $dataArray = array(
            'title' => $request->title,
            'description' => $request->description,
            'requirement' => $request->requirement,
            'image_name' => $request->image_name,
            'intro_video' => $request->intro_video,
            'price' => $request->price,
            'is_published' => 0,
            'created_at' => date("Y-m-d H:i:s"),
        );

        $save = Courses::create($dataArray);
        if ($save) {
            $lastId = $save->id;
            $entityCategoriesdataArray = array(
                'category_id' => $request->category_id,
                'entity_id' => $lastId,
                'entity_type' => 'Course',
                'created_at' => date("Y-m-d H:i:s"),
            );
            $saveEntitycategory = EntityCategories::create($entityCategoriesdataArray);
            
            $entityInstructordataArray = array(
                'instructor_id' => $request->instructor_id,
                'entity_id' => $lastId,
                'entity_type' => 'Course',
                'created_at' => date("Y-m-d H:i:s"),
            );
            $saveInstructor = EntityInstructor::create($entityInstructordataArray);
            $dataArray['id'] = $lastId;
            $this->callEntityLog($lastId, 'Add', $request->all());
            return response()->json(['response_msg' => trans('package_lang::messages.commonSuccess', ["attribute" => "Course"]), 'status' => 1, 'data' => array($dataArray)], $this->successStatus);
        } else {
            return response()->json(['response_msg' => trans('package_lang::messages.error_msg'), 'data' => array()], $this->errorStatus);
        }
    }
    public function callAddCourseDetails(CoursesDetailRequest $request){
        $course_detail_json = json_decode($request->course_detail_json);
        
        if(count($course_detail_json) > 0){
            foreach($course_detail_json as $key){

                $courseModuledata = array(
                    'course_id' => $request->course_id,
                    'title' => $key->title,
                    'created_at' => date("Y-m-d H:i:s"),
                );

                $save = CourseModules::create($courseModuledata);
                $moduleId = $save->id;

                foreach($key->chapter_data as $val){
                    $content_metadata = array();

                    foreach($val->course_content as $keycontent){
                        $content_metadata[] = $keycontent->content_data;
                    }

                    $courseChapterdata = array(
                        'module_id' => $moduleId,
                        'title' => $val->title,
                        'description' => $val->description,
                        'content_metadata' => json_encode($content_metadata),
                        'content_type' => $val->content_type,
                        'created_at' => date("Y-m-d H:i:s")
                    );
                    $save = ModuleChapters::create($courseChapterdata);
                }
            }
            return response()->json(['response_msg' => trans('package_lang::messages.commonSuccess', ["attribute" => "Course Detail"]), 'status' => 1, 'data' => array(array('course_id' => $request->course_id))], $this->successStatus);
        }

        return response()->json(['response_msg' => trans('package_lang::messages.error_msg'), 'data' => array()], $this->errorStatus);
        
    }

    public function coursePreview(CoursesPreviewRequest $request){
        $getCourseDetail = Courses::getDataById($request->course_id);
        if ($getCourseDetail) {
            $statusMsg = trans('package_lang::messages.success_res');
        } else {
            $statusMsg = trans('package_lang::messages.no_record_available');
        }
        return response()->json(['response_msg' => $statusMsg,  'data' => array($getCourseDetail)], $this->successStatus);
    }

    public function callEntityLog($entity_id, $action_taken, $request_params,$parent_category_id="")
    {
        $type="Category";
        if($parent_category_id  !=""){
            $type="SubCategory";
        }
        $final_array = array(
            'entity_id' => $entity_id,
            'entity_type' =>  $type,
            'action_taken' => $action_taken,
            'request_params' => json_encode($request_params)
        );
        EntityOperationLogs::saveData($final_array);
    }
    
}
