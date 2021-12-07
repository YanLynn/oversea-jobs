<?php

namespace App\Http\Controllers\API\V1\Jobseeker;

use App\Http\Controllers\API\V1\BaseController;
use Illuminate\Http\Request;
use App\User;
use Auth;
use App\Model\Jobseeker;
use App\Model\Country;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\JobseekerRegisterMail;
use App\Helpers\CustomLog;
use App\Model\Scout;
use App\Model\JobApply;
use App\Model\Job;


class JobseekerController extends BaseController
{

    public function getJobSeeker($user_id, Request $request)
    {
        $jobseeker = Jobseeker::where('user_id', $user_id)->first();
        // $no_of_registered_person_for_newscout = Scout::select('*')
        //                                             ->where('jobseeker_id', $jobseeker_id)
        //                                             ->where('scout_status', 1)
        //                                             ->whereDate('created_at', Carbon::today())
        //                                             ->get();


        // $no_of_new_job_today = Job::select('*')
        //                         ->where('job_post_status', 1)
        //                         ->whereDate('created_at', Carbon::today())
        //                         ->get();

        // return response()->json(array('no_of_registered_person_for_newscout'=>count($no_of_registered_person_for_newscout),
        //                                 'no_of_new_job_today'=>count($no_of_new_job_today)));

        $unread_scout_mail = Scout::select('*')
            ->where('jobseeker_id', $jobseeker->id)
            ->where('scout_status', config('config.scouts.waiting'))
            ->where('read_job', FALSE)
            ->get();
        return response()->json(array('unread_scout_mail' => count($unread_scout_mail)));
    }

    public function register(Request $request)
    {
    
        $validator = Validator::make($request->all(), [            
			'email' => 'unique:users,email,3,record_status,role_id,3,email_verified,1',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return $this->createErrorResponse($errors, 422);
        }

        $activation_link = '';
        DB::beginTransaction();
        try {

            $user = new User;
            $jobseeker = new Jobseeker;

            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->role_id = 3;
            $user->email_verified = false;
            $user->password = bcrypt($request->input('password'));
            $user->save();
            $registered_id  = $user->id;

            $jobseeker->user_id = $user->id;
            $jobseeker->jobseeker_name = $request->input('name');
            $jobseeker->jobseeker_furigana_name = $request->input('jobseeker_furigana_name');
            $jobseeker->dob = $request->input('dob');
            // $jobseeker->address = $request->input('address');

            if($request->country_name != '0'){
              
				if($request->country_name != '0'  && $request->country_id == '0'){
                 
                    $cityname = $request->country_name;
                    $country_id = Country::where('city_name',$cityname)->value('id');
                    $jobseeker->country_id = $country_id;
                    $jobseeker->only_country = true;
				}
				else{
                    $jobseeker->country_id = $request->country_id;					
                }
			}
			else{		
				$jobseeker->country_id = 0;
			}	
            // $jobseeker->country_id = $request->input('country_id');
            $jobseeker->phone = $request->input('phone');
            $jobseeker->email = $request->input('email');
            $jobseeker->save();
            // $jobseeker->calculateJobsekerNumber();
            CustomLog::info("Create".json_encode($jobseeker->jobseeker_number));
            // --Send activation link
            $this->sendMail($request->input('name'),$request->input('jobseeker_furigana_name'), $request->input('email'), $registered_id, $request->input('frontend_url'));
            
            DB::commit();
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
            return redirect()->to(rtrim($frontend, "/").'/jobseeker/register?activate=fail&email='.$encryptedEmail.'&registeredid='.$registered_id);
        }

        // $matchThese = ['email' => $decryptedMail,'role_id' => 3];
        $user = User::where('email', '=', $decryptedMail)->where('role_id','=',3)->where('record_status','<>',3)->orderBy('id','desc')->get();
        if (count($user) == 0) {
            $activate_success = false;
            return redirect()->to(rtrim($frontend, "/").'/jobseeker/register?activate=fail&email='.$encryptedEmail.'&registeredid='.$registered_id);
        }

        // --Account already activated
        for($i=0;$i<count($user);$i++) {
            if ($user[$i]->email_verified == true) {
                return redirect()->to(rtrim($frontend, "/").'/jobseeker/register?activate=activated');
            }
        }

        // if ($user == null) {
        //     $activate_success = false;
        //     return redirect()->to(rtrim($frontend, "/").'/jobseeker/register?activate=fail&email='.$encryptedEmail.'&registeredid='.$registered_id);
        // }        

        // // --Account already activated
        // if($user->email_verified == true) {
        //     return redirect()->to(rtrim($frontend, "/").'/jobseeker/register?activate=activated');
        // }

	    if ((Carbon::now())->format("Y/m/d H:i:s") > $expiredDate) {
            // Expired
	        $activate_success = false;
	    } else {
	        // still Valid
            $jobseeker = Jobseeker::where(['user_id' => $registered_id])->first();
            if ($jobseeker == null) {
                $activate_success = false;
            }
            else{
                $actived_user = User::findOrFail($registered_id);
                $actived_user->email_verified = true;
                $actived_user->save();

                $expired_js = User::where('email', '=', $decryptedMail)->where('role_id','=',3)->where('record_status','<>',3)->where('email_verified',0)->select('id')->get();
                $exp_user_id_arr = [];
                foreach ($expired_js as $e => $v) {
                    array_push($exp_user_id_arr, $v->id);
                }
                Jobseeker::whereIn('user_id',$exp_user_id_arr)->delete();
                User::whereIn('id',$exp_user_id_arr)->delete();

                $jobseeker->calculateJobsekerNumber();
                CustomLog::info(sprintf("Jobseeker %s-%s is successfully activated.", $jobseeker->id, $jobseeker->email));
            }            
	    }

        if ($activate_success) {            
            return redirect()->to(rtrim($frontend, "/").'/jobseeker/register?activate=success');
        } else {
            return redirect()->to(rtrim($frontend, "/").'/jobseeker/register?activate=fail&email='.$encryptedEmail.'&registeredid='.$registered_id);
        }

    }

    public function isMailUnique(Request $request)   
    {
        $validator = Validator::make($request->all(), [
            'email' => 'unique:users,email,3,record_status,role_id,3,email_verified,1',
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
        $user = User::where('email', $email)->where('role_id','=',3)->where('record_status','<>',3)->where('email_verified',0)->first();
        if ($user) {
            $this->sendMail($user->name,$user->jobseeker_furigana_name, $email, $request->input('registered_id'), $request->input('frontend_url'));
            return $this->createResponse('Email has been sent', 200);
        }
        return $this->createErrorResponse('Email is invalid.', 500);
    }

    private function sendMail($name,$jobseeker_furigana_name, $email, $registered_id, $frontend_url) 
    {
        
        $currentDate = date('Y/m/d', strtotime(Carbon::now()));
        $checkExpiredDate = date('Y/m/d H:i:s', strtotime(Carbon::now()));
        $expiredDate = date('Y/m/d H:i:s', strtotime($checkExpiredDate . ' +1 day'));

        $encryptedEmail = $this->performCrypt(strtolower($email), 'e');
        // e for encrypt & d for decrypt
        $encryptedDate  = $this->performCrypt($expiredDate, 'e');
        // e for encrypt & d for decrypt

        $baseUrl = url('/api/v1/jobseeker/activate/'.$encryptedEmail.'/'.$encryptedDate.'/'. $registered_id.'?redirect='.$frontend_url);
        $data = [
            'name' =>$name,
            'jobseeker_furigana_name' => $jobseeker_furigana_name,
            'today' => $currentDate,
            'url' => $baseUrl,
            'expiredDate' => $expiredDate
        ];      
       
        Mail::to($email)->send(new JobseekerRegisterMail($data));      
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

    public function getcountry()
    {
        // $country = DB::select('select country_name from countries GROUP BY country_name');
        $country = Country::groupBy('country_name')
                    ->select('country_name')
                    ->get();
        return response()->json($country);

    }

    public function getCity($city_name)
    {
        $city_list = Country::where('country_name',$city_name)->get();
        return response()->json($city_list);
    }

    public function getFavouriteJob(Request $request) {
      
        $search_word = $request->input('search_word');
        $jobseeker_id = $this->getLoginJobseekerID();
        $jobseeker = Jobseeker::find($jobseeker_id);
        $favourite_job_arr = explode(',',$jobseeker->favourite_job_ids);
        // return $favourite_job_arr;
        $to_date =  date('Y-m-d', strtotime(Carbon::now().'+1 day'));
        $from_date = date('Y-m-d', strtotime($to_date . '-1 month'));
        $timezone = User::find(Auth::user()->id)->selectedTimeZone();
        
        $fav_query = DB::table('jobs as j')
                    ->join ('recruiters as r', 'r.id', 'j.recruiter_id')
                    ->join('countries as c','c.id','j.country_id')
                    ->whereIn('j.id',$favourite_job_arr)
                    ->where('j.record_status',1)
                    ->where('r.record_status',1)
                    ->where('j.job_post_status','post')
                    ->whereBetween('job_post_date',[$from_date, $to_date])
                    ->select(DB::raw("convert_tz(j.job_post_date,'+00:00','$timezone') as job_post_date"),'c.country_name','j.id as job_id','j.title','j.occupation_description', 'j.other_keywords', 
                    'j.job_location','r.logo');

       
        $favourite_job_list = $fav_query;
        $not_exit_job_id = $fav_query->pluck('job_id')->toArray();

        $diff_result = array_diff($favourite_job_arr,$not_exit_job_id);
        $favJobMessage = '';
      
        if(implode($diff_result) != null){   
            $update_fav = array_diff($favourite_job_arr,$diff_result);
            $jobseeker->favourite_job_ids = \implode(',',$update_fav);
            $jobseeker->update();
            $favJobMessage = 'job have delete!';
        }
       
        if ($search_word) {
            $favourite_job_list->where(function($query) use ($search_word) {
                $query->orwhere('j.occupation_description' ,'like', '%' . $search_word . '%');
                $query->orwhere('j.title' ,'like', '%' . $search_word . '%');
                $query->orwhere('j.job_location' ,'like', '%' . $search_word . '%');
                $query->orwhere('j.other_keywords' ,'like', '%' . $search_word . '%');
                $query->orwhere('j.job_number' ,'like', '%' . $search_word . '%');
                $query->orwhere('j.employment_types' ,'like', '%' . $search_word . '%');
                $query->orwhere('j.job_description' ,'like', '%' . $search_word . '%');
                $query->orwhere('j.qualification' ,'like', '%' . $search_word . '%');
                $query->orwhere('j.allowance' ,'like', '%' . $search_word . '%');
                $query->orwhere('j.message' ,'like', '%' . $search_word . '%');
                $query->orwhere('j.job_start_date' ,'like', '%' . $search_word . '%');
                $query->orWhere('c.country_name','like', '%' . $search_word . '%');
                
                
            });
        }

        if($request->input('search_status') == '0') {
            $favourite_job_list = $favourite_job_list->paginate(2);
        } else {
            $favourite_job_list = $favourite_job_list->get();
        }

        for($i=0; $i<count($favourite_job_list);$i++) {
            if($favourite_job_list[$i]->other_keywords != '') {
                $keywords_arr = explode(',',$favourite_job_list[$i]->other_keywords);
                $favourite_job_list[$i]->other_keywords = $keywords_arr;
            }
            $favourite_job_list[$i]->logo_url = generate_image_link($favourite_job_list[$i]->logo,"recruiters/images");
            $favourite_job_list[$i]->job_post_date = date('Y-m-d', strtotime($favourite_job_list[$i]->job_post_date));
            $favourite_job_list[$i]->job_expired_date = date('Y-m-d', strtotime($favourite_job_list[$i]->job_post_date. ' + 1 month'));
        }
        
       
     
        return response()->json(['favourite_job_list'=>$favourite_job_list,'favJobMessage'=>$favJobMessage]);
    }

    public function deleteFavouriteJob($job_id,Request $request) {

        $jobseeker_id = $this->getLoginJobseekerID();
        $jobseeker = Jobseeker::find($jobseeker_id);
        $tmp_arr = explode(',',$jobseeker->favourite_job_ids);
        $jobseeker->favourite_job_ids = '';

        for($i=0; $i<count($tmp_arr); $i++) {
            if($tmp_arr[$i] != $job_id) {
                if($jobseeker->favourite_job_ids) {
                    $jobseeker->favourite_job_ids .= ',' .$tmp_arr[$i];
                }
                else { // first time to insert to array
                    $jobseeker->favourite_job_ids .= $tmp_arr[$i]; 
                }
            }
        }
        $jobseeker->update();
    }

    /**
	 * @get jobseeker's recommended jobs
	 * @author Chhuani @ 2020/08/06
	 * @last_maintained Chhuani @ 2020/08/18
	 * @param  Request $request
	 * @return json
	 */
    public function getRecommentedJob(Request $request) {
        
        $jobseeker_id = $this->getLoginJobseekerID();
        $jobseeker = Jobseeker::find($jobseeker_id);
        $favourite_job_arr = explode(',',$jobseeker->favourite_job_ids);
        $timezone = User::find(Auth::user()->id)->selectedTimeZone();

        $desired_occupation = DB::table('desired_occupations as do')
                                ->where('jobseeker_id',$jobseeker_id)
                                ->select('do.occupation_id')
                                ->get();

        $to_date =  date('Y-m-d', strtotime(Carbon::now().'+1 day'));
        $from_date = date('Y-m-d', strtotime($to_date . '-1 month'));

        $tmp_occupation_id = [];
        // $tmp_occupation_id[] = $jobseeker->desired_occupation_id;
        for($i=0; $i<count($desired_occupation); $i++) {
            $tmp_occupation_id[] = $desired_occupation[$i]->occupation_id;
        }
        
        if($request->input('page') == 'favourite') {
                $recommended_job_list = DB::table('jobs as j')
                            ->join ('recruiters as r', 'r.id', 'j.recruiter_id')
                            ->join('countries as c','c.id','j.country_id')
                            ->whereNotIn('j.id',$favourite_job_arr)
                            ->whereIn('j.occupation_id',$tmp_occupation_id)
                            ->where('j.record_status',1)
                            ->where('r.record_status',1)
                            ->where('j.job_post_status','post')
                            ->whereBetween('j.job_post_date',[$from_date, $to_date])
                            ->select(DB::raw("convert_tz(j.job_post_date,'+00:00','$timezone') as job_post_date"),'c.country_name','j.id as job_id','j.title','j.occupation_description', 'j.other_keywords',
                            'j.job_location','r.logo')
                            ->get();
        }else{
                $recommended_job_list = DB::table('jobs as j')
                        ->join ('recruiters as r', 'r.id', 'j.recruiter_id')
                        ->whereIn('j.occupation_id',$tmp_occupation_id)
                        ->where('j.record_status',1)
                        ->where('r.record_status',1)
                        ->where('j.job_post_status','post')
                        ->whereBetween('j.job_post_date',[$from_date, $to_date])
                        ->select(DB::raw("convert_tz(j.job_post_date,'+00:00','$timezone') as job_post_date"),'j.id as job_id','j.title','j.occupation_description', 'j.other_keywords',
                        'j.job_location','r.logo')
                        ->get();
        }


        for($i=0; $i<count($recommended_job_list);$i++) {
            $recommended_job_list[$i]->logo_url = generate_image_link($recommended_job_list[$i]->logo,"recruiters/images");
            $recommended_job_list[$i]->job_post_date = date('Y-m-d', strtotime($recommended_job_list[$i]->job_post_date));
            $recommended_job_list[$i]->job_expired_date = date('Y-m-d', strtotime($recommended_job_list[$i]->job_post_date. ' + 1 month'));
            // to check again business logic (confrim login 18/08/20)
            if($request->input('page') != 'favourite') {
                for($j=0; $j<count($favourite_job_arr); $j++) { 
                    if($recommended_job_list[$i]->job_id == $favourite_job_arr[$j]) { // if it has been already favourite jobs
                        $recommended_job_list[$i]->disable = true;
                    break;
                    } else {
                        $recommended_job_list[$i]->disable = false;
                    }
                }
            }

            if($recommended_job_list[$i]->other_keywords != '') {
                $tmp_keyworks_arr = explode(',',$recommended_job_list[$i]->other_keywords);
                $recommended_job_list[$i]->other_keywords = $tmp_keyworks_arr;
            }
        }
        return response()->json(array("recommended_job_list"=>$recommended_job_list));
    }

    public function getPopularJob()
    {

         //popular jobs
         $jobseeker_id = $this->getLoginJobseekerID();
         $fav_id = DB::table('jobseekers')->where('id',$jobseeker_id)->select('favourite_job_ids')->value('favourite_job_ids');
         $fav_id_list = explode(',', $fav_id);
        //  $to_date =  date('Y-m-d', strtotime(Carbon::now()));
        //  $from_date = date('Y-m-d', strtotime($to_date . ' -1 month'));

         $to_date =  date('Y-m-d', strtotime(Carbon::now().'+1 day'));
         $from_date = date('Y-m-d', strtotime($to_date . '-1 month'));

        $recently_jobs = Jobseeker::where('id',$jobseeker_id)->select('recently_job_ids')->value('recently_job_ids');
        $recently_jobs_list = explode(",",$recently_jobs);
       
        if(sizeof($recently_jobs_list) > 4)
        {
            $recently_jobs_list = array_slice($recently_jobs_list,0,4);
        }
        

        $query = DB::table('jobs')->join ('recruiters as r', 'r.id', 'jobs.recruiter_id')
                                ->join('countries','countries.id','jobs.country_id')
                                ->select( DB::raw("DATE_FORMAT(jobs.job_post_date, '%Y-%m-%d') as job_post_date,DATE_FORMAT(DATE_ADD(jobs.job_post_date,INTERVAL 1 month),'%Y-%m-%d') as job_expired_date"),'jobs.id as job_id','jobs.other_keywords','jobs.title','jobs.occupation_description','r.logo','countries.country_name')
                                ->where('jobs.record_status',1)
                                ->where('r.record_status',1)
                                ->where('jobs.job_post_status','post')
                                ->whereBetween('jobs.job_post_date',[$from_date, $to_date]);
                            

         $diff_favjob_id =  array_diff($recently_jobs_list,$fav_id_list);

         $popular_job =  $query->whereIn('jobs.id',$diff_favjob_id)->get();

         for($i=0; $i<count($popular_job);$i++) {
             $popular_job[$i]->logo_url = generate_image_link($popular_job[$i]->logo,"recruiters/images");
 
             if($popular_job[$i]->other_keywords != '') {
                 $tmp_keyworks_arr = explode(',',$popular_job[$i]->other_keywords);
                 $popular_job[$i]->other_keywords = $tmp_keyworks_arr;
             }
         }

         return response()->json(array("popular_jobs"=>$popular_job));
         
    }

    /**
	 * @add to favourite job
	 * @author Chhuani @ 2020/08/06
	 * @last_maintained Chhuani @ 2020/08/07
	 * @param  Request $request
	 */
    public function addFavouriteJob($job_id,Request $request) {

        $jobseeker_id = $this->getLoginJobseekerID();
        $jobseeker = Jobseeker::find($jobseeker_id);

        if($jobseeker->favourite_job_ids == '') {
            $jobseeker->favourite_job_ids = $job_id;
        } else {
            $jobseeker->favourite_job_ids .= ','.$job_id;
        }
        $jobseeker->save(); 
    }

    public function deactivate(Request $request) {
        DB::beginTransaction();
        try {
            $user = auth()->user();

            $model = User::findOrFail($user->id); 
            $model->record_status = 3; // from 'active' to 'stop'
            $model->save();

            $jobseeker = Jobseeker::where(['user_id' => $user->id])->first();
            $jobseeker->record_status       = 3; // from 'active' to 'stop'
            $jobseeker->jobseeker_number = $jobseeker->jobseeker_number.'T';
            $jobseeker->favourite_job_ids   = NULL;
            $jobseeker->recently_job_ids    = NULL;
            $jobseeker->save();

            Scout::where('jobseeker_id', $jobseeker->id)
                    ->whereIn('scout_status', ['回答待ち', '興味あり', '期限切れ'])
                    ->delete();
            JobApply::where(['jobseeker_id' => $jobseeker->id, 'job_apply_status' => '検討中'])->delete();

            DB::commit();
            CustomLog::info("Deactivate" . $user->id);

            return $this->createResponse($user, 200);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->createErrorResponse($e->getMessage(), 500);
        }
    }

    /**
	 * @Change jobseeker's Password
	 * @author Chhuani @ 2020/08/06
	 * @last_maintained Chhuani @ 2020/08/07
	 * @param  Request $request
	 * @return response
	 */
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

    /**
     * @get jobseeker info with user id
     * @author Zayar Phone Naing @ 2020/08/4
     * @last_maintained @ 2020/08/4
     * @param request
     * @return response
     */
    public function getJobSeekerInfo(Request $request)
    {
        $jobseeker_info = Jobseeker::where('user_id', $request->id)->first();
        return response()->json($jobseeker_info);
    }

    /**
     * @change scout setting status of job seeker
     * @author Zayar Phone Naing @ 2020/08/4
     * @last_maintained @ 2020/08/4
     * @param request
     * @return response
     */
    public function changeScoutSettingStatus(Request $request)
    {
        $user_id = auth()->user()->id;
        Jobseeker::where('user_id', $user_id)->update(['scout_setting_status' => $request->scout_setting_status]);
        return $this->createResponse("Successfully Changed", 200);
    }

}
