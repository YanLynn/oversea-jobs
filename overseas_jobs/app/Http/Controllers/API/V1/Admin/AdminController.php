<?php 

namespace App\Http\Controllers\API\V1\Admin;
use App\User;
use App\Http\Controllers\API\V1\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Model\Recruiter;
use App\Model\Jobseeker;
use App\Model\Job;
use App\Model\Scout;
use App\Model\JobApply;
use Carbon\Carbon;
use DB;
use App\services\LoginAttempts;
use App\Model\PaymentTransaction;
//┌───────────────────────────────────────────────
//│ Controller Name　： AdminController
//│ Created By　：Myo Ko Ko
//│ Created Date　：2020/06/23
//│ Description : Actions for admin related tasks
//├───────────────────────────────────────────────
//│ Copyright notice　：Copyright (C) 2020 Management Partners Myanmar Co.,Ltd.
//├───────────────────────────────────────────────
//│ Repair history　　：2020/06/23　-> Created New
//└───────────────────────────────────────────────
class AdminController extends BaseController {

	/**
	 * @desc Admin change password
	 * @author Myo Ko Ko @ 2020/06/23
	 * @last_maintained Myo Ko KO @ 2020/06/23
	 * @param  Request $request
	 * @return Response
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

		return $this->createResponse($user, 200);
	}

	/**
	 * @desc Transform laravel validation errors into less keyed array
	 * @author Myo Ko Ko @ 2020/06/23
	 * @last_maintained 
	 * @param  Illuminate\Support\MessageBag $messageBag
	 * @return Array
	 */
	private function transformValidationErrorMsg($messageBag)
	{
		return array_map(function($x) { return $x[0] ?? ''; }, $messageBag->toArray());
	}
	public function getCount()
	{
		$recruiter = Recruiter::where('record_status',1)->count();
		$jobseeker = Jobseeker::where('record_status',1)->count();
		$scout	   = Scout::where('scout_status','内定未請求')->count();
		$jobApply  = JobApply::where('job_apply_status','内定未請求')->count();
		$getJobApplyDate  = Job::select('job_post_date')->get();

		$twoWeekMinus = date('Y-m-d H:m:s', strtotime('-1 month',strtotime(Carbon::now())));
		$currentDate = date('Y-m-d  H:m:s',strtotime(Carbon::now()));
		$getcount = Job::where('record_status',1)
						->where('job_post_status','post')
						->select('job_post_date')
						->where('job_post_date',">=",$twoWeekMinus)
						->where('job_post_date',"<=",$currentDate)
						->count();

		$payment_count = PaymentTransaction::select(DB::raw("(CASE payment_transactions.payment_job_type WHEN 'job-apply' THEN job_applies.job_apply_status ELSE scouts.scout_status END) AS payment_count"))
									->leftjoin('scouts','scouts.id','=','payment_transactions.scoutid_or_applyid')
									->leftjoin('job_applies','job_applies.id','=','payment_transactions.scoutid_or_applyid')
									->whereRaw("(CASE payment_transactions.payment_job_type WHEN 'job-apply' THEN job_applies.job_apply_status = '請求済' ELSE scouts.scout_status = '請求済' END)")
									->count();


		return response()->json(['recruiter' =>$recruiter,
								 'jobseeker' => $jobseeker,
								 'job'=>$getcount,
								 'scout'=>$scout,
								 'jobApply'=>$jobApply,
								 'payment_count'=>$payment_count,
								 ]);
    }
    
    public function clearLoginLocked(Request $request)
	{
		$clean_result = LoginAttempts::clearLoginLocked($request->email.$request->role_id.$request->userid);
		return $this->createResponse($clean_result, 200);
	}
}