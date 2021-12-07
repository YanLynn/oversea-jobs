<?php 

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;

use App\Model\Recruiter;
use App\Model\Jobseeker;

use App\Helpers\CustomLog;

trait RemoveFavouriteTrait 
{
	public function removeFavourites($recruiter_id, $jobseeker_id, $job_id)
	{
		$recruiter = Recruiter::findOrFail($recruiter_id);
		$jobseeker = Jobseeker::findOrFail($jobseeker_id);
		
		// --Remove recruiter favourite jobseekers
		$jids = \explode(',',$recruiter->favourite_jobseeker_ids);	
		foreach (array_keys($jids, $jobseeker_id) as $key) {
			unset($jids[$key]);
		}
		$new = \implode(',',$jids);
		$recruiter->favourite_jobseeker_ids = $new;
		$recruiter->save();
		CustomLog::info("reruiter Delete fav ID ".$recruiter->id);
		
		// --Remove jobseeker favourite jobs
		$jids = \explode(',',$jobseeker->favourite_job_ids);	
		foreach (array_keys($jids, $job_id) as $key) {
			unset($jids[$key]);
		}
		$new = \implode(',',$jids);
		$jobseeker->favourite_job_ids = $new;
		$jobseeker->save();
		CustomLog::info("jobseeker delete fav job id ".$jobseeker->id);
	}
}
