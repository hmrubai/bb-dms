<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();

});



Route::get('/test',function (){
        return "hello";
    })->middleware('auth:sanctum');


Route::post('/auth/register', [Api\AuthController::class, 'createUser']);
Route::post('/auth/login', [Api\AuthController::class, 'loginUser']);
Route::get('/users',[userController::class,'index']);



