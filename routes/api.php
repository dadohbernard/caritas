<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\MemberController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\SupportController;
use App\Http\Controllers\API\IncomeController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\TextGenerationController;
use App\Http\Controllers\USSD\UssdController;




#open API
Route::group(['namespace' => 'Api', 'prefix' => 'v1'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forget-password-api', [AuthController::class,'forgetPassword']);

    Route::get('/reset-pass/{token}', [AuthController::class, 'create'])
                ->middleware('guest')
                ->name('password.reset');
    Route::get('/list-category',[CategoryController::class,'index']);

    #this route will help to reach on openAI
Route::post('/generate-text', [TextGenerationController::class, 'generateText']);
Route::post('/ussd', [UssdController::class, 'handleUssd']);
  });
#Manage Authentication and Users APIs
Route::group(['namespace' => 'Api', 'prefix' => 'v1','middleware' => 'auth:api'], function () {
    Route::post('change-password',[AuthController::class,'changePassword']);
    Route::post('/logout-api', [AuthController::class, 'destroy'])->name('logout-api');
    Route::get('/user-detail',[AuthController::class,'index'])->name('user-detail');
    Route::post('/update-profile',[AuthController::class, 'update'])->name('update-profile');
    Route::get('list-user',[UserController::class,'index']);

});

#Manage members

Route::group(['namespace' => 'Api', 'prefix' => 'v1','middleware' => 'auth:api'], function () {
    Route::get('list-members',[MemberController::class,'index']);
    Route::post('create-member', [MemberController::class, 'store']);
    Route::post('/approval',[MemberController::class,'status']);
    // Route::post('/reject',[memberController::class, 'status'])->name('update-profile');
});

#Manage Dashboard

Route::group(['namespace' => 'Api', 'prefix' => 'v1','middleware' => 'auth:api'], function () {
    Route::get('dashboard',[DashboardController::class,'index']);
    Route::post('add-support',[SupportController::class,'store']);

});
#Support
Route::group(['namespace' => 'Api', 'prefix' => 'v1','middleware' => 'auth:api'], function () {
    Route::get('/list-support',[SupportController::class,'index']);
    Route::post('/status'    , [SupportController::class,'status']);
    Route::get('list-income',[IncomeController::class,'index']);
});

