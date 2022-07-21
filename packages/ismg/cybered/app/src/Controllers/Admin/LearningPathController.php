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

    public function callLearningPathList(Request $request){
        $learningpathData = LearningPaths::getAllData();
        if (count($learningpathData) > 0) {
            $statusMsg = trans('package_lang::messages.success_res');
        } else {
            $statusMsg = trans('package_lang::messages.no_record_available');
        }
        return response()->json(['response_msg' => $statusMsg,  'data' => $learningpathData], $this->successStatus);
    }
    
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

    public function callEditLearningPathDetail($id){
        $learningPathdata = $this->commonDetails($id);
        return response()->json(['response_msg' => trans('package_lang::messages.success_res'), 'data' => $learningPathdata]);
    }
    function commonDetails($id)
    {
        $learningPathData = LearningPaths::getDataById($id);
        return $learningPathData;
    }
    public function callUpdateLearningPath(LearningPathRequest $request){
        $getDuplicateExist = LearningPaths::checkExistOrNot($request->title, $request->id);
        if ($getDuplicateExist != 0) {
            return response()->json(['response_msg' => trans('package_lang::messages.learningpathExist'),  'data' => array()], $this->validationStatus);
        }

        $dataArray = array(
            'title' => $request->title,
            'description' => $request->description,
            'requirement' => $request->requirement,
            'image_name' => $request->image_name,
            'intro_video' => $request->intro_video,
            'price' => $request->price,
        );
        $update = LearningPaths::updateData($dataArray, array('id' => $request->id));
        if($update){
            $this->callEntityLog($request->id, 'Edit', $request->all(),"");
            if($request->category_id !=''){

                $checkExistentry = EntityCategories::checkExistOrNot($request->category_id,$request->id);
                if($checkExistentry){
                    $udpateEntityCategoriesdataArray = array(
                        'category_id' => $request->category_id,
                    );
                    $updateLearningpathEntitycategory = EntityCategories::updateData($udpateEntityCategoriesdataArray,$checkExistentry->id);
                }else{
                    $entityCategoriesdataArray = array(
                        'category_id' => $request->category_id,
                        'entity_id' => $request->id,
                        'entity_type' => 'LearningPath',
                        'created_at' => date("Y-m-d H:i:s"),
                    );
                    $saveEntitycategory = EntityCategories::create($entityCategoriesdataArray);
                }
                
            }
            
            if($request->instructor_id !=''){

                $checkExistentry = EntityInstructor::checkExistOrNot($request->category_id,$request->id);
                if($checkExistentry){
                    $udpateEntityInstructordataArray = array(
                        'instructor_id' => $request->instructor_id,
                    );
                    $updateLearningpathEntityinstructor = EntityInstructor::updateData($udpateEntityInstructordataArray,$checkExistentry->id);
                }else{
                    $entityInstructordataArray = array(
                        'instructor_id' => $request->instructor_id,
                        'entity_id' => $request->id,
                        'entity_type' => 'LearningPath',
                        'created_at' => date("Y-m-d H:i:s"),
                    );
                    $saveInstructor = EntityInstructor::create($entityInstructordataArray);
                }
                
            }
            return response()->json(['response_msg' => trans('package_lang::messages.commonSuccessUpdate', ['attribute' => 'Learning Path']),  'data' => array($update)], $this->successStatus);
        }
        return response()->json(['response_msg' => trans('package_lang::messages.error_msg'), 'data' => array()], $this->errorStatus);
    }

    public function callDeleteLearningPath(Request $request)
    {
        $id = $request->id;
        $delete = LearningPaths::SoftDelete(array(), array('id' => $id));

        if ($delete) {
            
            $this->callEntityLog($id, 'Delete',array(),'');
            return response()->json(['response_msg' => trans('package_lang::messages.commonDelete', ['attribute' => 'Learning Path']), 'data' => array()], $this->successStatus);
        } else {
            return response()->json(['response_msg' => trans('package_lang::messages.error_msg'), 'data' => array()], $this->errorStatus);
        }
    }

    public function callEntityLog($entity_id, $action_taken, $request_params)
    {

        $final_array = array(
            'entity_id' => $entity_id,
            'entity_type' =>  "Learning Path",
            'action_taken' => $action_taken,
            'request_params' => json_encode($request_params)
        );
        EntityOperationLogs::saveData($final_array);
    }
    
}
