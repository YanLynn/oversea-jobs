<?php

namespace App\Http\Controllers\API\V1\Recruiter;
use App\Model\Job;
use App\Model\JobApply;
use App\Model\Scout;
use App\User;
use App\Model\Occupation;
use App\Model\Country;
use App\Model\EmploymentType;
use Carbon\Carbon;
use App\Http\Controllers\API\V1\BaseController;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Helpers\CustomLog;
use App\services\PublicSearch;
use App\Model\Recruiter;
use Illuminate\Support\Facades\Mail;
use App\Mail\NoticeSendAdminMail;
use App\Mail\NoticeSendRecruiterMail;
use DateTime;
use DateTimeZone;

class RecruiterJobController extends BaseController
{
    
    public function list(Request $request,$status,$check)
    {
        $time_now = Carbon::now()->subDays(29)->format('Y-m-d')." 00:00:00";
        Job::where('job_post_date','<',$time_now)->update(['record_status' => 3]);
        
        $timezone = User::find(Auth::user()->id)->selectedTimeZone();
        $filteredData = $_GET["fData"];
        $filteredData = json_decode($filteredData);
        $query = DB::table('jobs')
                ->select('jobs.*',DB::raw("DATE_FORMAT(convert_tz(DATE_SUB(DATE_ADD(jobs.job_post_date, INTERVAL 1 month), INTERVAL 1 DAY), '+00:00', '$timezone'),'%Y-%m-%d') as addOneMonth, DATE_FORMAT( job_post_date,'%Y-%m-%d') AS jobPostDate, (SELECT COUNT(job_applies.jobseeker_id)FROM job_applies WHERE job_applies.job_apply_status = '検討中' AND jobs.id = job_applies.job_id) as job_applies_count, (SELECT COUNT(scouts.jobseeker_id )FROM scouts WHERE scouts.scout_status = '興味あり' AND jobs.id = scouts.job_id)as scouts_count, DATE_FORMAT(convert_tz(job_post_date,'+00:00','$timezone'),'%Y-%m-%d') as post_date, convert_tz(jobs.job_update_date,'+00:00','$timezone') as updated, convert_tz(jobs.created_at,'+00:00','$timezone') as created"),'countries.country_name','occupations.occupation_name')
                ->leftJoin('job_applies', 'job_applies.job_id', '=', 'jobs.id')
                ->leftJoin('scouts', 'scouts.job_id', '=', 'jobs.id')
                ->join('countries','jobs.country_id','=','countries.id')
				->join('occupations','jobs.occupation_id','=','occupations.id')
                ->where('jobs.recruiter_id',$this->getLoginRecruiterID(),'and')
                ->where('jobs.job_post_status','=',$status,'and')
                ->where('jobs.record_status','!=',0)
                ->groupBy('jobs.id');
          
        if($check == 'scroll'){
            $projects = $query->orderBy('jobs.created_at', 'desc')->paginate(12);
        }
        else{
            if(sizeof($filteredData->job_list_status) > 0){
                $query->where(function($query) use ($filteredData) {
                    $query->wherein('jobs.record_status', $filteredData->job_list_status);    
                });
            }
            if($filteredData->job_num != null){
                $query->where(function($query) use ($filteredData) {
                    $query->orwhere('job_number', 'like', '%' . $filteredData->job_num . '%');
                });
            }
            if($filteredData->job_title != null){
                $query->where(function($query) use ($filteredData) {
                    $query->orWhere('title', 'like', '%' . $filteredData->job_title. '%');
                });
            }

            if($check == 'filter'){
                $projects = $query->orderBy('jobs.created_at', 'desc')->get();
            }
            else{
                $page = $request['page'];
                $count = $page * 12; 
                $projects =  $query->orderBy('jobs.created_at', 'desc')->take($count)->get();
            }            
        }

        return response()->json($projects,200);
    } 

    public function changeStatus(Request $request)
    {
       $id = $request->id;
       $status = $request->recordstatus;
       $changeStatus = Job::find($id);
       $changeStatus->record_status = $status;
       $changeStatus->save();
       return $this->createResponse(null, 200);

    }

    public function delete(Request $request)
    {

       
     if($request->status == 'post'){
        $relate = array('jobApply','scout');
        $get_jobApply_Scout_Count =  Job::with($relate)->whereHas('jobApply',function($q) use ($request){
             $q->whereIn('job_id',$request->checked_data);
        })->orWhere(function($q) use ($request){
            $q->whereHas('scout',function ($q) use ($request){
              $q->whereIn('job_id',$request->checked_data);
            });
        })->count();

        if($get_jobApply_Scout_Count > 0){
            $get_relate_job =  Job::with($relate)->whereHas('jobApply',function($q) use ($request){
                $q->whereIn('job_id',$request->checked_data);
           })->orWhere(function($q) use ($request){
               $q->whereHas('scout',function ($q) use ($request){
                 $q->whereIn('job_id',$request->checked_data);
               });
           })->get();

            $ids_in_transactions = [];
            foreach ($get_relate_job as $k => $v) {
                array_push($ids_in_transactions, $v->id);
            } 

            $diff_favjob_id =  array_diff($request->checked_data,$ids_in_transactions);

            //delete job
            Job::whereIn('id', $diff_favjob_id)->delete();
            CustomLog::info("Delete" . json_encode($diff_favjob_id));
            return $this->createResponse($ids_in_transactions, 200);

        
        }else{
            Job::whereIn('id', $request->checked_data)->delete();
            CustomLog::info("Delete" . json_encode($request));
            return $this->createResponse("Successfully Deleted", 200);
        }
        }else if($request->status == 'draft'){
                Job::whereIn('id', $request->checked_data)->delete();
                CustomLog::info("Delete" . json_encode($request));
                return $this->createResponse("Successfully Deleted", 200);
        }
       
    }
   
    public function create(Request $request)
    {
        if(Auth::user()->role_id == 1){
            $timezone = 'Asia/Tokyo';
        }
        else{
            $timezone = User::find(Auth::user()->id)->selectedTimeZoneVal();
        } 
       
        DB::beginTransaction();
        try {
            $job = new Job();
            $job->recruiter_id          = $this->getLoginRecruiterID();
            $job->recruiter_show_name = $request->input('recruiter_show_name');
            $job->title = $request->input('title');
            $job->occupation_description = $request->input('occupation_description');
            $job->job_description = $request->input('job_description');
            $job->qualification = $request->input('qualification');
            $job->allowance = $request->input('allowance');
            $job->job_start_date = $request->input('job_start_date');
            $job->job_location = $request->input('job_location');
            $job->application_address = $request->input('application_address');
            $job->message = $request->input('message');
            $job->country_id = $request->input('country_id');
            $job->occupation_id = $request->input('occupation_id');
            // Timezone change
            $job->job_post_date = implode(",", $request->input('job_post_date'));
            date_default_timezone_set($timezone);
            $job->job_post_date = new DateTime($job->job_post_date);
            $utc_time = new DateTimeZone('UTC');
            $job->job_post_date->setTimezone($utc_time);
            date_default_timezone_set('UTC');
            // End Timezone change
            $job->employment_types = implode(",", $request->input('employment_types'));
            $job->other_keywords = implode(",", $request->input('other_keywords'));
            $job->job_post_status = $request->input('job_post_status');
            $job->created_at = Carbon::now();
            $job->updated_at = Carbon::now();
            $job->save();
            $job->calculateJobNumber();
            DB::commit();

        }catch(\Exception $e) {
            DB::rollback();
            return $this->createErrorResponse($e->getMessage(), 500);

        }
      
        return $this->createResponse(['job' => $job],200);
    }

     public function getJobs($id)
    {
        if(Auth::user()->role_id == 1){
            $timezone = 'Asia/Tokyo';
            $timezoneVal = '+09:00';
        }
        else{
            $timezone = User::find(Auth::user()->id)->selectedTimeZoneVal();
            $timezoneVal = User::find(Auth::user()->id)->selectedTimeZone();
        } 
        
        // $job = Job::find($id);
        $job = Job::leftjoin('scouts','scouts.job_id','jobs.id')->where('jobs.id',$id)
        ->select('jobs.*','scouts.occupation_description as scout_occupation_description',
        'scouts.employment_types as scout_employment_types','scouts.job_location as scout_job_location', DB::raw("convert_tz(jobs.job_update_date,'+00:00','$timezoneVal') as updated, convert_tz(jobs.created_at,'+00:00','$timezoneVal') as created"))
        ->first();
        
        $job->scout_employment_types = explode(',',$job->scout_employment_types);

        $db_time = new DateTime($job->job_post_date);        
        $user_time_zone = new DateTimeZone($timezone);
        $db_time->setTimezone($user_time_zone);
        $job->job_post_date = $db_time->format('Y-m-d H:i:s');
        return $this->createResponse($job, 200);
    }

    public function update(Request $request, $id)
    {
        // $rec_user_id = Recruiter::find($request->input('recruiter_id'))->value('user_id');
        // $timezone = User::find($rec_user_id)->selectedTimeZoneVal();
        if(Auth::user()->role_id == 1){
            $timezone = 'Asia/Tokyo';
        }
        else{
            $timezone = User::find(Auth::user()->id)->selectedTimeZoneVal();
        }     

        DB::beginTransaction();
        try {
        // $status_filter = [
        //     config('config.scouts.unclaimed'),
        //     config('config.scouts.billed'),
        //     config('config.scouts.payment_confirmed')
        // ];
        // $job_with_count = Job::where('id', $id)->withCount([
        //     'jobapply' => function($query) use($status_filter) {
        //         $query->whereIn('job_apply_status', $status_filter);
        //     }, 'scout' => function($query) use($status_filter) {
        //         $query->whereIn('scout_status', $status_filter);
        //     }
        // ])->first();
        // // if this job is related with above status then TRUE nor FALSE
        // // true => create new job, false => update itself
        // $has_count = false;
        // if(!empty($job_with_count->jobapply_count) || !empty($job_with_count->scout_count)){
        //     return $this->createResponse(['job' => 'new'], 200);
        // }

        $job = Job::find($id);
        if($job->job_post_status == 'post')
        {
            $job->job_update_date = Carbon::now();
        }
        $job->recruiter_id = $request->input('recruiter_id');
        $job->recruiter_show_name = $request->input('recruiter_show_name');
        $job->title = $request->input('title');
        $job->occupation_description = $request->input('occupation_description');
        $job->job_description = $request->input('job_description');
        $job->qualification = $request->input('qualification');
        $job->allowance = $request->input('allowance');
        $job->job_start_date = $request->input('job_start_date');
        $job->job_location = $request->input('job_location');
        $job->application_address = $request->input('application_address');
        $job->message = $request->input('message');
        $job->country_id = $request->input('country_id');
        $job->occupation_id = $request->input('occupation_id');
        // $job->job_post_date = $request->input('job_post_date');
        // $job->job_post_date = implode(",", $request->input('job_post_date'));
        // Timezone change
        $job->job_post_date = implode(",", $request->input('job_post_date'));
        date_default_timezone_set($timezone);
        $job->job_post_date = new DateTime($job->job_post_date);
        $utc_time = new DateTimeZone('UTC');
        $job->job_post_date->setTimezone($utc_time);
        date_default_timezone_set('UTC');
        // End Timezone change
        $job->employment_types = implode(",", $request->input('employment_types'));
        $job->other_keywords = implode(",", $request->input('other_keywords'));
        $job->job_post_status = $request->input('job_post_status');
        $job->updated_at = Carbon::now();
        $job->update();
        
        DB::commit();
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return $this->createErrorResponse($e->getMessage(), 500);
        }
        return $this->createResponse(['job' => $job],200);
    }    
    
    public function getalldata()
    {
        $occupation = Occupation::all();
        $country=  Country::groupBy('country_name')->get();
        $employment= EmploymentType::select("*",DB::raw("false as checked"))->get(); 
        if(Auth::user()->role_id == 1) {
            $timezone = 'Asia/Tokyo';
        }
        else{
            $timezone = User::find(Auth::user()->id)->selectedTimeZoneVal();
        }
        
       
        return response()->json(array("occupation"=>$occupation,"country"=>$country,"employment"=>$employment,'GMT_Offset' => $timezone));
    }
    
    public function getJobApplyList(Request $request,$check)
    {
        $filteredData = $_GET["fData"];
        $filteredData = json_decode($filteredData);
       
        $columns = ['job_applies.id'];

        $recruiter_id = $this->getLoginRecruiterID();
        $timezone = User::find(Auth::user()->id)->selectedTimeZone();

        $query  = JobApply::join('jobs AS j', 'j.id', '=', 'job_applies.job_id')
        ->join('recruiters as r','r.id','=','j.recruiter_id')
                ->join('jobseekers AS js', 'js.id', '=', 'job_applies.jobseeker_id')
                ->join('countries','j.country_id','=','countries.id')
				->join('occupations','j.occupation_id','=','occupations.id')
                ->select(DB::raw("convert_tz(j.job_post_date,'+00:00','$timezone') as job_post_date, convert_tz(job_applies.job_apply_date,'+00:00','$timezone') as job_apply_date, convert_tz(j.job_update_date,'+00:00','$timezone') as updated, convert_tz(j.created_at,'+00:00','$timezone') as created"),'job_applies.id as jobapply_id','r.recruiter_number as recruiter_number',
                'r.recruiter_name','job_applies.management_number',
                'j.job_number', 'j.title','js.jobseeker_number as jobseeker_number','js.jobseeker_name','js.jobseeker_furigana_name',
                'r.registered_place_type',
                'job_applies.job_apply_status',
                'j.occupation_description', 
                'j.employment_types', 
				'j.job_description', 'j.qualification', 'j.job_location', 'j.allowance', 'j.id AS job_id',
				'job_applies.jobseeker_id','j.recruiter_id','j.other_keywords','j.recruiter_show_name','j.application_address','j.message','j.job_start_date','countries.country_name','occupations.occupation_name','job_applies.send_noti','job_applies.job_apply_date as testdate')
                ->where('r.id',$recruiter_id);

        if($check == 'scroll') {
            $projects = $query->orderBy('job_applies.created_at', 'desc')->paginate(12);
        } else {
            if(sizeof($filteredData->jobapply_status) > 0) {
                
                if(in_array('内定', $filteredData->jobapply_status)){
                    array_push($filteredData->jobapply_status, '請求済', '入金確認済', '内定未請求');
                }

                $query->wherein('job_applies.job_apply_status',$filteredData->jobapply_status);
            }

            if($filteredData->from_date != '' && $filteredData->to_date != '') {
                $query->whereBetween(DB::raw("DATE_FORMAT(convert_tz(job_applies.job_apply_date,'+00:00','$timezone'), '%Y-%m-%d')"), [$filteredData->from_date, $filteredData->to_date]);
            }
            if($filteredData->from_date != '' && $filteredData->to_date == '') {
                $query->where(DB::raw("DATE_FORMAT(convert_tz(job_applies.job_apply_date,'+00:00','$timezone'), '%Y-%m-%d')"), ">=",[$filteredData->from_date]);
            }
            if($filteredData->from_date == '' && $filteredData->to_date != '') {
                $query->where(DB::raw("DATE_FORMAT(convert_tz(job_applies.job_apply_date,'+00:00','$timezone'), '%Y-%m-%d')"), "<=",[$filteredData->to_date]);
            }
            
            $query->where(function($query) use ($filteredData) {
                    if($filteredData->job_title != '') {
                        $query->where('j.title' ,'like', '%' . $filteredData->job_title . '%'); 
                    }
                    if($filteredData->management_number != '') {
                        $query->where('job_applies.management_number' ,'like', '%' . $filteredData->management_number . '%'); 
                    }
                    if(sizeof($filteredData->jobapply_status) > 0) {
                        $query->wherein('job_applies.job_apply_status', $filteredData->jobapply_status);
                    }
            });
            if($check == 'filter'){
                $projects = $query->orderBy('job_applies.created_at', 'desc')->get();
            }
            else{
                $page = $request['page'];
                $count = $page * 12; 
                $projects =  $query->orderBy('job_applies.created_at', 'desc')->take($count)->get();
            }         
        }
        return response()->json($projects, 200);
    }
    
    /**
	 * @desc Get jobs of a recruiter which are not expired 
	 * @author Myo Ko Ko @ 2020/08/19
	 * @last_maintained Myo Ko Ko @ 2020/08/19
	 * @param  null
	 * @return json
	 */
	public function getScoutableJobs($jobseeker_id)
	{
		$recruiter_id = $this->getLoginRecruiterID();
        $jobs = Job::join('occupations', 'occupations.id', '=', 'jobs.occupation_id')
				->leftJoin('job_applies', function ($q) use ($jobseeker_id) {
					$q->on('job_applies.job_id', '=', 'jobs.id')
						->where('job_applies.jobseeker_id', '=', $jobseeker_id);
                })
                ->leftJoin('scouts', function ($q) use ($jobseeker_id) {
					$q->on('scouts.job_id', '=', 'jobs.id')
						->where('scouts.jobseeker_id', '=', $jobseeker_id);
				})
				->where('jobs.recruiter_id', $recruiter_id)
				->where('jobs.job_post_status', config('config.job_post.post'))
                ->whereIn('jobs.record_status',[config('config.job.private'),config('config.job.public')])
                // --Exclude jobs in scouts which are not declined
				->whereNotIn('jobs.id', function($query) use ($recruiter_id, $jobseeker_id) {
					$query->select('job_id')
                    ->from('scouts')
                    ->where('scouts.scout_status','!=',config('config.scouts.declined'))
					->where([
						'jobseeker_id' => $jobseeker_id,
                        'recruiter_id' => $recruiter_id,
					]);
                })
                // --Exclude jobs in job_applies which are not declined
                ->whereNotIn('jobs.id', function($query) use ($recruiter_id, $jobseeker_id) {
					$query->select('job_id')
					->from('job_applies')
					->where(['jobseeker_id' => $jobseeker_id])
					->where('job_apply_status', '!=', config('config.job_apply.declined'))
					->where('job_apply_status', '!=', config('config.job_apply.under_review'));
				})
                ->orderBy('jobs.created_at', 'DESC')
                ->groupBy('jobs.id')
				->get(['jobs.*', 'occupations.occupation_name', 'job_applies.id AS jobapply_id', 'job_applies.job_apply_status', 'scouts.scout_status'])
				->reject
				->isExpired()
				->values();
		return $this->createResponse($jobs);
    }
    

    //generated by Su Sandy (17.7.2020 Fri)
    //to add favourite jobs  (only login jobseeker)
    public function addJobSeekerFav($id)
    {
		
        $recruiterId = $this->getLoginRecruiterID();      
        
        $favId = Recruiter::where('id',$recruiterId)->select('favourite_jobseeker_ids')->value('favourite_jobseeker_ids');
        if($favId)
        {
            $favId = $favId.",".$id;
        }
        else{
            $favId = $id;
        }
        $jobseeker = Recruiter::where('id',$recruiterId)->update(['favourite_jobseeker_ids'=> $favId]);   
        CustomLog::info("Recruiter add job fav".$id); 
        return $jobseeker; 

    }

    public function removeJobSeekerFav($jobId)
    {
        $recruiterId = $this->getLoginRecruiterID();        
    
        $favId = Recruiter::where('id',$recruiterId)->select('favourite_jobseeker_ids')->value('favourite_jobseeker_ids');
        $favId = explode(',',$favId);

        foreach (array_keys($favId, $jobId) as $key) {
            unset($favId[$key]);
        }

        $favId = implode(',',$favId);
        $jobseeker = Recruiter::where('id',$recruiterId)->update(['favourite_jobseeker_ids'=> $favId]);  
        CustomLog::info("jobseeker remove job fav".$jobId);
        return $jobseeker;

    }

    public function sendNoticeAcceptance(Request $request)
    {
        $data = array(
            'recruiter_name' => $request->recruiter_name,
            'recruiter_number' => $request->recruiter_number,
			'job_number' => $request->job_number,
			'title' => $request->title,
			'management_number' => $request->management_number,
			'jobseeker_number' => $request->jobseeker_number,
			'jobseeker_name' => $request->jobseeker_name,
			'jobseeker_furigana_name' => $request->jobseeker_furigana_name,
			'notice_comment' => $request->notice_comment,
			'accept_decline' => $request->accept_decline
        );
        
		$admin_mail = User::where('role_id','=',1)->select('email','extra')->first();
        $job_email = Job::select('application_address as job_email')->where('id','=',$request->job_id)->first();
        
        if($admin_mail->extra != null && $admin_mail->extra != '') {
            $extra = array_merge(array($admin_mail->email),\explode(',',$admin_mail->extra));
        }
        else{
            $extra = array($admin_mail->email);
        }

        foreach ($extra as $k => $v) {
            Mail::to($v)->send(new NoticeSendAdminMail($data));
        }
		
        Mail::to($job_email->job_email)->send(new NoticeSendRecruiterMail($data));
        
        if($request->type == 'scout'){
            $record = Scout::findOrFail($request->id);
        }
        else{
            $record = JobApply::findOrFail($request->id);
        }
        $record->send_noti = 1;
        $record->update();
    }
 
}
