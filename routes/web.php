<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\USSD\UssDController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\SupportPredictorController;


/* Logout */
Route::get('/logout', function () {
    \Auth::logout();
    return redirect(route('login'));
})->name('logout');
/* Dashboard controller*/
Route::group([ 'middleware' => ['auth','nocache'],'namespace' => 'App\Http\Controllers'], function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
});

Route::get('/', [AuthController::class,'index'])->name('login');
Route::any('/login', [AuthController::class,'login'])->name('admin-login-post');
Route::get('/forgot-password', [AuthController::class,'forgotPassword'])->name('forgot-password');
Route::post('/forgot-password', [AuthController::class,'store'])->name('forgot-password-post');
Route::get('/reset-password/{token}', [AuthController::class,'viewReset'])->name('reset-password');
Route::get('/reset/{id}', [AuthController::class,'Reset'])->name('reset');
Route::post('/reset-password', [AuthController::class,'storePassword'])->name('reset-password-post');
Route::post('/reset', [AuthController::class,'storePasswordReset'])->name('reset-post');
Route::get('/change-password', [AuthController::class,'viewChangePassword'])->name('change-password');
Route::post('/change-password', [AuthController::class,'storeNewPassword'])->name('change-password-post');

#Profile module
Route::group(['prefix' => '/users', 'middleware' => ['auth','nocache'],'namespace' => 'App\Http\Controllers'], function () {
    Route::post('update-profile', 'UserController@updateProfile')->name('manage-update-profile');
    Route::get('edit-profile', 'UserController@editProfile')->name('manage-edit-profile');

});

#manage-users
Route::group(['prefix' => '/users', 'middleware' => ['auth','nocache','can:Manage-Users'],'namespace' => 'App\Http\Controllers'], function () {
    Route::get('/users', 'UserController@index')->name('manage-user');
    Route::get('/get-user-list-ajax', 'UserController@getUserListAjax')->name('getUserListAjax');
    Route::get('/add', 'UserController@add')->name('manage-user-add');
    Route::post('/save', 'UserController@save')->name('manage-user-save');
    Route::post('/update', 'UserController@update')->name('manage-user-update');
    Route::get('/edit/{id}', 'UserController@edit')->name('manage-user-edit');
    Route::any('/delete/{id}', 'UserController@delete')->name('manage-user-delete');
    Route::post('/status'    , 'UserController@status')->name('manage-user-status');
    Route::any('/images/delete','UserController@deleteImage')->name('manage-user-images-delete');
    Route::any('/activities/{id}','UserController@activities')->name('manage-user-activities');


});
#Manage Roles
Route::group(['prefix' => '/manage-role', 'middleware' => ['auth','nocache','can:Manage-Roles'], 'namespace' => 'App\Http\Controllers', 'page-group' => '/manage-role'], function () {
    Route::get('/list-role', 'RoleController@index')->name('role-list');
    Route::get('/datatable', 'RoleController@getDatatable')->name('role-datatable');
    Route::get('/add', 'RoleController@add')->name('role-add');
    Route::post('/save', 'RoleController@save')->name('role-save');
    Route::get('/edit/{id}', 'RoleController@edit')->name('role-edit');
    Route::post('/update', 'RoleController@update')->name('role-update');
    Route::any('/status', 'RoleController@status')->name('role-status');
    Route::any('/delete', 'RoleController@delete')->name('role-delete');
});
#Manage Activity log
Route::group(['prefix' => '/activity', 'middleware' => ['auth','nocache','can:Manage-Users'], 'namespace' => 'App\Http\Controllers', 'page-group' => '/activities'], function () {
    Route::any('/viewActivity/{id}','ActivityController@getActivityAjaxView')->name('viewActivity');
    Route::any('/viewActivities/{id}','ActivityController@viewActivity')->name('activity-view');
});
 #categories

 Route::group(['prefix' => '/categories', 'middleware' => ['auth','nocache','can:Manage-categories'], 'namespace' => 'App\Http\Controllers', 'page-group' => '/categories'], function () {
    Route::any('/list-categories','CategoryController@getCategoryListAjax')->name('getCategoryListAjax');
    Route::any('/add-category','CategoryController@add')->name('add-categories');
    Route::post('/save-category', 'CategoryController@store')->name('manage-category-save');
    Route::get('/categories', 'CategoryController@index')->name('manage-category');
    Route::post('/update', 'CategoryController@update')->name('manage-category-update');
    Route::get('/edit/{id}', 'CategoryController@edit')->name('manage-category-edit');
    Route::any('/delete/{id}', 'CategoryController@delete')->name('manage-category-delete');
    Route::post('/status'    , 'CategoryController@status')->name('manage-category-status');
});

#members
Route::group(['prefix' => '/members', 'middleware' => ['auth','nocache','can:Manage-Members'], 'namespace' => 'App\Http\Controllers', 'page-group' => '/members'], function () {
    Route::any('/list-members','MemberController@getMemberListAjax')->name('getMembersListAjax');
    Route::any('/add-members','MemberController@add')->name('add-members');
    Route::post('/save-members', 'MemberController@store')->name('manage-members-save');
    Route::get('/members', 'MemberController@index')->name('manage-members');
    Route::post('/update', 'MemberController@update')->name('manage-members-update');
    Route::get('/edit/{id}', 'MemberController@edit')->name('manage-members-edit');
    Route::any('/delete/{id}', 'MemberController@delete')->name('manage-members-delete');
    Route::post('/status'    , 'MemberController@status')->name('manage-members-status');
     Route::post('/support-status'    , 'MemberController@supportStatus')->name('manage-members-support-status');
});

#centrale
Route::group(['prefix' => '/centrale', 'middleware' => ['auth','nocache','can:Manage-Centrale'], 'namespace' => 'App\Http\Controllers', 'page-group' => '/centrale'], function () {
    Route::any('/list-centrale','CenterController@getCentraleListAjax')->name('getCentralesListAjax');
    Route::any('/add-centrales','CenterController@add')->name('add-centrales');
    Route::post('/save-centrales', 'CenterController@store')->name('manage-centrales-save');
    Route::get('/centrales', 'CenterController@index')->name('manage-centrales');
    Route::post('/update', 'CenterController@update')->name('manage-centrales-update');
    Route::get('/edit/{id}', 'CenterController@edit')->name('manage-centrales-edit');
    Route::any('/delete/{id}', 'CenterController@delete')->name('manage-centrales-delete');
    Route::post('/status'    , 'CenterController@status')->name('manage-centrales-status');
});
#community
Route::group(['prefix' => '/communities', 'middleware' => ['auth','nocache','can:Manage-community'], 'namespace' => 'App\Http\Controllers', 'page-group' => '/centrale'], function () {
    Route::any('/list-community','CommunityController@getCommunityListAjax')->name('getCommunityListAjax');
    Route::any('/add-community','CommunityController@add')->name('add-community');
    Route::post('/save-community', 'CommunityController@store')->name('manage-community-save');
    Route::get('/community', 'CommunityController@index')->name('manage-community');
    Route::post('/update', 'CommunityController@update')->name('manage-community-update');
    Route::get('/edit/{id}', 'CommunityController@edit')->name('manage-community-edit');
    Route::any('/delete/{id}', 'CommunityController@delete')->name('manage-community-delete');
    Route::post('/status'    , 'CommunityController@status')->name('manage-community-status');
    Route::post('view-community','CommunityController@viewCommunity')->name('view-community');
});
#Parish
Route::group(['prefix' => '/parish', 'middleware' => ['auth','nocache','can:Manage-Parish'], 'namespace' => 'App\Http\Controllers', 'page-group' => '/centrale'], function () {
    Route::any('/list-parish','ParishController@getparishListAjax')->name('getParishListAjax');
    Route::any('/add-parish','ParishController@add')->name('add-parish');
    Route::post('/save-parish', 'ParishController@store')->name('manage-parish-save');
    Route::get('/parish', 'ParishController@index')->name('manage-parish');
    Route::post('/update', 'ParishController@update')->name('manage-parish-update');
    Route::get('/edit/{id}', 'ParishController@edit')->name('manage-parish-edit');
    Route::any('/delete/{id}', 'ParishController@delete')->name('manage-parish-delete');
    Route::post('/status'    , 'ParishController@status')->name('manage-parish-status');
    Route::post('view-parish','ParishController@viewParish')->name('view-Parish');
});

#support
Route::group(['prefix' => '/support', 'middleware' => ['auth','nocache','can:Manage-Supports'], 'namespace' => 'App\Http\Controllers', 'page-group' => '/support'], function () {
    Route::any('/list-support','SupportController@getSupportListAjax')->name('getSupportListAjax');
    Route::post('/save-support', 'SupportController@store')->name('manage-support-save');
    Route::post('/update-support', 'SupportController@update')->name('manage-support-update');
    Route::get('/support', 'SupportController@index')->name('manage-support');
    Route::post('/status'    , 'SupportController@status')->name('manage-support-status');
});

#Income
Route::group(['prefix' => '/income', 'middleware' => ['auth','nocache','can:Manage-Income'], 'namespace' => 'App\Http\Controllers', 'page-group' => '/income'], function () {
    Route::any('/list-income','IncomeController@getIncomeListAjax')->name('getIncomeListAjax');
    Route::post('/save-income', 'IncomeController@store')->name('manage-income-save');
    Route::post('/update-income', 'IncomeController@update')->name('manage-income-update');
    Route::get('/income', 'IncomeController@index')->name('manage-income');
    Route::post('/status'    , 'IncomeController@status')->name('manage-income-status');
    Route::get('incomes/{id}', 'IncomeController@show')->name('manage-income-single');
});
Route::post('/ussd', [USSDController::class, 'handleUSSD']);
Route::post('/ussd/next', [UssdController::class, 'handleNext']);
#location
Route::get('/province', [LocationController::class, 'index'])->name('province');
Route::get('/district', [LocationController::class, 'district'])->name('district');
Route::get('/sector', [LocationController::class, 'sector'])->name('sector');
Route::get('/cell', [LocationController::class, 'cell'])->name('cell');
Route::get('/vellage'   , [LocationController::class, 'village'])->name('village');

Route::get('/support-predictor', [SupportPredictorController::class, 'index'])
    ->name('support-predictor')
    ->middleware('can:Support-Predictor');