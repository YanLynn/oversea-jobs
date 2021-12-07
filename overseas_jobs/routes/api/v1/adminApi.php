<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth.role:1']], function() {   
    // Admin setting routes 
    Route::post('password-change', 'AdminController@changePassword');
    Route::post('login-unlock', 'AdminController@clearLoginLocked');

    // Admin jobseeker list routes
    Route::post('jobseeker-list', 'AdminJobSeekerController@index');
    Route::post('jobseeker-list/delete', 'AdminJobSeekerController@delete');
    Route::post('jobseeker-list/change-status', 'AdminJobSeekerController@changeStatus');

    // --Admin scout operation routes
	Route::post('scout-list', 'AdminScoutController@index');
	Route::post('scout-list/generate-invoice', 'AdminScoutController@generateBill');
	Route::post('scout-list/confirm-payment', 'AdminScoutController@confirmPayment');
	Route::post('scout-list/send-invoice-mail', 'AdminScoutController@sendInvoiceMail');
	Route::post('scout-list/change-status', 'AdminScoutController@changeStatus');
    
    Route::get('taxes','AdminScoutController@taxes');
    // Admin jobapply list routes
    Route::post('jobapply-list', 'AdminJobApplyController@index');
    Route::get('jobapply-list/confirm-payment/{id}', 'AdminJobApplyController@confirmPayment');
    Route::post('jobapply-list/generate-invoice', 'AdminJobApplyController@generateBill');
	Route::post('jobapply-list/confirm-payment', 'AdminJobApplyController@confirmPayment');
	Route::post('jobapply-list/send-invoice-mail', 'AdminJobApplyController@sendInvoiceMail');
	Route::post('jobapply-list/change-status', 'AdminJobApplyController@changeStatus');

    // --Admin joblist of recruiters routes by zayarphonenaing
	Route::post('job-list', 'AdminRecruiterController@jobList');
	Route::post('job-list/delete', 'AdminRecruiterController@deleteJob');
	Route::post('job-list/change-status', 'AdminRecruiterController@changeJobStatus');
    
    // Admin recruiter
    Route::post('recruiter-list', 'AdminRecruiterController@index');
    Route::post('recruiter-list/delete', 'AdminRecruiterController@delete');
    Route::post('recruiter-list/change-status', 'AdminRecruiterController@changeStatus');

    // Payment transactions by Myo Ko Ko
	Route::post('payment-transactions', 'AdminPaymentController@index');
	Route::put('payment-transactions/{id}', 'AdminPaymentController@update');
    Route::post('payment-transactions/csv', 'AdminPaymentController@exportCsv');
    Route::post('payment-transactions/{jobapply_id}/jobapply-invoice', 'AdminPaymentController@viewJobapplyInvoice');
	Route::post('payment-transactions/{scout_id}/scout-invoice', 'AdminPaymentController@viewScoutInvoice');
    
    // Credit Payment
    Route::post('billMail/{type}', 'AdminPaymentController@billMail');
    Route::post('checkPay/{type}', 'AdminPaymentController@checkPay');
    
    // Admin Dashboard by Yan
    Route::get('dashboard-count','AdminController@getCount');
    Route::get('admin-setting-taxes','AdminSettingController@taxes');
    Route::post('update-taxes','AdminSettingController@updateTaxes');
    Route::get('get-extra','AdminSettingController@getExtraEmail');
    Route::post('update-email/{status}','AdminSettingController@updateEmail');
    Route::get('email-send','AdminSettingController@emailSend');
    Route::post('update-email-send','AdminSettingController@updateEmailSend');
    // CreditCard Testing
    // Route::get('payment-creditcard-testing', 'AdminPaymentController@creditCardTest');
});

// Route::get('payment-creditcard-testing', 'AdminPaymentController@creditCardTest');
Route::get('payment-creditcard-testing', 'AdminPaymentController@userInfoTest');