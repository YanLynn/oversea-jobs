<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Recruiter Only
Route::group([ 'middleware' => [ 'auth.role:2'], ], function() {
    Route::post('password-change', 'RecruiterController@changePassword');
    Route::post('deactivate','RecruiterController@deactivate');
    Route::post('mypage', 'RecruiterController@myPage');
    Route::post('notice-send', 'RecruiterJobController@sendNoticeAcceptance');
});

// Admin Only
Route::group([ 'middleware' => [ 'auth.role:1'], ], function() {
    // Admin => Recruiter
    Route::get('recruiters/{id}/edit','RecruiterProfileController@getProfile');
    Route::post('recruiters/{id}/update', 'RecruiterProfileController@updateProfile');
});

// Admin and Recruiter
Route::group(['middleware' => ['auth.role:1,2']], function() {
    Route::get('occupation-list', 'RecruiterJobController@showoccupations');
    Route::get('getalldata', 'RecruiterJobController@getalldata');
    Route::get('country-list', 'RecruiterJobController@showcountry');
    Route::post('jobs/add', 'RecruiterJobController@create');
    Route::get('job-list/{status}/{check}', 'RecruiterJobController@list');
    Route::post('job-list/change-status', 'RecruiterJobController@changeStatus');
    Route::post('job-list/{status}/delete', 'RecruiterJobController@delete');
    Route::get('jobs/{id}/edit', 'RecruiterJobController@getJobs');
    Route::post('jobs/{id}/update', 'RecruiterJobController@update');
    Route::get('fav-list/{status}','RecruiterFavouriteController@getFavJobseeker');   
    Route::get('fav-list/{id}/delete','RecruiterFavouriteController@removeFavJobseeker'); 
    Route::get('scouted-list/{check}','RecruiterScoutController@scoutedList');
    Route::get('/jobseeker-favourite/{id}/add','RecruiterJobController@addJobSeekerFav');
    Route::get('/jobseeker-favourite/{id}/remove','RecruiterJobController@removeJobSeekerFav');

    // --scouting actions
    Route::get('scoutable/{jobseeker_id}', 'RecruiterScoutController@isJobseekerScoutable');
    Route::get('scoutable/{jobseeker_id}/profile', 'RecruiterScoutController@getJobseekerProfile');
    Route::get('scoutable-jobs/{jobseeker_id}', 'RecruiterJobController@getScoutableJobs');
    Route::get('jobseeker-detail/{type}/{transaction_id}', 'RecruiterScoutController@jobseekerDetail');
    Route::post('scouted/download/{uploaded_file_id}', 'RecruiterScoutController@downloadCv');
    Route::post('scout', 'RecruiterScoutController@scoutJobseeker');
    Route::put('fav-list/add/{jobseeker_id}','RecruiterFavouriteController@addFavJobseeker');

    // Invoice
    Route::post('scouted-list/{scout_id}/invoice', 'RecruiterPaymentController@downloadScoutInvoice');
    Route::post('jobapply-list/{jobapply_id}/invoice', 'RecruiterPaymentController@downloadJobapplyInvoice');

    // Job Post
    Route::get('jobpost/jobapply-list/{check}', 'RecruiterJobController@getJobApplyList');

    // Recruiter Profile
    Route::get('profile', 'RecruiterProfileController@getProfile');
    Route::post('profile', 'RecruiterProfileController@updateProfile');
    
});

// Public Forget Password
Route::post('mail-unity', 'RecruiterController@isMailUnique');
Route::post('register','RecruiterController@register');
Route::post('resend-mail','RecruiterController@resendMail');
Route::get('activate/{encryptedEmail}/{encryptedDate}/{id}','RecruiterController@activate');  

$middleware = ['api'];
if (\Request::header('Authorization')){
    $middleware = array_merge(['auth.role:2']);
}
// Credit Card
Route::group(['middleware' => $middleware], function() {
    Route::get('card-info/{userid}', 'RecruiterPaymentController@cardInfo');
    Route::get('transaction-list/{id}/{type}/transaction-info', 'RecruiterPaymentController@transactionInfo');
    Route::post('transaction-list/credit-payment', 'RecruiterPaymentController@creditCardPayment');    
    
    // Payment Success
    Route::post('payment-complete', 'RecruiterPaymentController@paymentComplete');
});
// End Credit Card