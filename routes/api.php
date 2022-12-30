<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\catagoryController;
use App\Http\Controllers\Api\documentController;
use App\Http\Controllers\api\permissionController;
use App\Http\Controllers\api\roleController;
use App\Http\Controllers\api\roleHasPermissionController;
use App\Http\Controllers\api\subCatagoryController;
use App\Http\Controllers\Api\subSubCatagoryController;
use App\Http\Controllers\api\userController;
use App\Http\Controllers\api\userHasPermissionController;
use App\Http\Controllers\api\userHasRolesController;
use App\Models\permission;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();

// });

Route::group(['middleware' => ['auth:sanctum']], function () {
// user api
Route::get('/users', [userController::class, 'index']);
Route::post('/users', [userController::class, 'store']);
Route::get('/users/{id}', [userController::class, 'show']);
Route::put('/users/{id}', [userController::class, 'edit']);
Route::post('/users/{id}', [userController::class, 'update']);
Route::delete('/users/{id}', [userController::class, 'destroy']);
Route::get('/all_user', [userController::class, 'allUser']);

//catagories
Route::get('/category', [catagoryController::class, 'index']);
Route::post('/category', [catagoryController::class, 'store']);
Route::get('/category/{id}', [catagoryController::class, 'show']);
Route::put('/category/{id}', [catagoryController::class, 'edit']);
Route::post('/category/{id}', [catagoryController::class, 'update']);
Route::delete('/category/{id}', [catagoryController::class, 'destroy']);
Route::get('/category_all', [catagoryController::class, 'allCategory']);
Route::get('/category_show/{id}', [catagoryController::class, 'showSubCatagory']);

//subCatagoris
Route::get('/sub_category', [subCatagoryController::class, 'index']);
Route::post('/sub_category', [subCatagoryController::class, 'store']);
Route::get('/sub_category/{id}', [subCatagoryController::class, 'show']);
Route::put('/sub_category/{id}', [subCatagoryController::class, 'edit']);
Route::post('/sub_category/{id}', [subCatagoryController::class, 'update']);
Route::delete('/sub_category/{id}', [subCatagoryController::class, 'destroy']);
Route::get('/sub_category_show/{id}', [subCatagoryController::class, 'showSubSubCatagory']);

//subSubCatagories
Route::get('/sub_sub_category', [subSubCatagoryController::class, 'index']);
Route::post('/sub_sub_category', [subSubCatagoryController::class, 'store']);
Route::get('/sub_sub_category/{id}', [subSubCatagoryController::class, 'show']);
Route::put('/sub_sub_category/{id}', [subSubCatagoryController::class, 'edit']);
Route::post('/sub_sub_category/{id}', [subSubCatagoryController::class, 'update']);
Route::delete('/sub_sub_category/{id}', [subSubCatagoryController::class, 'destroy']);

//document
Route::get('/document', [documentController::class, 'index']);
Route::post('/document', [documentController::class, 'store']);
Route::get('/document/{id}', [documentController::class, 'show']);
Route::put('/document/{id}', [documentController::class, 'edit']);
Route::post('/document/{id}', [documentController::class, 'update']);
Route::delete('/document/{id}', [documentController::class, 'destroy']);
Route::get('/document_show/{id}', [documentController::class, 'showDocument']);
Route::get('/category_document/{id}', [documentController::class, 'showCategoryDocument']);

Route::get ('download/{id}',[documentController::class,'download']);
Route::post ('document_publish/{id}',[documentController::class,'documentPublish']);

Route::get('adminunpublish_document_list',[documentController::class,'AdminUnpubishDocumentList']);



//show document
Route::get('/show_sub_category/{id}', [documentController::class, 'showSubCategory']);
Route::get('/show_sub_category_document/{id}', [documentController::class, 'showSubCategoryDocument']);
Route::get('/show_sub_sub_category/{id}', [documentController::class, 'showSubSubCategory']);
Route::get('/show_sub_sub_category_document/{id}', [documentController::class, 'showSubSubCategoryDocument']);

//permission
Route::get('/permission', [permissionController::class, 'index']);

//role
Route::get('/role', [roleController::class, 'index']);
Route::get('/role/{id}', [roleController::class, 'show']);
Route::post('/role', [roleController::class, 'store']);
Route::post('/role/{id}', [roleController::class, 'update']);

//userHasPermission
Route::post('/user_has_permission', [userHasPermissionController::class, 'store']);
Route::post('/user_has_permission/{id}', [userHasPermissionController::class, 'update']);

//roleHasPermission
Route::post('/role_has_permission', [roleHasPermissionController::class, 'store']);
Route::post('/role_has_permission/{id}', [roleHasPermissionController::class, 'update']);

//userHasRole
Route::post('/user_has_role', [userHasRolesController::class, 'store']);

});




Route::post ('admin_document_publish/{id}',[documentController::class,'AdminPublishDocument']);
//Authentaction
Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);

