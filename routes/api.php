<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\catagoryController;
use App\Http\Controllers\Api\documentController;
use App\Http\Controllers\api\subCatagoryController;
use App\Http\Controllers\Api\subSubCatagoryController;
use App\Http\Controllers\api\userController;

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

Route::group(['middleware'=>['auth:sanctum']],function(){
    // Route::get('/catagory',[catagoryController::class,'index']);
});



Route::get('/test', function () {
    return "hello";
})->middleware('auth:sanctum');


//Authentaction
Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);

// user api
Route::get('/users', [userController::class, 'index']);
// Route::post('/users',[userController::class,'store']);
Route::get('/users/{id}', [userController::class, 'show']);
Route::put('/users/{id}', [userController::class, 'edit']);
Route::post('/users/{id}', [userController::class, 'update']);
Route::delete('/users/{id}', [userController::class, 'destroy']);

//catagories
Route::get('/catagory', [catagoryController::class, 'index']);
Route::post('/catagory', [catagoryController::class, 'store']);
Route::get('/catagory/{id}', [catagoryController::class, 'show']);
Route::put('/catagory/{id}', [catagoryController::class, 'edit']);
Route::post('/catagory/{id}', [catagoryController::class, 'update']);
Route::delete('/catagory/{id}', [catagoryController::class, 'destroy']);
//subCatagoris
Route::get('/sub_catagory', [subCatagoryController::class, 'index']);
Route::post('/sub_catagory', [subCatagoryController::class, 'store']);
Route::get('/sub_catagory/{id}', [subCatagoryController::class, 'show']);
Route::put('/sub_catagory/{id}', [subCatagoryController::class, 'edit']);
Route::post('/sub_catagory/{id}', [subCatagoryController::class, 'update']);
Route::delete('/sub_catagory/{id}', [subCatagoryController::class, 'destroy']);
//subSubCatagories
Route::get('/sub_sub_catagory', [subSubCatagoryController::class, 'index']);
Route::post('/sub_sub_catagory', [subSubCatagoryController::class, 'store']);
Route::get('/sub_sub_catagory/{id}', [subSubCatagoryController::class, 'show']);
Route::put('/sub_sub_catagory/{id}', [subSubCatagoryController::class, 'edit']);
Route::post('/sub_sub_catagory/{id}', [subSubCatagoryController::class, 'update']);
Route::delete('/sub_sub_catagory/{id}', [subSubCatagoryController::class, 'destroy']);

//document
Route::get('/document', [documentController::class, 'index']);
Route::post('/document', [documentController::class, 'store']);
Route::get('/document/{id}', [documentController::class, 'show']);
Route::put('/document/{id}', [documentController::class, 'edit']);
Route::post('/document/{id}', [documentController::class, 'update']);
Route::delete('/document/{id}', [documentController::class, 'destroy']);
