<?php

namespace App\Http\Controllers\API\V1\Jobseeker;

use App\Http\Controllers\API\V1\BaseController;
use App\Http\Controllers\API\V1\RemoveFavouriteTrait;
use Illuminate\Http\Request;
use App\Model\JobApply;
use App\Model\Job;
use App\Model\Jobseeker;
use App\Mail\jobAppliedMail;
use App\Mail\jobAppliedMailAdmin;
use App\Mail\jobAppliedMailJobseeker;
use App\Model\Scout;
use App\User;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Mail\applyDeclineAdminMail;
use App\Mail\applyDeclineJobseekerMail;
use App\Mail\applyDeclineRecruiterMail;
class JobseekerJobApplyController extends BaseController
{
    use RemoveFavouriteTrait;
    public function jobapplied(Request $request,$jobid)
    {
    
        $id = $this->getLoginJobseekerID();
        
        try {            
           
            $jobapplied = new JobApply();
            $jobapplied->jobseeker_id = $id;
            $jobapplied->job_id = $jobid;
            $jobapplied->job_apply_status = '検討中';
            $jobapplied->save();

            $jobapplied->calculateManagementNumber();

            $result = DB::table('job_applies')->leftjoin('jobs','job_applies.job_id','=','jobs.id')
                                            ->leftjoin('recruiters','recruiters.id','=','jobs.recruiter_id')
                                            ->leftjoin('jobseekers','jobseekers.id','=','job_applies.jobseeker_id')
                                            ->where('jobs.id',$jobid)
                                            ->where('jobseekers.id',$id)
                                            ->select('jobs.application_address','jobs.occupation_description','jobs.employment_types','jobs.job_description','jobs.qualification','jobs.job_location','jobs.allowance','jobs.job_start_date','jobs.message','recruiters.id as recruiter_id','recruiters.email','job_applies.management_number','jobs.id','jobs.job_number','recruiters.recruiter_number','recruiters.recruiter_name','jobseekers.email as jobseekermail','jobseekers.jobseeker_name','jobseekers.jobseeker_number','jobseekers.jobseeker_furigana_name','jobs.title')
                                            ->first();
            
            $admin = User::where('role_id',1)->select('email','name','extra')->first();
            
            if($admin->extra != null && $admin->extra != '') {
                $extra = array_merge(array($admin->email),\explode(',',$admin->extra));
            }
            else{
                $extra = array($admin->email);
            }

            $sendmailjobseeker = [
                'email'=>$result->jobseekermail,
                'management_number'=>$result->management_number,
                'job_number'=>$result->job_number,
                'jobseeker_name'=>$result->jobseeker_name,
                'title'=>$result->title,
                'occupation_description' =>$result->occupation_description,
                'employment_types'=>$result->employment_types,
                'job_description'=>$result->job_description,
                'qualification'=>$result->qualification,
                'job_location'=>$result->job_location,
                'allowance'=>$result->allowance,
                'job_start_date'=>$result->job_start_date,
                'message'=>$result->message,
            ];

            // $this->sendMail( $result->email, $result->recruiter_name, $result->management_number ,$result->title ,$result->jobseeker_number, $result->jobseeker_name,$result->jobseeker_furigana_name);
            $sendmailrecruiter = [
                // 'email'=>$result->email,
                'email'=>$result->application_address,
                'recruiter_name'=>$result->recruiter_name,
                'management_number'=>$result->management_number,
                'title'=>$result->title,
                'jobseeker_number'=>$result->jobseeker_number,
                'jobseeker_name'=>$result->jobseeker_name,
                'jobseeker_furigana_name'=>$result->jobseeker_furigana_name,
                'job_number'=>$result->job_number,
                'occupation_description' =>$result->occupation_description,
                'employment_types'=>$result->employment_types,
                'job_description'=>$result->job_description,
                'qualification'=>$result->qualification,
                'job_location'=>$result->job_location,
                'allowance'=>$result->allowance,
                'job_start_date'=>$result->job_start_date,
                'message'=>$result->message,
            ];
            
            $sendMailAdminData = [
                'extra' => $extra,
                'email' => $admin->email,
                'name' => $admin->name,
                'management_number' => $result->management_number,
                'title' =>$result->title,
                'recruiter_number' =>$result->recruiter_number,
                'recruiter_name' =>$result->recruiter_name,
                'jobseeker_number'=>$result->jobseeker_number,
                'jobseeker_name'=>$result->jobseeker_name,
                'jobseeker_furigana_name'=>$result->jobseeker_furigana_name,
                'job_number'=>$result->job_number,
                'occupation_description' =>$result->occupation_description,
                'employment_types'=>$result->employment_types,
                'job_description'=>$result->job_description,
                'qualification'=>$result->qualification,
                'job_location'=>$result->job_location,
                'allowance'=>$result->allowance,
                'job_start_date'=>$result->job_start_date,
                'message'=>$result->message,
            ];
            
            $this->removeFavourites($result->recruiter_id, $id, $jobid);

            $this->sendMail($sendmailrecruiter);
            $this->sendMailadmin($sendMailAdminData);
            $this->sendMailJobseeker($sendmailjobseeker);

            DB::commit();
        } catch (\Exception $e) {            
            DB::rollback();
            return $this->createErrorResponse($e->getMessage(), 500);
        }
        
    
    }
    private function sendMailJobseeker($sendmailjobseeker) 
    {        
        $currentDate = date('Y/m/d', strtotime(Carbon::now()));
        $email = $sendmailjobseeker['email'];

        $data = [
            'jobseeker_name' =>$sendmailjobseeker['jobseeker_name'],
            'management_number'=>$sendmailjobseeker['management_number'],
            'job_number'=>$sendmailjobseeker['job_number'],
            'title'=>$sendmailjobseeker['title'],
            'occupation_description' =>$sendmailjobseeker['occupation_description'],
            'employment_types'=>$sendmailjobseeker['employment_types'],
            'job_description'=>$sendmailjobseeker['job_description'],
            'qualification'=>$sendmailjobseeker['qualification'],
            'job_location'=>$sendmailjobseeker['job_location'],
            'allowance'=>$sendmailjobseeker['allowance'],
            'job_start_date'=>$sendmailjobseeker['job_start_date'],
            'message'=>$sendmailjobseeker['message'],
        ];      
       
        Mail::to($email)->send(new jobAppliedMailJobseeker($data));
      
    }

    //jobapplied_mailsend_admin
    private function sendMailadmin($sendMailAdminData) 
    {
        
        $currentDate = date('Y/m/d', strtotime(Carbon::now()));
        $checkExpiredDate = date('Y/m/d H:i:s', strtotime(Carbon::now()));
        $email = $sendMailAdminData['email'];
        $extra_email = $sendMailAdminData['extra'];
        $data = [
            'adminname' =>$sendMailAdminData['name'],
            'management_number' =>$sendMailAdminData['management_number'],
            'jobtitle' =>$sendMailAdminData['title'],
            'recruiter_number' =>$sendMailAdminData['recruiter_number'],
            'recruiter_name' =>$sendMailAdminData['recruiter_name'],
            'jobseeker_number' =>$sendMailAdminData['jobseeker_number'],
            'jobseeker_name' =>$sendMailAdminData['jobseeker_name'],
            'jobseeker_furigana_name' =>$sendMailAdminData['jobseeker_furigana_name'],
            'job_number'=>$sendMailAdminData['job_number'],
            'occupation_description' =>$sendMailAdminData['occupation_description'],
            'employment_types'=>$sendMailAdminData['employment_types'],
            'job_description'=>$sendMailAdminData['job_description'],
            'qualification'=>$sendMailAdminData['qualification'],
            'job_location'=>$sendMailAdminData['job_location'],
            'allowance'=>$sendMailAdminData['allowance'],
            'job_start_date'=>$sendMailAdminData['job_start_date'],
            'message'=>$sendMailAdminData['message'],

        ];
       
        foreach ($extra_email as $k => $v) {
            Mail::to($v)->send(new jobAppliedMailAdmin($data));
        }
      
      
    }

    //jobapplied_mailsend_recruiter
    private function sendMail($sendmailrecruiter) 
    {
        
        $currentDate = date('Y/m/d', strtotime(Carbon::now()));
        $checkExpiredDate = date('Y/m/d H:i:s', strtotime(Carbon::now()));
        $email = $sendmailrecruiter['email'];
        
        $data = [
            'recruiter_name' =>$sendmailrecruiter['recruiter_name'],
            'management_number' =>$sendmailrecruiter['management_number'],
            'title' =>$sendmailrecruiter['title'],
            'jobseeker_number' =>$sendmailrecruiter['jobseeker_number'],
            'jobseeker_name' =>$sendmailrecruiter['jobseeker_name'],
            'jobseeker_furigana_name' =>$sendmailrecruiter['jobseeker_furigana_name'],
            'job_number'=>$sendmailrecruiter['job_number'],
            'occupation_description' =>$sendmailrecruiter['occupation_description'],
            'employment_types'=>$sendmailrecruiter['employment_types'],
            'job_description'=>$sendmailrecruiter['job_description'],
            'qualification'=>$sendmailrecruiter['qualification'],
            'job_location'=>$sendmailrecruiter['job_location'],
            'allowance'=>$sendmailrecruiter['allowance'],
            'job_start_date'=>$sendmailrecruiter['job_start_date'],
            'message'=>$sendmailrecruiter['message'],

        ];


        Mail::to($email)->send(new jobAppliedMail($data));
      
    }

    public function jobappliedlist($id)
    {       
    
        $loginId = $this->getLoginJobseekerID();

        $jobapplied = JobApply::select('id','job_apply_status')->where('jobseeker_id',$loginId)
                              ->where('job_id',$id)
                              ->orderBy('id','DESC')
                              ->get();
        
        $scout = Scout::select('id','scout_status')->where('jobseeker_id',$loginId)
                        ->where('job_id',$id)
                        ->orderBy('id','DESC')
                        ->get();
                           
        return $this->createResponse(array('jobapplied'=>$jobapplied,'scout'=>$scout), 200);
    }

    public function appliedList()
    {
		$job = $this->getappliesList()->paginate(6);
		 foreach ($job as $k => $v) {
            $v->other_keywords = explode(',',$v->other_keywords) ;
            $v->logo_url = generate_image_link($v->logo, 'recruiters/images');   
            $v->job_post_date = explode(' ', $v->job_post_date)[0]; 
        }
      
        return response()->json($job);
    }
 
    public function jobSeekerApplyChangeStatus($applyId, Request $request)
    {	
       try {
        $get_data = JobApply::join('jobs','jobs.id','=','job_applies.job_id')
                            ->join('recruiters','recruiters.id','=','jobs.recruiter_id')
                            ->join('jobseekers','jobseekers.id','=','job_applies.jobseeker_id')
                            ->where('job_applies.id',$applyId)
                            ->select('job_applies.management_number','jobs.job_number','jobs.title',
                                    'recruiters.recruiter_number','recruiters.recruiter_name','recruiters.email as recruiter_email',
                                    'jobseekers.jobseeker_number','jobseekers.jobseeker_name','jobseekers.jobseeker_furigana_name','jobseekers.email as jobseeker_email')
                            ->first();
        $data = [
            'management_number' => $get_data->management_number,
            'job_number' => $get_data->job_number,
            'recruiter_number' => $get_data->recruiter_number,
            'recruiter_name' => $get_data->recruiter_name,
            'jobseeker_number' =>  $get_data->jobseeker_number,
            'jobseeker_name' => $get_data->jobseeker_name,
            'jobseeker_furigana_name' => $get_data->jobseeker_furigana_name,
            'title' => $get_data->title,
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
            Mail::to($v)->send(new applyDeclineAdminMail($data));
        }
        //recruiter send mail
        if($get_data->recruiter_email != null && $get_data->recruiter_email != ''){
            Mail::to($get_data->recruiter_email)->send(new applyDeclineRecruiterMail($data));
        }
        //jobseeker send mail
        if($get_data->jobseeker_email != null && $get_data->jobseeker_email != ''){
            Mail::to($get_data->jobseeker_email)->send(new applyDeclineJobseekerMail($data));
        }
        //update stauts
        JobApply::where('id',$applyId)->update(['job_apply_status'=>config('config.job_apply.declined')]);
       } catch (\Throwable $th) {
        
        return $this->createErrorResponse($th->getMessage(), 500);
    
       }
	   $page = $request['page'] * 6;
       $job = $this->getappliesList()->take($page)->get();
       
	    foreach ($job as $k => $v) {
            $v->other_keywords = explode(',',$v->other_keywords) ;
            $v->logo_url = generate_image_link($v->logo, 'recruiters/images');   
            $v->job_post_date = explode(' ', $v->job_post_date)[0]; 
		}
       return response()->json($job);
	}
	
	public function getappliesList()
	{ 
        $timezone = User::find(Auth::user()->id)->selectedTimeZone();
		$job = JobApply::Join('jobs','jobs.id','=','job_applies.job_id')    
                         ->Join('countries','countries.id','=','jobs.country_id')
                         ->join('recruiters','recruiters.id','=','jobs.recruiter_id')
                         ->where('jobseeker_id',$this->getLoginJobseekerID())
                         ->select('recruiters.logo','job_applies.id as job_apply_id','job_applies.job_apply_status',
                                  'jobs.id  as job_id' ,'jobs.job_description',
                                  'jobs.title','jobs.job_location','countries.country_name',
								  'jobs.other_keywords','jobs.occupation_description',
								  'jobs.recruiter_id','job_applies.jobseeker_id',
                                  DB::raw("convert_tz(DATE_SUB(DATE_ADD(DATE_FORMAT(jobs.job_post_date, '%Y-%m-%d'), INTERVAL 1 month), INTERVAL 1 DAY),'+00:00','$timezone') as job_expired_date,DATE_FORMAT(convert_tz(jobs.job_post_date,'+00:00','$timezone'), '%Y-%m-%d') as job_post_date"))->orderBy('job_applies.created_at','DESC');
		return $job;
	}


}
