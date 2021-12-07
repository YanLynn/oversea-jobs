<?php

namespace App\Http\Controllers\API\V1\Recruiter;

use App\Http\Controllers\API\V1\BaseController;
use Illuminate\Http\Request;
use App\User;
use App\Model\Recruiter;
use App\Model\Jobseeker;
use DB;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\RecruiterRegisterMail;
use App\Helpers\CustomLog;
use App\Model\Scout;
use App\Model\JobApply;
use App\Model\Job;

class RecruiterController extends BaseController
{
    /**
     * @to show data count of recruiter in mypage
     * @author Zayar Phone Naing @ 2020/08/10
     * @last_maintained @ 2020/08/17
     * @param  
     * @return response
     */
    public function myPage()
    {
        $user_id    = auth()->user()->id;
        $today_date = Carbon::now()->format("Y-m-d");

        $new_jobseekers_cnt = User::whereDate('created_at', $today_date)
                                ->where(['role_id' => 3, 'email_verified' => 1, 'record_status' => 1])
                                ->count();
        
        // $waiting_scouted = Scout::where('recruiter_id', 2)->count(); // just tested output
        
        $job_applicants_collection = Recruiter::where('user_id', $user_id)->with('job.jobapply')->first();
        $job_applicants =   $job_applicants_collection->job
                            ->pluck('jobapply')
                            ->flatten()
                            ->filter(function ($job_apply) use ($today_date) {
                                return false !== (stristr($job_apply->created_at, $today_date) && $job_apply->job_apply_status == '検討中');
                            })
                            ->count();

        $scouted_trustees   = Scout::whereDate('updated_at', $today_date)
                                ->where('scout_status', '興味あり')
                                ->with('recruiter')
                                ->whereHas('recruiter', function($query) use ($user_id){
                                        $query->where('user_id', $user_id);                                        
                                    })
                                ->count();

        $response_array = [
            'new_jobseekers'     => $new_jobseekers_cnt,
            'job_applicants'    => $job_applicants,
            'scouted_trustees'  => $scouted_trustees,
        ];

        return $this->createResponse($response_array, 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [            
			'email' => 'unique:users,email,3,record_status,role_id,2,email_verified,1',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return $this->createErrorResponse($errors, 422);
        }

        $activation_link = '';
        DB::beginTransaction();
        try {

            $user = new User;
            $recruiter = new Recruiter;

            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->role_id = 2;
            $user->email_verified = false;
            $user->password = bcrypt($request->input('password'));
            $user->save();            
            $registered_id  = $user->id;

            $recruiter->user_id = $user->id;
            $recruiter->recruiter_name = $request->input('name');
            $recruiter->recruiter_nick_name = $request->input('recruiter_nick_name');
            $recruiter->registered_place_type = $request->input('register_place');
            
            // $recruiter->establishment_date = Carbon::createFromDate($request->input('establishment_year'), $request->input('establishment_month'), 1);
            $recruiter->establishment_date = $request->input('establishment_date');
            $recruiter->representative_name = $request->input('representative_name');
            $recruiter->num_of_employees = $request->input('num_of_employees');
            $recruiter->business_description = $request->input('business_description');
            $recruiter->address = $request->input('address');
            $recruiter->phone1 = $request->input('phone');
            $recruiter->email = $request->input('email');
            $recruiter->website = $request->input('website');
            $recruiter->logo = '';
            $recruiter->video = '';
            $recruiter->incharge_name = $request->input('incharge_name');
            $recruiter->incharge_name_furigana = $request->input('incharge_name_furigana');
            $recruiter->question = (strtolower($request->input('security_question')) == 'その他（全角54文字以下）')? $request->input('own_question'): $request->input('security_question'); 
            $recruiter->answer = $request->input('security_answer'); 

            $recruiter->save();
			// $recruiter->calculateRecruiterNumber();
			
            // --Send activation link
            $this->sendMail($request->input('name'), $request->input('email'), $registered_id, $request->input('frontend_url'));

            DB::commit();
            CustomLog::info("Create ".$user->id);
            CustomLog::info("Create ".$recruiter->id);
            
        } catch (\Exception $e) {            
            DB::rollback();
            return $this->createErrorResponse($e->getMessage(), 500);
        }

        return $this->createResponse($activation_link,201);
    }

    public function activate($encryptedEmail, $encryptedDate, $registered_id, Request $request) 
    {
        $frontend = $request->input('redirect');
        $activate_success = true;

        $decryptedMail  = $this->performCrypt($encryptedEmail, 'd');
        $expiredDate  = $this->performCrypt($encryptedDate, 'd');
       
       
        // --Something wrong with encrypted value
        if (!$decryptedMail || !$expiredDate) { 
            return redirect()->to(rtrim($frontend, "/").'/recruiter/register?activate=fail&email='.$encryptedEmail.'&registeredid='.$registered_id);
        }

        // $matchThese = ['email' => $decryptedMail,'role_id' => 2];
        $user = User::where('email', '=', $decryptedMail)->where('role_id','=',2)->where('record_status','<>',3)->orderBy('id','desc')->get();
        if (count($user) == 0) {
            $activate_success = false;
            return redirect()->to(rtrim($frontend, "/").'/recruiter/register?activate=fail&email='.$encryptedEmail.'&registeredid='.$registered_id);
        }

        // --Account already activated
        for($i=0;$i<count($user);$i++) {
            if ($user[$i]->email_verified == true) {
                return redirect()->to(rtrim($frontend, "/").'/recruiter/register?activate=activated');
            }
        }
        
        // if ($user == null) {
        //     $activate_success = false;
        //     return redirect()->to(rtrim($frontend, "/").'/recruiter/register?activate=fail&email='.$encryptedEmail.'&registeredid='.$registered_id);
        // }        

        // // --Account already activated
        // if($user->email_verified == true) {
        //     return redirect()->to(rtrim($frontend, "/").'/recruiter/register?activate=activated');
        // }

	    if ((Carbon::now())->format("Y/m/d H:i:s") > $expiredDate) {
            // Expired
	        $activate_success = false;
	    } else {
	        // still Valid
            $recruiter = Recruiter::where(['user_id' => $registered_id])->first();
            if ($recruiter == null) {
                $activate_success = false;
            }
            else{
                $actived_user = User::findOrFail($registered_id);
                $actived_user->email_verified = true;
                $actived_user->save();
                
                $expired_rc = User::where('email', '=', $decryptedMail)->where('role_id','=',2)->where('record_status','<>',3)->where('email_verified',0)->select('id')->get();
                $exp_user_id_arr = [];
                foreach ($expired_rc as $e => $v) {
                    array_push($exp_user_id_arr, $v->id);
                }
                Recruiter::whereIn('user_id',$exp_user_id_arr)->delete();
                User::whereIn('id',$exp_user_id_arr)->delete();

                $recruiter->calculateRecruiterNumber();
                CustomLog::info(sprintf("Recruiter %s-%s is successfully activated.", $recruiter->id, $recruiter->email));
            }
	    }

        if ($activate_success) {            
            return redirect()->to(rtrim($frontend, "/").'/recruiter/register?activate=success');
        } else {
            return redirect()->to(rtrim($frontend, "/").'/recruiter/register?activate=fail&email='.$encryptedEmail.'&registeredid='.$registered_id);
        }

    }

    public function isMailUnique(Request $request)   
    {
        $validator = Validator::make($request->all(), [
            'email' => 'unique:users,email,3,record_status,role_id,2,email_verified,1',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return $this->createErrorResponse($errors, 422);
        }
        return $this->createResponse([], 200);
    }

    public function resendMail(Request $request) 
    {
        if ($request->input('encrypted') == 1) { // check for encrypted email
            $email = $this->performCrypt($request->input('email'), 'd'); //-- decrypt the email
        } else {
            $email = $request->input('email');
        }
        // --Retrieve name
        $user = User::where('email', $email)->where('role_id','=',2)->where('record_status','<>',3)->where('email_verified',0)->first();
        if ($user) {
            $this->sendMail($user->name, $email, $request->input('registered_id'), $request->input('frontend_url'));
            return $this->createResponse('Email has been sent', 200);
        }
        return $this->createErrorResponse('Email is invalid.', 500);
    }

    private function sendMail($name, $email, $registered_id, $frontend_url) 
    {
        $currentDate = date('Y/m/d', strtotime(Carbon::now()));
        $checkExpiredDate = date('Y/m/d H:i:s', strtotime(Carbon::now()));
        $expiredDate = date('Y/m/d H:i:s', strtotime($checkExpiredDate . ' +1 day'));

        $encryptedEmail = $this->performCrypt(strtolower($email), 'e');
        // e for encrypt & d for decrypt
        $encryptedDate  = $this->performCrypt($expiredDate, 'e');
        // e for encrypt & d for decrypt

        $baseUrl = url('/api/v1/recruiter/activate/'.$encryptedEmail.'/'.$encryptedDate.'/'. $registered_id.'?redirect='.$frontend_url);
        $data = [
            'name' => $name,
            'today' => $currentDate,
            'url' => $baseUrl,
            'expiredDate' => $expiredDate
        ];
        Mail::to($email)->send(new RecruiterRegisterMail($data));
    }

    private function performCrypt($string, $action = 'e')
    {
        // you may change these values to your own
        $secret_key = 'oversea_job_secret_key';
        $secret_iv = 'oversea_job_secret_iv';
        $output = '';
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'e') {
            $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
        } else if ($action == 'd') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }

    public function changePassword(Request $request)
	{
		$user = auth()->user();

		$validator = Validator::make($request->all(), [            
			'password' => 'required',
        ]);
        if ($validator->fails()) {			
			$errors = $this->transformValidationErrorMsg($validator->errors());
            return $this->createErrorResponse($errors, 422);
        }

		$model = User::findOrFail($user->id);
		$model->password = bcrypt($request->input('password'));
        $model->save();
        CustomLog::info("Update ".$user->id);

		return $this->createResponse($user, 200);
    }
    
    public function deactivate(Request $request) {
        $user = auth()->user();

        DB::beginTransaction();
        try {
            $model = User::findOrFail($user->id);
            $model->record_status = 3; // from 'active' to 'stop'
            $model->save();

            $recruiter = Recruiter::where(['user_id' => $user->id])->first();
            $recruiter->recruiter_number = $recruiter->recruiter_number.'T';
            $recruiter->favourite_jobseeker_ids = NULL;
            $recruiter->record_status = 3; // from 'active' to 'stop'
            $recruiter->save();
           
            $scout_status = [config('config.scouts.waiting'),config('config.scouts.interested'),config('config.scouts.expired')];
            Scout::where('recruiter_id', $recruiter->id)
                    ->whereIn('scout_status', $scout_status)
                    ->delete();

            $job_by_recruiter = Job::where('recruiter_id', $recruiter->id)
                                ->select('id')
                                ->get();
            JobApply::where('job_apply_status',config('config.job_apply.under_review'))
                    ->whereIn('job_id', $job_by_recruiter)
                    ->delete();

            DB::commit();

            CustomLog::info("Update " . $user->id);
            return $this->createResponse($user, 200);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->createErrorResponse($e->getMessage(), 500);
        }
    }    

    public function viewInvoice() {
		$payment = PaymentTransaction::latest()->firstOrFail();
		$payment->default_amount = 200000;
		$payment->tax = 20000;
        $scout = Scout::findOrFail($payment->scoutid_or_applyid);
        $payment->management_number = $scout->management_number;
		$scout->payment = $payment;

		$PDF = PDF::loadView('Mail.scout-invoice-pdf', [ 
			'data' => $scout,
			'sign' => get_sign_image_base64(),
		]);
		$pdf_filename = $payment->invoice_number . '.pdf';
		return $PDF->stream($pdf_filename);
	}

}
