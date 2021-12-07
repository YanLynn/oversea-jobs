<?php

namespace App\Http\Controllers\API\V1\Universal;
use App\Model\Job;
use App\User;
use App\Model\Occupation;
use App\Model\Country;
use App\Model\Recruiter;
use Carbon\Carbon;
use DB;
use App\Model\Jobseeker;    
use App\Http\Controllers\API\V1\BaseController;
use Illuminate\Http\Request;
use Auth;
use App\services\JobDetails;
class JobController extends BaseController
{
    
    public function getJobsAll($request,$isfav)
    {
        $favJobList='';
        $twoWeekMinus = date('Y-m-d', strtotime('-1 month',strtotime(Carbon::now())));
        $currentDate = date('Y-m-d', strtotime(Carbon::now()));
        
        $result = DB::table('jobs')->join('recruiters','recruiters.id','=','jobs.recruiter_id')
                                 ->where('jobs.job_post_status','post')->where('jobs.record_status',1)
                                 ->where(DB::raw("DATE_FORMAT(job_post_date, '%Y-%m-%d')"),">=",$twoWeekMinus)
                                 ->where(DB::raw("DATE_FORMAT(job_post_date, '%Y-%m-%d')"),"<=",$currentDate)
                                 ->select('recruiters.logo','jobs.title', 'jobs.occupation_description','jobs.job_description','jobs.id as job_id','jobs.job_location','jobs.other_keywords','jobs.job_post_date')
                                 ->addSelect(DB::raw("'' as fav,'' as job_expired_date"))
                                 ->orderBy('jobs.job_post_date', 'desc');
                                //  ->paginate(4);

            if($isfav == 'addfav'){
                $page = $request['page'] * 4;
                $test = $result->orderby('jobs.job_post_date','desc')->take($page)->get();
            }


        if(Auth::user())
        {
            $jobseekerId = BaseController::getLoginJobseekerID();
            $favJobId = Jobseeker::where('id',$jobseekerId)->select('favourite_job_ids')->value('favourite_job_ids');
            $favJobList = explode(',',$favJobId);
        }

        foreach($result as $res) 
        {
                $res->other_keywords = explode(",",$res->other_keywords);
                $res->job_post_date = explode(" ",$res->job_post_date)[0];
                $res->job_expired_date = explode(" ",$res->job_expired_date . ' + 1 month')[0];
                $res->logo_url = generate_image_link($res->logo, 'recruiters/images');    
                if($favJobList) 
                {
                    foreach($favJobList as $fav)
                    {
                        if($fav == $res->job_id){
                            $res->fav = 'fav';
                        }
                    }
                }       
        }
        
        return $this->createResponse($result,200);
    }
    
    public function getjob($id)
    {
       
        try{
            $favJobList = [];$favCount = 0;                                   
            if(Auth::user())
            {
                $jobseekerId = BaseController::getLoginJobseekerID();
                $favJobId = Jobseeker::where('id',$jobseekerId)->select('favourite_job_ids')->value('favourite_job_ids');
                if($favJobId)
                {
                    $favJobList = explode(',',$favJobId);
                    $favCount = count($favJobList);
                }
                $timezone = User::find(Auth::user()->id)->selectedTimeZone();
            }
            else{
                $timezone = '+09:00';
            }
            
            $details = DB::table('jobs')->join('recruiters','recruiters.id','=','jobs.recruiter_id')
                                        ->join('countries','countries.id','=','jobs.country_id')
                                        ->join('occupations','occupations.id','=','jobs.occupation_id')
                                        ->select( DB::raw("convert_tz(DATE_FORMAT(DATE_ADD(jobs.job_post_date,INTERVAL 1 month),'%Y-%m-%d'),'+00:00','$timezone') as job_expired_date,'' as fav, convert_tz(jobs.job_post_date,'+00:00','$timezone') as job_post_date, convert_tz(jobs.job_update_date,'+00:00','$timezone') as updated, convert_tz(jobs.created_at,'+00:00','$timezone') as created"),'recruiters.recruiter_number','recruiters.id','recruiters.record_status','recruiters.incharge_name','recruiters.incharge_photo','recruiters.recruiter_name','recruiters.recruiter_nick_name','recruiters.representative_name','recruiters.num_of_employees','recruiters.business_description','recruiters.company_pr','recruiters.establishment_date','recruiters.logo','recruiters.video','occupations.occupation_name','jobs.id as jobid','jobs.employment_types','jobs.job_description','jobs.qualification','jobs.job_location','jobs.allowance','jobs.job_start_date','jobs.other_keywords','jobs.occupation_description','jobs.title','jobs.job_number','jobs.occupation_id','countries.country_name','jobs.recruiter_show_name','jobs.message','recruiters.record_status')
                                        ->where('jobs.id',$id)->first();

            foreach($favJobList as $fav)
            {
                if($fav == $details->jobid){
                    $details->fav = 'fav';
                }
            
            }                            
            
             
             $details->other_keywords = explode(',', $details->other_keywords);
            //  $details->establishment_date = explode('-', $details->establishment_date);
             $details->job_post_date = explode(' ', $details->job_post_date)[0];   

             $related_files = DB::table('uploaded_files')->join('recruiters','recruiters.id','=','uploaded_files.related_user_id')
                                            ->where('uploaded_files.related_user_id',$details->id)
                                            ->where('uploaded_files.file_type','photo')
                                            ->where('uploaded_files.user_type','recruiter')
                                            ->select('uploaded_files.url','recruiters.record_status')
                                            ->get();

            $details->video = generate_youtube_embed_link($details->video);
            $details->incharge_photo_url = generate_image_link($details->incharge_photo, 'recruiters/images');
			$details->logo_url = generate_image_link($details->logo, 'recruiters/images');       

        }
        catch(\Exception $e){
            return $this->createErrorResponse($e->getMessage(),500); 
        }

      

        return $this->createResponse(array('details'=>$details,'related_files'=>$related_files),200);       
    }

    public function getJobDetails(Request $request,$selectedJobId)
    {
        $result = JobDetails::Details($request,null,$selectedJobId);
        return $this->createResponse($result,200);
      
    }
}
