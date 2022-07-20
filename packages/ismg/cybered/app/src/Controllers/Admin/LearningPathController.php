<?php

namespace CyberEd\App\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use CyberEd\Core\Helpers\UtilityHelper;
use CyberEd\App\Requests\LearningPathRequest;
use CyberEd\App\Controllers\Common\BaseController;
use CyberEd\Core\Models\CourseModules;
use CyberEd\Core\Models\Courses;
use CyberEd\Core\Models\EntityCategories;
use CyberEd\Core\Models\EntityInstructor;
use CyberEd\Core\Models\EntityOperationLogs;
use CyberEd\Core\Models\Instructor;
use CyberEd\Core\Models\LearningPaths;
use CyberEd\Core\Models\ModuleChapters;

class LearningPathController extends  BaseController
{

    
    public function callAddLearningPath(LearningPathRequest $request){
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

        $save = LearningPaths::create($dataArray);
        if ($save) {
            $lastId = $save->id;
            if($request->category_id !=''){
                $entityCategoriesdataArray = array(
                    'category_id' => $request->category_id,
                    'entity_id' => $lastId,
                    'entity_type' => 'LearningPath',
                    'created_at' => date("Y-m-d H:i:s"),
                );
                $saveEntitycategory = EntityCategories::create($entityCategoriesdataArray);
            }
            
            if($request->instructor_id !=''){
                $entityInstructordataArray = array(
                    'instructor_id' => $request->instructor_id,
                    'entity_id' => $lastId,
                    'entity_type' => 'LearningPath',
                    'created_at' => date("Y-m-d H:i:s"),
                );
                $saveInstructor = EntityInstructor::create($entityInstructordataArray);
            }
            
            
            $dataArray['id'] = $lastId;
            $this->callEntityLog($lastId, 'Add', $request->all());
            return response()->json(['response_msg' => trans('package_lang::messages.commonSuccess', ["attribute" => "Learning Path"]), 'status' => 1, 'data' => array($dataArray)], $this->successStatus);
        } else {
            return response()->json(['response_msg' => trans('package_lang::messages.error_msg'), 'data' => array()], $this->errorStatus);
        }
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
