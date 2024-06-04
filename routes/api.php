<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\Application\ApplicationController;
use App\Http\Controllers\Appointment\AppointmentController;
use App\Http\Controllers\Notification\NotificationController;
use App\Http\Controllers\Message\MessageController;
use App\Http\Controllers\OccupancyController;
use App\Http\Controllers\AssessmentController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('login', [AuthController::class, 'login']);
Route::post('forgot', [AuthController::class, 'forgot']);
Route::post('register/applicant', [UserController::class, 'register']);



Route::group(['prefix' => 'backend','middleware' => 'auth:sanctum'], function() {

    //// Auth User
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('auth_user', [AuthController::class, 'authUser']);

     /// routes for users
     Route::post('register', [UserController::class, 'register']);
     Route::get('users', [UserController::class,'index']);
     Route::post('profile/{id}', [UserController::class,'profileUpdate']);
     Route::get('user/{id}', [UserController::class,'show']);
     Route::delete('user/delete/{id}', [UserController::class,'delete']);
     Route::post('user/update/{id}', [UserController::class,'update']);
     Route::post('users/profileupdate/{id}', [UserController::class,'profileUpdate']);

     /// routes for roles
     Route::get('roles', [RoleController::class,'index']);
     Route::get('rolesedit/{id}', [RoleController::class,'edit']);
     Route::delete('rolesdelete/{id}', [RoleController::class,'delete']);
     Route::post('roles/update/{id}', [RoleController::class,'update']);
     Route::post('roles/profileupdate/{id}', [RoleController::class,'profileUpdate']);

     /// routes for application
     Route::get('application', [ApplicationController::class,'index']);
     Route::get('application/{id}', [ApplicationController::class,'show']);
     Route::delete('application/{id}', [ApplicationController::class,'delete']);
     Route::put('application/update/{id}', [ApplicationController::class,'update']);
     Route::put('application/approve/{id}', [ApplicationController::class,'approve']);
     Route::put('application/return/{id}', [ApplicationController::class,'return']);
     Route::put('application/claimed/{id}', [ApplicationController::class,'claimed']);
     Route::post('application', [ApplicationController::class,'store']);
     Route::post('documents', [ApplicationController::class,'uploadDocuments']);
     Route::post('documents/update', [ApplicationController::class,'updateDocuments']);

     Route::get('schedule/available', [AppointmentController::class,'availableDate']);
     Route::get('appointment', [AppointmentController::class,'index']);
     Route::get('appointment/{id}', [AppointmentController::class,'show']);
     Route::put('appointment/{id}', [AppointmentController::class,'update']);
     Route::post('appointment', [AppointmentController::class,'store']);

     Route::post('occupancy', [OccupancyController::class,'store']);
     Route::get('occupancy', [OccupancyController::class,'show']);

     Route::get('notifications', [NotificationController::class,'index']);
     Route::put('read/{id}', [NotificationController::class,'read']);

     Route::get('online-users', [UserController::class,'getOnlineUsers']);
     Route::get('messages', [MessageController::class,'index']);
     Route::put("messages/{id}", [MessageController::class, "update"]);
     Route::post('messages', [MessageController::class,'store']);

     Route::post('assessment', [AssessmentController::class,'store']);
     Route::get('assessment/{id}', [AssessmentController::class,'show']);

     Route::get('official_receipt/{id}', [AssessmentController::class,'getOR']);

});
