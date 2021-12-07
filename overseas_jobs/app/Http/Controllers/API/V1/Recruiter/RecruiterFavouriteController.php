<?php

namespace App\Http\Controllers\API\V1\Recruiter;

use DB;
use Auth;
use App\Model\Jobseeker;
use App\Model\Recruiter;
use App\Helpers\CustomLog;
use Illuminate\Http\Request;
use App\Http\Controllers\API\V1\BaseController;
class RecruiterFavouriteController extends BaseController
{
    public function getFavJobseeker($status,Request $request)
    {  
   
        $fav = json_decode($_GET["fav"]);
        $getFavList = Recruiter::select('favourite_jobseeker_ids')->where('user_id',Auth::user()->id)->value('favourite_jobseeker_ids');
        $jobseekerID = \explode(',',$getFavList);
        $recruiter = Recruiter::find($this->getLoginRecruiterID());

        $fav_query = Jobseeker::leftJoin('jobseeker_languages','jobseeker_languages.jobseeker_id','=','jobseekers.id')
                                ->leftJoin('languages','languages.id','=','jobseeker_languages.language_id')
                                ->leftjoin('countries as ca','ca.id','=','jobseekers.desired_location_1')
                                ->leftjoin('countries as cb','cb.id','=','jobseekers.desired_location_2')
                                ->leftjoin('countries as cc','cc.id','=','jobseekers.desired_location_3')
                                ->leftjoin('desired_occupations','jobseekers.id','=','desired_occupations.jobseeker_id')
                                ->leftjoin('occupations','occupations.id','=','desired_occupations.occupation_id')
                                ->whereIn('jobseekers.id',$jobseekerID)
                                ->where('jobseekers.record_status',1)->where('jobseekers.scout_setting_status',1)
                                ->select('ca.country_name as desired_location_1','cb.country_name as desired_location_2','cc.country_name as desired_location_3','jobseekers.jobseeker_nick_name','jobseekers.face_image_private_status','jobseekers.id','languages.language_name','jobseekers.face_image','jobseekers.desired_occupation_status');
                             

        $language = Jobseeker::select(DB::raw("group_concat(languages.language_name,'|',IFNULL(jobseeker_languages.language_level, '')) as language_level"),'jobseekers.id')
                                ->leftJoin('jobseeker_languages','jobseeker_languages.jobseeker_id','=','jobseekers.id')
                                ->leftJoin('languages','languages.id','=','jobseeker_languages.language_id')
                                ->whereIn('jobseekers.id',$jobseekerID)
                                ->where('jobseekers.record_status',1)->where('jobseekers.scout_setting_status',1)
                                ->groupBy('jobseeker_languages.jobseeker_id')
                                ->get(); 
 
       
         $occupation = Jobseeker::select(DB::raw("group_concat(occupations.occupation_name) as occupation_name"),"jobseekers.id")
                                 ->leftjoin('desired_occupations','jobseekers.id','=','desired_occupations.jobseeker_id')
                                 ->leftjoin('occupations','occupations.id','=','desired_occupations.occupation_id')
                                 ->whereIn('jobseekers.id',$jobseekerID)
                                 ->where('jobseekers.record_status',1)->where('jobseekers.scout_setting_status',1)
                                 ->groupBy('desired_occupations.jobseeker_id')
                                 ->get();  
        
        $getjobseeker = $fav_query;
        $not_exit_job_id = $fav_query->pluck('jobseekers.id')->toArray();
        $diff_result = array_diff($jobseekerID,$not_exit_job_id);
        $fav_jobseeker_message = '';
            if(implode($diff_result) != null){   
                $update_fav = array_diff($jobseekerID,$diff_result);
                $recruiter->favourite_jobseeker_ids = \implode(',',$update_fav);
                $recruiter->update();
                $fav_jobseeker_message = 'jobseeker have been delete!';
            }
                                    
        if($fav->keyword != null)
        {
            $keyword = $fav->keyword;
            $getjobseeker->where(function ($q) use ($keyword)
            {
                $q->where('languages.language_name','like','%'.$keyword.'%')
                    ->orWhere('occupations.occupation_name','like','%'.$keyword.'%')
                    ->orWhere('jobseekers.self_pr','like','%'.$keyword.'%')
					->orWhere('jobseeker_languages.language_level','like','%'.$keyword.'%')															
                    ->orWhere('ca.country_name','like','%'.$keyword.'%')
                    ->orWhere('cb.country_name','like','%'.$keyword.'%')	
                    ->orWhere('cc.country_name','like','%'.$keyword.'%');						
            });
			$getJobseekerList = $getjobseeker->groupBy('jobseekers.id')->get();  		
		}
		else if($fav->keyword == null){
		
			if($status == 'delete')
			{
               
				$page = $request['page'];
				$count = $page * 9; 
				$getJobseekerList =  $getjobseeker->groupBy('jobseekers.id')->take($count)->get();
			}
			else if($status == 'scroll')
			{	
			
				$getJobseekerList =  $getjobseeker->groupBy('jobseekers.id')->paginate(9);
				
			}
			else if($status == 'filter'){
			
				$getJobseekerList =  $getjobseeker->groupBy('jobseekers.id')->get();
			}
		}
		// return $getJobseekerList;
	  return response()->json(['fav_jobseeker_message'=>$fav_jobseeker_message,'getJobseekerList'=>$getJobseekerList,'occupation'=>$occupation,'language'=>$language],200);
	  
	 
    }
    public function removeFavJobseeker($id)
    {

		$getFavList = Recruiter::select('favourite_jobseeker_ids')->where('user_id',Auth::user()->id)->value('favourite_jobseeker_ids');
		$jobseekerID = \explode(',',$getFavList);
		
        foreach (array_keys($jobseekerID, $id) as $key) {
                unset($jobseekerID[$key]);
        }
		$favID = \implode(',',$jobseekerID);
	
        $upDateRecruiter = Recruiter::where('user_id',Auth::user()->id)->update(['favourite_jobseeker_ids'=>$favID]);
        CustomLog::info("reruiter Delete fav ID ".$id);
        return $this->createResponse($upDateRecruiter);

	}
	
	/**
     * @desc Add a jobseeker into recruiter favourite
     * @author Myo Ko Ko @ 2020/08/20
     * @last_maintained Myo Ko Ko @ 2020/08/20
     * @param  int $jobseeker_id
     * @return json
     */
    public function addFavJobSeeker(int $jobseeker_id)
    {
        $recruiter_id = $this->getLoginRecruiterID();
        $recruiter = Recruiter::findOrFail($recruiter_id);
        $ids = array_filter(explode(",", $recruiter->favourite_jobseeker_ids));
        if (!in_array($jobseeker_id, $ids)) {
            array_push($ids, $jobseeker_id);
            $recruiter->favourite_jobseeker_ids = implode(",", $ids);
            $recruiter->save();
        }
        return $this->createResponse($recruiter);
    }
}
