<?php

namespace App\Http\Controllers\API\V1\Jobseeker;

use App\Http\Controllers\API\V1\BaseController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;
use App\Model\Scout;
use App\Model\Job;
use App\Model\Jobseeker;
use App\Model\Recruiter;
use Auth;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\JobseekerRegisterMail;
use App\Helpers\CustomLog;
use App\Mail\ScoutInterestedMail;
use App\Mail\ScoutInterestedRecruiterMail;
use App\Mail\ScoutInterestedJobseekerMail;
use App\Mail\scoutDeclineAdminMail;
use App\Mail\scoutDeclineJobseekerMail;
use App\Mail\scoutDeclineRecruiterMail;
class JobseekerScoutController extends BaseController
{
	public function scoutedList(int $userId, Request $request)
	{
		$jobseekerid = $this->getLoginJobseekerID();
		$timezone = User::find(Auth::user()->id)->selectedTimeZone();

		$scouts = DB::table('scouts AS s')
					->join('jobseekers AS js', 's.jobseeker_id', '=', 'js.id')
					->join('recruiters AS r', 's.recruiter_id', '=', 'r.id')
					->join('jobs AS j', 's.job_id', '=', 'j.id')
					->where('jobseeker_id', $jobseekerid)
					->select(DB::raw("convert_tz(s.scouted_date,'+00:00','$timezone') as user_scouted_date"),'s.*', 'j.recruiter_show_name', 'r.recruiter_nick_name', 'r.recruiter_name', 'r.incharge_photo', 'r.logo', 'j.title','s.occupation_description', 's.job_location', 's.employment_types')
					->orderBy('s.created_at','DESC')
					->paginate(5);

		$unread_count = DB::table('scouts AS s')
					->join('jobseekers AS js', 's.jobseeker_id', '=', 'js.id')
					->where(['jobseeker_id' => $jobseekerid, 'read_job' => FALSE, 'scout_status' => config('config.scouts.waiting')])					
					->count();

		$scouts->getCollection()->transform(function ($model) {
			$model->incharge_photo_url = generate_image_link($model->incharge_photo, "recruiters/images");			
			return $model;
		});

		$data = collect(['unread_count' => $unread_count]);

		$result = $data->merge($scouts);

		return response()->json($result, 200);
	}

	public function makeAction(Request $request) 
	{
		// return $request;
		DB::beginTransaction();
		try {
			$jobseeker = Jobseeker::where('user_id', Auth::user()->id)->firstOrFail();
			$scout = Scout::where([
				'id' => $request->input('scoutId'), 
				'jobseeker_id' => $jobseeker->id
			])->firstOrFail();
			
			if($request->input('action') == 'favourite'){
				$scout->scout_status = config('config.scouts.interested');
				$scout->save();
				// --Send email to admin
				$recruiter = Recruiter::findOrFail($scout->recruiter_id);
                // $job = Job::findOrFail($scout->job_id);
                $job = Job::join('scouts', 'jobs.id', 'scouts.job_id')
                            ->select('jobs.*','scouts.occupation_description as scout_occupation_description','scouts.employment_types as scout_employment_types','scouts.job_location as scout_job_location')
                            ->where('scouts.id',$scout->id)
                            ->first();
                
				$jobseekers = Jobseeker::where(['id'=>$jobseeker->id])->select('email')->first();
				$admin = User::where('role_id',1)->select('email','name','extra')->first();
				if($admin->extra != null && $admin->extra != '') {
					$extra = array_merge(array($admin->email),\explode(',',$admin->extra));
				}
				else{
					$extra = array($admin->email);
				}				

				$data = [
					'admin_name' => $admin->name,
					'job_number' => $job->job_number,
					'title' => $job->title,
					'occupation_description' =>$job->occupation_description,
					'scout_occupation_description' =>$job->scout_occupation_description,
					'employment_types'=>$job->employment_types,
					'scout_employment_types'=>$job->scout_employment_types,
					'job_description'=>$job->job_description,
					'qualification'=>$job->qualification,
					'job_location'=>$job->job_location,
					'scout_job_location'=>$job->scout_job_location,
					'allowance'=>$job->allowance,
					'job_start_date'=>$job->job_start_date,
					'message'=>$job->message,
					'jobseeker_number' => $jobseeker->jobseeker_number,
					'jobseeker_name' => $jobseeker->jobseeker_name,
					'jobseeker_furigana_name' => $jobseeker->jobseeker_furigana_name,
					'recruiter_number' => $recruiter->recruiter_number,
					'recruiter_name' => $recruiter->recruiter_name,
					'management_number' => $scout->management_number,
					
				];
				foreach ($extra as $k => $v) {
					Mail::to($v)->send(new ScoutInterestedMail($data));
				}
				//Mail::to($admin->email)->send(new ScoutInterestedMail($data));

				// --Send email to recruiter
				$recruiter_data = [
					'recruiter_name' => $recruiter->recruiter_name,
					'job_number' => $job->job_number,
					'title' => $job->title,
					'occupation_description' =>$job->occupation_description,
					'scout_occupation_description' =>$job->scout_occupation_description,
					'employment_types'=>$job->employment_types,
					'scout_employment_types'=>$job->scout_employment_types,
					'job_description'=>$job->job_description,
					'qualification'=>$job->qualification,
					'job_location'=>$job->job_location,
					'scout_job_location'=>$job->scout_job_location,
					'allowance'=>$job->allowance,
					'job_start_date'=>$job->job_start_date,
					'message'=>$job->message,
					'jobseeker_number' => $jobseeker->jobseeker_number,
					'jobseeker_name' => $jobseeker->jobseeker_name,
					'jobseeker_furigana_name' => $jobseeker->jobseeker_furigana_name,
					'management_number' => $scout->management_number,
					
				];
				Mail::to($job->application_address)->send(new ScoutInterestedRecruiterMail($data));

				// --Send email to jobseeker
				$jobseeker_data = [
					'job_number' => $job->job_number,
					'title' => $job->title,
					'occupation_description' =>$job->occupation_description,
					'scout_occupation_description' =>$job->scout_occupation_description,
					'employment_types'=>$job->employment_types,
					'scout_employment_types'=>$job->scout_employment_types,
					'job_description'=>$job->job_description,
					'qualification'=>$job->qualification,
					'job_location'=>$job->job_location,
					'scout_job_location'=>$job->scout_job_location,
					'allowance'=>$job->allowance,
					'job_start_date'=>$job->job_start_date,
					'message'=>$job->message,
					'jobseeker_number' => $jobseeker->jobseeker_number,
					'jobseeker_name' => $jobseeker->jobseeker_name,
					'jobseeker_furigana_name' => $jobseeker->jobseeker_furigana_name,
					'management_number' => $scout->management_number,
				];
				Mail::to($jobseekers->email)->send(new ScoutInterestedJobseekerMail($data));
			}
			// else if( $request->input('action') == 'decline'){

			// }
			else{
				
				try {
					$get_data = DB::table('scouts AS s')
					->join('jobseekers AS js', 's.jobseeker_id', '=', 'js.id')
					->join('recruiters AS r', 's.recruiter_id', '=', 'r.id')
					->join('jobs AS j', 's.job_id', '=', 'j.id')
					->where('s.id', $request->input('scoutId'))
					->select('s.management_number', 'j.*', 's.occupation_description as scout_occupation_description', 's.employment_types as scout_employment_types', 's.job_location as scout_job_location', 'r.recruiter_number', 'r.recruiter_name','r.email as recruiter_email','js.email as jobseeker_email','js.jobseeker_number', 'js.jobseeker_name', 'js.jobseeker_furigana_name')
					->first();

					$data = [
						'management_number' => $get_data->management_number,
						'job_number' => $get_data->job_number,
						'title' => $get_data->title,
						'occupation_description' =>$get_data->occupation_description,
						'scout_occupation_description' =>$get_data->scout_occupation_description,
						'employment_types'=>$get_data->employment_types,
						'scout_employment_types'=>$get_data->scout_employment_types,
						'job_description'=>$get_data->job_description,
						'qualification'=>$get_data->qualification,
						'job_location'=>$get_data->job_location,
						'scout_job_location'=>$get_data->scout_job_location,
						'allowance'=>$get_data->allowance,
						'job_start_date'=>$get_data->job_start_date,
						'message'=>$get_data->message,
						'recruiter_number' => $get_data->recruiter_number,
						'recruiter_name' => $get_data->recruiter_name,
						'jobseeker_number' =>  $get_data->jobseeker_number,
						'jobseeker_name' => $get_data->jobseeker_name,
						'jobseeker_furigana_name' => $get_data->jobseeker_furigana_name,
					];

					//admin send mail
					$admin = User::where('role_id',1)->select('email','name','extra')->first();
					if($admin->extra != null && $admin->extra != '') {
						$extra = array_merge(array($admin->email),\explode(',',$admin->extra));
					}
					else{
						$extra = array($admin->email);
					}	
					foreach ($extra as $k => $v) {
						Mail::to($v)->send(new scoutDeclineAdminMail($data));
					}
					//recruiter send mail
					if($get_data->recruiter_email != null && $get_data->recruiter_email != ''){
						Mail::to($get_data->recruiter_email)->send(new scoutDeclineRecruiterMail($data));
					}
					//jobseeker send mail
					if($get_data->jobseeker_email != null && $get_data->jobseeker_email != ''){
						Mail::to($get_data->jobseeker_email)->send(new scoutDeclineJobseekerMail($data));
					}

					$scout->scout_status = config('config.scouts.declined');
					$scout->save();

				} catch (\Throwable $th) {
					return $this->createErrorResponse($th->getMessage(), 500);
				}
					
			}		
			
			CustomLog::info("ChangeStatus ".$scout->id);
			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
			return $this->createErrorResponse($e->getMessage(), 500);
		}	

		return $this->createResponse($scout);
	}

	public function getScout($scoutId)
	{
		$timezone = User::find(Auth::user()->id)->selectedTimeZone();
		$scout = DB::table('scouts AS s')
					->join('jobseekers AS js', 's.jobseeker_id', '=', 'js.id')
					->join('recruiters AS r', 's.recruiter_id', '=', 'r.id')
					->join('jobs AS j', 's.job_id', '=', 'j.id')
					->where('s.id', $scoutId)
					->select(DB::raw("convert_tz(s.scouted_date,'+00:00','$timezone') as user_scouted_date"),'s.*','j.recruiter_show_name', 'r.recruiter_nick_name', 'r.recruiter_name', 'r.incharge_photo', 'r.logo', 'j.title', 's.job_location', 's.employment_types','r.record_status as r_record_status','s.occupation_description')
					->first();		
		if (!$scout) 
			return $this->createErrorResponse("Scout information not found", 404);
		
		// --Update read_job of scout
		if (!$scout->read_job) {
			Scout::where('id', $scoutId)->update(['read_job' => TRUE,]);
		}

		$scout->incharge_photo_url = generate_image_link($scout->incharge_photo, 'recruiters/images');
		return $this->createResponse($scout);
	}
}