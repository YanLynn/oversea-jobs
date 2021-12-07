<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth.role:1']], function() {
    Route::get('contact_list' , 'contactController@getlist');
    Route::post('add_contact','contactController@addContact');
    Route::put('update_contact/{id}','contactController@updateContact');
    Route::delete('delete_contact/{id}','contactController@deleteContact');
});


Route::group(['prefix' => 'auth'], function ($router) {
    Route::post('registerRecruiter', 'AuthController@registerRecruiter');
    Route::post('register', 'AuthController@register');
    Route::post('jobseeker_register', 'AuthController@jobseeker_register');
    Route::post('login', 'AuthController@login');
    Route::post('jobseeker/login', 'AuthController@jobseekerLogin');
    Route::post('recruiter/login', 'AuthController@recruiterLogin');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');  
    Route::post('forgot-password', 'ResetPasswordController@sendPasswordResetLink'); 
    Route::get('reset-password/', 'ResetPasswordController@callResetPassword');
    Route::post('updateResetPassword', 'ResetPasswordController@updateResetPassword');
    Route::post('checkAns', 'ResetPasswordController@checkAns');
    
});

Route::group(['middleware' => ['auth.role:2,3']], function() {
    //Recruiter Noti
    Route::get('recruiter-fav-count','NotificationController@getRecruiterFavCount');
    Route::get('applicant-count','NotificationController@getApplicantCount');
    Route::get('recruiter-scouted-count','NotificationController@getScoutedCount');

    //Jobseeker Noti
    Route::get('jobseeker-scouted-count','NotificationController@scoutedCount');
    Route::get('jobseeker-fav-count','NotificationController@getJobseekerFavCount');

    Route::get('user-img','NotificationController@userImg');
    Route::get('user-info','NotificationController@userInfo');

    Route::post('time-zone','AuthController@timezone');
    Route::get('time-zone-list','AuthController@timeZoneList');
    Route::get('user-time-zone','AuthController@gettimezone');

    
});
