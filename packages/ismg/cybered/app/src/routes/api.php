<?php

header('Access-Control-Allow-Methods', 'GET,PUT,POST,DELETE');
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix' => 'v1',], function () {
    Route::namespace('CyberEd\App\Controllers')->group(function ($user_route) {
        $user_route->post('sign-up', 'Common\AuthController@signUp'); 
        $user_route->post('call-login','Common\AuthController@callLogin');
      
        $user_route->post('forgot-password','Common\AuthController@callForgotPassword');
        $user_route->post('reset-password/{id}','Common\AuthController@callResetPassword');
        $user_route->post('dropzone-image-upload','Common\AuthController@imageUpload');
        $user_route->get('email-user','Common\AuthController@emails');
        Route::middleware(['auth:sanctum', 'verified'])->group(function ($route) {
            $route->post('call-logout','Common\AuthController@callLogout');
            $route->post('call-update-password','Common\ProfileController@callUpdatePassword');
            $route->get('call-user-profile','Common\ProfileController@callProfileDetail');
            $route->post('profile-update','Common\ProfileController@callProfileUpdate');
            $route->post('image-upload','Common\ProfileController@callImageUpload');

            
            $route->namespace('Admin')->group(function ($admin_route) {
                
                $admin_route->get('entities-list', 'RoleController@callEntitiesList');
                $admin_route->get('role-list', 'RoleController@callRoleList');
                $admin_route->get('all-role-list', 'RoleController@callUserRoleList');
                $admin_route->post('call-role', 'RoleController@callSaveRole');
                $admin_route->get('role-detail/{id}', 'RoleController@callEditRole');
                $admin_route->post('role-update/{id}', 'RoleController@callUpdateRole');
                $admin_route->get('view-role-detail/{id}', 'RoleController@callRoleDetail');
                $admin_route->post('role-delete', 'RoleController@callDeleteRole');
                $admin_route->post('bulk-role-delete', 'RoleController@callBulkRoleDelete');
                $admin_route->post('set-default-role', 'RoleController@callSetDefaultRole');
                
                $admin_route->get('user-list','UserController@callUserList');
                $admin_route->post('user-save','UserController@callSaveUser');
                $admin_route->get('edit-user-detail/{id}','UserController@callEditUserDetails');
                $admin_route->get('payment-plan','UserController@callPaymentPlan');
                $admin_route->post('user-update/{id}','UserController@callUpdateUserDetails');
                $admin_route->get('view-user-detail/{id}','UserController@callUserDetails');
                $admin_route->post('user-delete','UserController@callDeleteUser');
                $admin_route->post('user-bulk-delete','UserController@callBulkUserDelete');
                $admin_route->post('import-csv','UserController@callImportCSV');
                $admin_route->post('reactive-user','UserController@callReactiveUser');
                
                $admin_route->get('category-list', 'CategoryController@callCategoryList');
                $admin_route->post('category-save', 'CategoryController@callSaveCategory');
                $admin_route->get('edit-category-detail/{id}', 'CategoryController@callEditCategoryDetail');
                $admin_route->get('get-category/{id}', 'CategoryController@callCategoryDetail');
                $admin_route->post('update-category', 'CategoryController@callUpdateCategory');
                $admin_route->post('delete-category', 'CategoryController@callDeleteCategory');


                $admin_route->post('add-sub-category', 'CategoryController@callAddSubCategory');
                $admin_route->get('category', 'CategoryController@getAllCategorys');
                $admin_route->get('get-all-sub-category','CategoryController@callAllSubCategorys');
                $admin_route->get('get-sub-category/{id}','CategoryController@callEditSubCategoryDetail');
                $admin_route->post('update-sub-category','CategoryController@callUpdateSubCategory');
                $admin_route->post('category-bulk-delete','CategoryController@bulkCategoryDelete');
                $admin_route->get('call-allcategory-list', 'CategoryController@callAllCategoryList');
                
                $admin_route->get('call-subcategory-list', 'CategoryController@callSubCategoryList');
                
                $admin_route->get('call-courses-list', 'CoursesController@callCoursesList');
                $admin_route->get('call-instructors-list', 'CoursesController@callInstructorsList');
                $admin_route->post('add-courses', 'CoursesController@callAddCourses');
                $admin_route->post('add-course-details', 'CoursesController@callAddCourseDetails');
                $admin_route->post('course-preview', 'CoursesController@coursePreview');
                
               
                $admin_route->get('assignment-list', 'AssignmentManagementController@callAssignmentList');
                $admin_route->post('assignment-save', 'AssignmentManagementController@callSaveAssignment');
                $admin_route->post('assignment-delete','AssignmentManagementController@callDeleteAssignment');
                $admin_route->post('assignment-bulk-delete','AssignmentManagementController@callBulkAssignmentDelete');   
            });
            
        });
        
        
    });

});
