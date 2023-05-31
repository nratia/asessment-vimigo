<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\StudentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::controller(UserController::class)->group(function(){
    Route::post('login','loginUser');
    Route::post('register','regUser');
});

Route::controller(UserController::class)->group(function(){

    Route::get('user','getUserDetail');
    Route::get('logout','userLogout');
    Route::get('index/student','index');

})->middleware('auth:api');

Route::controller(StudentController::class)->group(function(){
    Route::get('student/index','index');
});

Route::controller(StudentController::class)->group(function(){
    Route::post('student/register','regStudent');
});

