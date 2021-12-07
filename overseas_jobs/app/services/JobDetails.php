<?php

namespace App\services;
use App\Model\Job;
use App\Model\Jobseeker;
use DB;
use App\Http\Controllers\API\V1\BaseController;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\User;

class JobDetails {
    public static function Details($request,$check,$selectedJobId)
    {

        $twoWeekMinus = date('Y-m-d H:m:s', strtotime(' -1 month ',strtotime(Carbon::now())));
        $currentDate = date('Y-m-d  H:m:s',strtotime(Carbon::now()));
        
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
	
        $details = DB::table('jobs')->join('countries','countries.id','=','jobs.country_id')
        ->join('recruiters','recruiters.id','=','jobs.recruiter_id')
                                ->join('occupations','occupations.id','=','jobs.occupation_id')
                                ->select(  DB::raw("convert_tz(DATE_FORMAT(DATE_ADD(jobs.job_post_date,INTERVAL 1 month),'%Y-%m-%d'),'+00:00','$timezone') as job_expired_date,'' as fav,convert_tz(jobs.job_post_date,'+00:00','$timezone') as job_post_date, convert_tz(jobs.updated_at,'+00:00','$timezone') as updated, convert_tz(jobs.created_at,'+00:00','$timezone') as created"),'recruiters.recruiter_number','recruiters.id','recruiters.incharge_name','recruiters.incharge_photo','recruiters.recruiter_name','recruiters.representative_name','recruiters.business_description','recruiters.establishment_date','recruiters.logo','recruiters.video','occupations.occupation_name','jobs.id as jobid','jobs.employment_types','jobs.job_description','jobs.job_location','jobs.job_start_date','jobs.other_keywords','jobs.occupation_description','jobs.title','jobs.occupation_id','countries.country_name')
                                ->where('jobs.id',$selectedJobId)
                                ->first();

        $details->other_keywords = explode(',', $details->other_keywords);
        $details->establishment_date = explode('-', $details->establishment_date);  
        $details->job_post_date = explode(' ', $details->job_post_date)[0]; 
       
        foreach($favJobList as $fav)
        {
            if($fav == $details->jobid){
                $details->fav = 'fav';
            }
        
        }        

        $query =  DB::table('jobs')->join('occupations','occupations.id','=','jobs.occupation_id')
                                            ->join('recruiters', 'recruiters.id', '=', 'jobs.recruiter_id')
                                            ->where('jobs.job_post_status','post')
                                            ->where('jobs.record_status',1)
                                            ->where('occupations.id',$details->occupation_id)
                                            ->where('jobs.id','!=',$selectedJobId)
                                            ->where('job_post_date',">=",$twoWeekMinus)
						                    ->where('job_post_date',"<=",$currentDate)
                                            ->select('recruiters.logo',DB::raw("convert_tz(DATE_FORMAT(DATE_ADD(jobs.job_post_date, INTERVAL 1 month), '%Y-%m-%d'),'+00:00','$timezone') as job_expired_date, '' as fav, convert_tz(jobs.job_post_date,'+00:00','$timezone') as job_post_date"),'jobs.id','jobs.other_keywords','jobs.occupation_description','jobs.title','jobs.job_location')
                                            ->orderBy('jobs.job_post_date', 'desc');
                                            


        if($check == 'addFav' || $check == 'removeFav'){

            $page = $request['page'];
            $take = $page * 4;
            $related_jobs = $query->take($take)->get();
           
        }
        else{
            $related_jobs = $query->paginate(4);
		}
	

		
	
        foreach($related_jobs as $jobs)
        {
	   	
            $jobs->other_keywords = explode(',', $jobs->other_keywords);
            $jobs->job_post_date = explode(' ', $jobs->job_post_date)[0];  
            $jobs->logo_url = generate_image_link($jobs->logo, 'recruiters/images');  
            if(isset($favJobList)) 
            {
                foreach($favJobList as $fav)
                {
				  
                    if($fav == $jobs->id){
                        $jobs->fav = 'fav';
					}
				
                }
            }  
        }
       

        //   $details[0]->incharge_photo_url = generate_image_link($details[0]->incharge_photo, 'recruiters/images');
          $details->logo_url = generate_image_link($details->logo, 'recruiters/images');  
          $details->video = generate_youtube_embed_link($details->video);    
          return array('details'=>$details,'related_jobs'=>$related_jobs,'favCount'=>$favCount); 

        
    }
    
}