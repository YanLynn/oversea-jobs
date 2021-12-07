<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api']], function() {
  
    Route::post('change-email','UniversalController@changeEmail');
    Route::post('check-password','UniversalController@checkPassword');
	
	// --Chat message routes
	Route::get('chattables/2/{type}', 'ChatMessageController@getRecruiterChattables');
	Route::get('chattables/3/{type}', 'ChatMessageController@getJobseekerChattables');
	Route::get('chattables/1/{type}', 'ChatMessageController@getAdminChattables');

	Route::get('messages/meta/{type}/{id}', 'ChatMessageController@getChatMetadata');
	Route::post('messages/file/{id}','ChatMessageController@downloadFile');
	Route::get('messages/fileDownload/{id}','ChatMessageController@fileDownload');
	Route::delete('messages/{id}','ChatMessageController@deleteMessage');
	Route::get('messages/{type}/{id}', 'ChatMessageController@getMessage');

	Route::post('messages/read/{type}/{id}/{role}/{opposite_id}', 'ChatMessageController@markMessageRead');
	Route::post('messages', 'ChatMessageController@saveMessage');
	Route::post('messages/send-mail','ChatMessageController@sendMail');

    Route::get('block-user/{user_id}', 'UniversalController@getBlockUser');
    // Route::get('current-user', 'UniversalController@getCurrentUser');
    Route::get('me', 'UniversalController@getCurrentUser');
});
Route::post('update-email','UniversalController@updateEmail');

Route::get('jobs/{id}', 'JobController@getjob');
Route::get('jobs/{id}/details', 'JobController@getJobDetails');
// Route::get('recruiterprofile/{id}', 'ProfileController@recruiterprofile');

Route::get('country_occupation_list/{type}', 'UniversalController@getCountryAndOccupation');

Route::get('jobseeker-search/{check}','UniversalController@searchJob');
Route::get('recruiter-search/{check}','UniversalController@searchSeeker');

Route::get('default-image', 'UniversalController@defaultImage');

// Testing
Route::post('image-blur', 'UniversalController@testImageBlur');
Route::get('jobseeker-profile/{id}', 'UniversalController@getJobseekerProfile');

Route::post('query','UniversalController@createContent');

Route::post('popular-jobs/{job_id}','UniversalController@popularJob');

Route::get('tax/current', 'UniversalController@getCurrentTax');
