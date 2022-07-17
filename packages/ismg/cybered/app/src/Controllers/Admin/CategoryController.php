<?php

namespace CyberEd\App\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use CyberEd\Core\Models\Category;
use CyberEd\Core\Models\SubCategory;
use CyberEd\Core\Helpers\UtilityHelper;
use CyberEd\App\Requests\CategoryRequest;
use CyberEd\App\Requests\SubCategoryRequest;

use CyberEd\App\Controllers\Common\BaseController;
use CyberEd\Core\Models\EntityOperationLogs;

class CategoryController extends  BaseController
{

    public function callCategoryList(Request $request)
    {
        
        $categoryData = Category::getAllData($request->search);
        if (count($categoryData) > 0) {
            $statusMsg = trans('package_lang::messages.success_res');
        } else {
            $statusMsg = trans('package_lang::messages.no_record_available');
        }
        return response()->json(['response_msg' => $statusMsg,  'data' => $categoryData], $this->successStatus);
    }
    public function callSaveCategory(CategoryRequest $request)
    {
        $getDuplicateExist = Category::checkExistOrNot($request->title, '');
        if ($getDuplicateExist != 0) {
            return response()->json(['response_msg' => trans('package_lang::messages.categoryExist'),  'data' => array()], $this->validationStatus);
        }
       
        $dataArray = array(
            'title' => $request->title,
            'description' => $request->description,
            'created_at' => date("Y-m-d H:i:s"),
            'image_name' => $request->image_name,
        
        );

        $save = Category::create($dataArray);

        $lastId = $save->id;
        if ($save) {
            $this->callEntityLog($lastId, 'Add', $request->all());
            return response()->json(['response_msg' => trans('package_lang::messages.commonSuccess', ["attribute" => "Category"]), 'status' => 1, 'data' => array($dataArray)], $this->successStatus);
        } else {
            return response()->json(['response_msg' => trans('package_lang::messages.error_msg'), 'data' => array()], $this->errorStatus);
        }
    }
    public function callEditCategoryDetail($id)
    {
        $categoryData = $this->commonDetails($id);
        return response()->json(['response_msg' => trans('package_lang::messages.success_res'), 'data' => $categoryData]);
    }

    public function callUpdateCategory(CategoryRequest $request)
    {
        $getDuplicateExist = Category::checkExistOrNot($request->title, $request->id);
        if ($getDuplicateExist != 0) {
            return response()->json(['response_msg' => trans('package_lang::messages.categoryExist'),  'data' => array()], $this->validationStatus);
        }

        $dataArray = array(
            'title' => $request->title,
            'description' => $request->description,
            'image_name' => $request->dropzoneImage,
        );

        $update = Category::updateData($dataArray, array('id' => $request->id));
        $this->callEntityLog($request->id, 'Edit', $request->all(),"");
        return response()->json(['response_msg' => trans('package_lang::messages.commonSuccessUpdate', ['attribute' => 'Category']),  'data' => array($update)], $this->successStatus);
    }

    public function callDeleteCategory(Request $request)
    {
        $id = $request->id;
        $delete = Category::SoftDelete(array(), array('id' => $id));

        if ($delete) {
            $parent_category_id = isset($request->parent_category_id) ? $request->parent_category_id : NULL;
            $this->callEntityLog($id, 'Delete',array(),$parent_category_id);
            return response()->json(['response_msg' => trans('package_lang::messages.commonDelete', ['attribute' => 'Category']), 'data' => array()], $this->successStatus);
        } else {
            return response()->json(['response_msg' => trans('package_lang::messages.error_msg'), 'data' => array()], $this->errorStatus);
        }
    }

    function commonDetails($id)
    {
        $categoryData = Category::getDataById($id);
        return $categoryData;
    }

    public function callCategoryDetail($id)
    {
        $details = $this->commonDetails($id);
        if ($details) {
          
            return response()->json(['response_msg' => trans('package_lang::messages.success_res'),  'data' => $details]);
        } else {
            return response()->json(['response_msg' => trans('package_lang::messages.error_msg'),  'data' => array()]);
        }
    }

    public function callAllSubCategorys(Request $request)
    {
        $categoryData = SubCategory::getAllData();
        return response()->json(['response_msg' => trans('package_lang::messages.success_res'),  'data' => $categoryData], $this->successStatus);
    }
    public function getAllCategorys()
    {
        $categoryData = Category::getMailCategoryList();

        if ($categoryData) {
            return response()->json(['response_msg' => trans('package_lang::messages.success_res'), 'data' => $categoryData]);
        } else {
            return response()->json(['response_msg' => trans('package_lang::messages.error_msg'), 'data' => array()]);
        }
    }

    function bulkCategoryDelete(Request $request)
    {
        $bulkId = $request->id;
        if (count($bulkId) > 0) {
            $save = 0;
            foreach ($bulkId as $val) {
                $getCategoryDetails=$this->commonDetails($val);
                $save = Category::SoftDelete(array(), array('id' => $val));
                
              
                $parent_category_id ="";
                if($getCategoryDetails->parent_category_id  !=""){
                    $parent_category_id  =$getCategoryDetails->parent_category_id;
                }
                $this->callEntityLog($val, 'BulkDelete',array(),$parent_category_id);
            }
            if ($save) {
                return response()->json(['response_msg' => trans('package_lang::messages.commonDelete', ["attribute" => "Category"]),  'data' => array(array('id' => $save))], $this->successStatus);
            } else {
                return response()->json(['response_msg' => trans('package_lang::messages.error_msg'),  'data' => array()], $this->errorStatus);
            }
        } else {
            return response()->json(['response_msg' => trans('package_lang::messages.error_msg'),  'data' => array()], $this->errorStatus);
        }
    }

    function callAllCategoryList()
    {
        $query = Category::getMailCategoryList();
        return response()->json(['response_msg' => trans('package_lang::messages.success_res', ["attribute" => "Category"]),  'data' => $query], $this->successStatus);
    }

    function callSubCategoryList(Request $request)
    {
        $parentCategoryId = $request->parentCategoryId;
        $query = SubCategory::getAllSubcategoryList($parentCategoryId, $request->search);
        if (count($query) > 0) {
            $statusMsg = trans('package_lang::messages.success_res');
        } else {
            $statusMsg = trans('package_lang::messages.no_record_available');
        }
        return response()->json(['response_msg' => $statusMsg,  'data' => $query], $this->successStatus);
    }

    public function callAddSubCategory(SubCategoryRequest $request)
    {
        $getDuplicateExist = Category::checkExistOrNot($request->title, '');
        if ($getDuplicateExist != 0) {
            return response()->json(['response_msg' => trans('package_lang::messages.categoryExist'),  'data' => array()], $this->validationStatus);
        }
       
        $dataArray = array(
            'title' => $request->title,
            'description' => $request->description,
            'created_at' => date("Y-m-d H:i:s"),
            'image_name' => $request->image_name,
            'parent_category_id' =>$request->parent_category_id
        );

        $save = Category::create($dataArray);

        $lastId = $save->id;
        if ($save) {
            $this->callEntityLog($lastId, 'Add', $request->all(),$request->parent_category_id);
            return response()->json(['response_msg' => trans('package_lang::messages.commonSuccess', ["attribute" => "Sub  Category"]), 'status' => 1, 'data' => array($dataArray)], $this->successStatus);
        } else {
            return response()->json(['response_msg' => trans('package_lang::messages.error_msg'), 'data' => array()], $this->errorStatus);
        }
    }

    public  function  callEditSubCategoryDetail($id){
        $details = $this->commonDetails($id);
        if ($details) {
            return response()->json(['response_msg' => trans('package_lang::messages.success_res'),  'data' => $details]);
        } else {
            return response()->json(['response_msg' => trans('package_lang::messages.error_msg'),  'data' => array()]);
        }
    }

    public function callUpdateSubCategory(SubCategoryRequest $request){
        $getDuplicateExist = Category::checkExistOrNot($request->title, $request->id);
        if ($getDuplicateExist != 0) {
            return response()->json(['response_msg' => trans('package_lang::messages.categoryExist'),  'data' => array()], $this->validationStatus);
        }

       
        $dataArray = array(
            'title' => $request->title,
            'description' => $request->description,
            'image_name' => $request->dropzoneImage,
            'parent_category_id' => $request->parent_category_id
        );

        $update = Category::updateData($dataArray, array('id' => $request->id));
        $this->callEntityLog($request->id, 'Edit', $request->all(),$request->parent_category_id);
        return response()->json(['response_msg' => trans('package_lang::messages.commonSuccessUpdate', ['attribute' => 'Sub Category']),  'data' => array($update)], $this->successStatus);
    
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
