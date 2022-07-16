<?php

namespace CyberEd\App\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use CyberEd\Core\Models\Category;
use CyberEd\Core\Models\SubCategory;
use CyberEd\Core\Helpers\UtilityHelper;
use CyberEd\App\Requests\CategoryRequest;
use CyberEd\App\Controllers\Common\BaseController;

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
        $parent_category_id = isset($request->parent_category_id) ? $request->parent_category_id : NULL;
        $dataArray = array(
            'title' => $request->title,
            'description' => $request->description,
            'created_at' => date("Y-m-d H:i:s"),
            'image_name' => $request->image_name,
            'parent_category_id' => $parent_category_id
        );

        $save = Category::create($dataArray);

        $lastId = $save->id;
        if ($save) {
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
        return response()->json(['response_msg' => trans('package_lang::messages.commonSuccessUpdate', ['attribute' => 'Category']),  'data' => array($update)], $this->successStatus);
    }

    public function callDeleteCategory(Request $request)
    {
        $id = $request->id;
        $delete = Category::SoftDelete(array(), array('id' => $id));

        if ($delete) {
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
                $save = Category::SoftDelete(array(), array('id' => $val));
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
        $query = Category::getAllSubcategoryList($parentCategoryId, $request->search);
        if (count($query) > 0) {
            $statusMsg = trans('package_lang::messages.success_res');
        } else {
            $statusMsg = trans('package_lang::messages.no_record_available');
        }
        return response()->json(['response_msg' => $statusMsg,  'data' => $query], $this->successStatus);
    }
}
