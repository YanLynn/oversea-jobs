<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Jobseeker Only
Route::group(['middleware' => ['auth.role:3']], function() {
    Route::post('password-change','JobseekerController@changePassword');  
});

Route::group(['middleware' => ['auth.role:1,3']], function() {
    Route::get('{userId}/scouts', 'JobseekerScoutController@scoutedList');
	Route::post('/scouts/make-action', 'JobseekerScoutController@makeAction');
	Route::get('/scouts/{scoutId}', 'JobseekerScoutController@getScout');
	Route::get('/job-favourite/{jobId}/add','JobseekerJobController@addJobFav');
    Route::get('/job-favourite/{jobId}/remove','JobseekerJobController@removeJobFav');
  
    Route::get('applied-list','JobseekerJobApplyController@appliedList');
    Route::get('apply-changes-status/{id}/status/','JobseekerJobApplyController@jobSeekerApplyChangeStatus');
    // Jobseeker Profile
    Route::get('required-list','JobseekerProfileController@requiredList');

    Route::get('profile/basicinfo','JobseekerProfileController@getBasicInfo');
    Route::post('profile/basicinfo','JobseekerProfileController@updateBasicInfo');

    Route::post('profile/carrer','JobseekerProfileController@updateCarrer');
    Route::get('profile/carrerinfo','JobseekerProfileController@getCarrer');

    Route::post('profile/experiences-qualifications','JobseekerProfileController@getExperiencesQualifications');
	Route::post('profile/experiences-qualifications/update','JobseekerProfileController@saveExperiencesQualifications');

    Route::post('profile/selfintro','JobseekerProfileController@updateSelfIntro');
    Route::get('profile/selfintro','JobseekerProfileController@getSelfIntro');

    Route::get('profile/desired-condition','JobseekerProfileController@getDesiredCondition');
    Route::post('profile/desired-condition','JobseekerProfileController@updateDesiredCondition');

    Route::post('resume-upload','JobseekerProfileController@fileUpload');
    Route::post('cv-upload','JobseekerProfileController@fileUpload');
    Route::get('resume-list','JobseekerProfileController@getResume');
   
    Route::get('file-remove/{type}/{lang}','JobseekerProfileController@fileRemove');
    
    // Jobseeker Job Favourite
    Route::post('favourite-jobs','JobseekerController@getFavouriteJob');
	Route::post('favourite-job/{job_id}/remove','JobseekerController@deleteFavouriteJob');
    Route::post('recommented-jobs','JobseekerController@getRecommentedJob');
    Route::post('popular-jobs','JobseekerController@getPopularJob');
	Route::post('favourite-job/{job_id}/add','JobseekerController@addFavouriteJob');
	
	
    // Jobseeker Deactivate
    Route::post('deactivate','JobseekerController@deactivate');

    Route::post('job-applied/{jobid}', 'JobseekerJobApplyController@jobapplied');
    Route::get('jopappliedlist/{jobid}','JobseekerJobApplyController@jobappliedlist');

    // MyPage
    Route::get('unread-scoutemail/{user_id}','JobseekerController@getJobSeeker');

    // Scout setting
    Route::post('info','JobseekerController@getJobSeekerInfo');
	Route::post('scout-setting/update','JobseekerController@changeScoutSettingStatus');
});

Route::post('register','JobseekerController@register');
Route::post('resend-mail','JobseekerController@resendMail');
Route::get('activate/{encryptedEmail}/{encryptedDate}/{id}','JobseekerController@activate');
Route::get('country-list', 'JobseekerController@getcountry');
Route::get('city-list/{country_name}', 'JobseekerController@getCity');
Route::post('mail-unity', 'JobseekerController@isMailUnique');
Route::post('selected-country-list/{name}','JobseekerProfileController@countryList');