<?php

namespace App\Http\Controllers\API\V1\Recruiter;

use App\Http\Controllers\API\V1\BaseController;
use Illuminate\Http\Request;
use App\Model\Scout;
use App\Model\JobApply;
use App\Model\Recruiter;
use App\Model\Jobseeker;
use DB;

use App\Model\Job;
use App\Model\UploadedFile;

use App\Model\Country;
use App\Model\Language;
use App\Model\Industry;
use App\Model\Position;
use App\Model\Occupation;
use App\Model\IndustryHistory;
use App\Model\EducationOverseas;
use App\Model\ExperienceOverseas;
use App\Model\JobSeekerLanguages;

use App\Model\EducationHistories;
use App\Model\ExperienceHistories;
use App\Model\DesiredIndustries;
use App\Model\DesiredOccupation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ScoutSuccessMail;
use App\Http\Controllers\API\V1\RemoveFavouriteTrait;
use App\User;
use Auth;
use DateTimeZone;
use DateTime;

class RecruiterScoutController extends BaseController
{
	use RemoveFavouriteTrait;
    public function scoutedList(Request $request,$check)
    {
		$timezone = User::find(Auth::user()->id)->selectedTimeZone();
        $filteredData = $_GET["fData"];        
        $filteredData = json_decode($filteredData);
        $query = Scout::join('recruiters','recruiters.id','=','scouts.recruiter_id')
                            ->join('jobseekers','jobseekers.id','=','scouts.jobseeker_id')
							->join('jobs','jobs.id','=','scouts.job_id')
							->join('countries','jobs.country_id','=','countries.id')
							->join('occupations','jobs.occupation_id','=','occupations.id')
                            ->where('scouts.recruiter_id',$this->getLoginRecruiterID())
							->select(DB::raw("convert_tz(jobs.job_post_date,'+00:00','$timezone') as job_post_date, convert_tz(scouts.scouted_date,'+00:00','$timezone') as scouted_date, convert_tz(jobs.job_update_date,'+00:00','$timezone') as updated, convert_tz(jobs.created_at,'+00:00','$timezone') as created"),'scouts.scout_status','jobseekers.jobseeker_furigana_name', 'jobseekers.jobseeker_name', 'jobseekers.jobseeker_number', 'scouts.management_number', 'scouts.id as scout_id', 'recruiters.registered_place_type', 'recruiters.recruiter_number', 'recruiters.recruiter_name', 
							'jobs.job_number', 'jobs.title', 'jobs.occupation_description', 'jobs.employment_types', 'jobs.job_description', 'jobs.qualification', 'jobs.job_location', 'jobs.allowance', 'jobs.id AS job_id','scouts.recruiter_id','scouts.jobseeker_id',
							'jobs.job_start_date','jobs.message','jobs.application_address','jobs.recruiter_show_name','jobs.other_keywords',
							'countries.country_name','occupations.occupation_name','scouts.send_noti',
						    'scouts.occupation_description as scout_occupation_description','scouts.job_location as scout_job_location','scouts.employment_types as scout_employment_types');
        
        if($check == 'scroll'){
		
			$projects = $query->orderBy('scouts.created_at', 'desc')->paginate(12);
			foreach ($projects as $proj) {
				 $proj->scout_employment_types = explode(',',$proj->scout_employment_types);
			}
			

        }
        else{
            if(sizeof($filteredData->scout_status) > 0) {
                
                // for ($i=0; $i < count($filteredData->scout_status); $i++) {      
                //     if($filteredData->scout_status[$i] == '内定'){
                //         $filteredData->scout_status[$i] = preg_replace('/内定/', $filteredData->scout_status[$i],'内定未請求',1); 
                //     }
                // }
                if(in_array('内定', $filteredData->scout_status)){
                    array_push($filteredData->scout_status, '請求済', '入金確認済', '内定未請求');
                }

                $query->wherein('scouts.scout_status',$filteredData->scout_status);
			}
             
            if($filteredData->from_date != '' && $filteredData->to_date != '') {
                $query->whereBetween(DB::raw("DATE_FORMAT(convert_tz(scouts.scouted_date,'+00:00','$timezone'), '%Y-%m-%d')"), [$filteredData->from_date, $filteredData->to_date]);
                // $query->whereBetween(DB::raw("DATE_FORMAT(scouts.scouted_date, '%Y-%m-%d')"), [$filteredData->from_date, $filteredData->to_date]);
            }
            if($filteredData->from_date != '' && $filteredData->to_date == '') {
                $query->where(DB::raw("DATE_FORMAT(convert_tz(scouts.scouted_date,'+00:00','$timezone'), '%Y-%m-%d')"), ">=",[$filteredData->from_date]);
            }
            if($filteredData->from_date == '' && $filteredData->to_date != '') {
                $query->where(DB::raw("DATE_FORMAT(convert_tz(scouts.scouted_date,'+00:00','$timezone'), '%Y-%m-%d')"), "<=",[$filteredData->to_date]);
            }
            $query->where(function($query) use ($filteredData) {
                $query->where('jobs.job_number', 'like', '%' . $filteredData->job_num . '%')
                        ->where('jobs.title', 'like', '%' . $filteredData->job_title. '%')
                        ->where('jobseekers.jobseeker_number', 'like', '%' . $filteredData->jobseeker_num. '%')
						->where('jobseekers.jobseeker_name', 'like', '%' . $filteredData->jobseeker_name. '%')
						->where('scouts.management_number', 'like', '%' . $filteredData->management_number. '%');
            });

            if($check == 'filter'){
                $projects = $query->orderBy('scouts.created_at', 'desc')->get();
            }
            else{
                $page = $request['page'];
                $count = $page * 12; 
                $projects =  $query->orderBy('scouts.created_at', 'desc')->take($count)->get();
            }            
            
        }
         
        return response()->json($projects,200);
    }

    /**
	 * @desc Get a scout detail with some jobseeker info
	 * @author Myo Ko Ko @ 2020/08/21
	 * @last_maintained Myo Ko Ko @ 2020/08/21
	 * @param  int $scout_id
	 * @return json
	 */
	public function jobseekerDetail($type,int $transaction_id)
	{
		if($type == 'scout'){
			$query = Scout::join('jobseekers','jobseekers.id','=','scouts.jobseeker_id')				->where('scouts.recruiter_id', $this->getLoginRecruiterID())
					->where('scouts.id', $transaction_id)
					->select('scouts.*','scouts.scout_status as transaction_status','jobseekers.jobseeker_name','jobseekers.jobseeker_furigana_name','jobseekers.jobseeker_number','jobseekers.record_status as jobseeker_record_status')
					->first();
		}
		else{
			$query = JobApply::join('jobseekers','jobseekers.id','=','job_applies.jobseeker_id')		
					->where('job_applies.id', $transaction_id)
					->select('job_applies.*','job_applies.job_apply_status as transaction_status','jobseekers.jobseeker_name','jobseekers.jobseeker_furigana_name','jobseekers.jobseeker_number','jobseekers.record_status as jobseeker_record_status')
					->first();
		}
		
		if (!$query) {
			return $this->createErrorResponse("Scout not found", 404);
        }
        $timezone = User::find(Auth::user()->id)->selectedTimeZone();
		// --Uploaded File List
		$file_types = [
			config('config.uploaded_file_type.cv-en'),
			config('config.uploaded_file_type.cv-jp'),
			config('config.uploaded_file_type.cv-other'),
			config('config.uploaded_file_type.exp-en'),
			config('config.uploaded_file_type.exp-jp'),
			config('config.uploaded_file_type.exp-other'),			
		];
        $files = UploadedFile::where('related_user_id', $query->jobseeker_id)
                ->select('*',DB::raw("convert_tz(updated_at,'+00:00','$timezone') as user_time"))
				->whereIn('file_type', $file_types)
				->where('user_type', config('config.uploaded_file_user_type.jobseeker'))
				->get();
		$query->jobseeker_files = $files;
		return $this->createResponse($query);
	}

	public function downloadCv(int $uploaded_file_id)
	{
		$uploaded_file = UploadedFile::findOrFail($uploaded_file_id);
		$file = './uploads/jobseekers/cvs/'.$uploaded_file->url;
		return response()->file($file);
		// return response()->file(public_path($file));
	}

	/**
	 * @desc Jobseeker information focus on scout setting 
	 * @author Myo Ko Ko @ 2020/08/20
	 * @last_maintained Myo Ko Ko @ 2020/08/20
	 * @param  int $jobseeker_id
	 * @return json
	 */
	public function isJobseekerScoutable(int $jobseeker_id)
	{
		$jobseeker = Jobseeker::where('id', $jobseeker_id)
					->first(['face_image','jobseeker_nick_name','scout_setting_status','desired_occupation_id']);
		if (!$jobseeker) {
			return $this->createErrorResponse('Jobseeker not found', 404);
		}
		return $this->createResponse($jobseeker);
	}

	/**
	 * @desc Jobseeker profile for scout detail
	 * @author Myo Ko Ko @ 2020/09/04
	 * @last_maintained Myo Ko Ko @ 2020/09/08
	 * @param  int $jobseeker_id
	 * @return application/json
	 */
	public function getJobseekerProfile(int $jobseeker_id)
	{
		$related_images = Jobseeker::with('related_images')->findorFail($jobseeker_id);

		// --These are Meta data
		// $countries = Country::groupBy('continent_name')->get();
		$countries = Country::all();
		$languages = Language::all();
		$industries = Industry::all();
		$occupation_list = Occupation::all();
        $positions = Position::all();
        
        $scout_status_check = Scout::join('jobseekers', 'jobseekers.id', '=', 'scouts.jobseeker_id')
        ->join('recruiters', 'recruiters.id', '=', 'scouts.recruiter_id')
        ->where('scouts.recruiter_id', $this->getLoginRecruiterID())
        ->where('scouts.jobseeker_id', $jobseeker_id)
        ->select('scouts.scout_status')->get();

        $apply_status_check = JobApply::join('jobseekers', 'jobseekers.id', '=', 'job_applies.jobseeker_id')
        ->join('jobs', 'jobs.id', '=', 'job_applies.job_id')
        ->join('recruiters', 'recruiters.id', '=', 'jobs.recruiter_id')
        ->where('jobs.recruiter_id', $this->getLoginRecruiterID())
        ->where('job_applies.jobseeker_id', $jobseeker_id)
        ->count();

		$profile = Jobseeker::leftjoin('countries','countries.id','=','jobseekers.country_id')
						->leftjoin('jobseeker_languages','jobseeker_languages.jobseeker_id','=','jobseekers.id')
						->leftjoin('languages','languages.id','=','jobseeker_languages.language_id')
						->where('jobseekers.id', $jobseeker_id)
						->select('jobseekers.*','countries.id','countries.city_name','countries.continent_name','countries.country_name','jobseeker_languages.language_id',
						'languages.language_name','jobseeker_languages.language_level')
						->first();
		// $profile->dob = explode('-', $profile->dob);



		$selfIntroDetails = Jobseeker::leftjoin('countries as ca','ca.id','=','jobseekers.desired_location_1')
									->leftjoin('countries as cb','cb.id','=','jobseekers.desired_location_2')
									->leftjoin('countries as cc','cc.id','=','jobseekers.desired_location_3')
									->leftjoin('desired_occupations','desired_occupations.jobseeker_id','=','jobseekers.id')
									->leftjoin('occupations','desired_occupations.occupation_id','=','occupations.id')
									->select(DB::raw("'' as language_level,group_concat(occupations.occupation_name) as occupation_name"),
									         'ca.country_name as desired_location_1','cb.country_name as desired_location_2','cc.country_name as desired_location_3','jobseekers.jobseeker_number','jobseekers.video','jobseekers.self_pr','jobseekers.num_of_experienced_companies',
											 'jobseekers.last_annual_income','jobseekers.last_currency')
									->groupBy('desired_occupations.jobseeker_id')
									->where('jobseekers.id',$jobseeker_id)
									->first();
								

		$language = Jobseeker::select(DB::raw("group_concat(languages.language_name,'|',IFNULL(jobseeker_languages.language_level, '')) as language_level"),'jobseekers.id')
									->leftJoin('jobseeker_languages','jobseeker_languages.jobseeker_id','=','jobseekers.id')
									->leftJoin('languages','languages.id','=','jobseeker_languages.language_id')
								    ->where('jobseekers.id',$jobseeker_id)
									->groupBy('jobseeker_languages.jobseeker_id')
									->get(); 



		if ($selfIntroDetails->video) {
			$selfIntroDetails->video = generate_youtube_embed_link($selfIntroDetails->video);
		}
		$carrerdetails = [
			'num_of_experienced_companies' => $selfIntroDetails->num_of_experienced_companies,
			'last_annual_income' => $selfIntroDetails->last_annual_income,
			'last_currency' => $selfIntroDetails->last_currency,
		];

		$file_names = ['photo_1','photo_2','photo_3','photo_4'];
		$file_hashed = [];
		foreach ($file_names as $file) {
			$hash_name = md5($selfIntroDetails->jobseeker_number.'_'.$file);
			array_push($file_hashed,$hash_name);
		}
				
		$educations  = EducationHistories::where('jobseeker_id', $jobseeker_id)->get();
        $experiences = ExperienceHistories::leftjoin('employment_types','employment_types.id','=','experience_histories.employment_type_id')
        ->leftjoin('positions','positions.id','=','experience_histories.position_id')
        ->select('experience_histories.*','positions.position_name','employment_types.employment_type_name')
        ->where('jobseeker_id', $jobseeker_id)->where('experience_histories.private_status','!=',1)->get();

        $industry_histories = IndustryHistory::where('jobseeker_id', $jobseeker_id)->get();
        $education_overseas = EducationOverseas::where('jobseeker_id', $jobseeker_id)->get();
        $working_overseas = ExperienceOverseas::where('jobseeker_id', $jobseeker_id)->get();
        $languages_levels = JobSeekerLanguages::where('jobseeker_id', $jobseeker_id)->get();
		
		// --Desire conditions
		$jobseeker = Jobseeker::leftjoin('countries as ca','ca.id','=','jobseekers.desired_location_1')
		->leftjoin('countries as cb','cb.id','=','jobseekers.desired_location_2')
		->leftjoin('countries as cc','cc.id','=','jobseekers.desired_location_3')
		->leftjoin('occupations','occupations.id','jobseekers.desired_occupation_id')
								->select(DB::raw("'' as industry_name,'' as occupation_name"),'jobseekers.*','occupations.occupation_name as js_occupation_name','ca.country_name as desired_locations_1','cb.country_name as desired_locations_2','cc.country_name as desired_locations_3')
								->where('jobseekers.id', $jobseeker_id)
								->first();
								
		$industry_details = DesiredIndustries::join('industries','industries.id','desired_industries.industry_id')
							->select('industries.industry_name')
							->where('desired_industries.jobseeker_id', $jobseeker_id)
							->get();
		$jobseeker->industry_name = $industry_details;

		$occupation_details = DesiredOccupation::join('occupations','occupations.id','desired_occupations.occupation_id')
								->select('occupations.occupation_name')
								->where('desired_occupations.jobseeker_id', $jobseeker_id)
								->get();
		$jobseeker->occupation_name = $occupation_details;

		return $this->createResponse([
			'selfIntro' => $related_images,
			'selfIntroDetails' => $selfIntroDetails,
			'hashedFile' => $file_hashed,
			'carrers' => $carrerdetails,
			'educations' => $educations,
			'experiences' => $experiences,
			'desired_condition' => $jobseeker,
			'profile' => $profile,
			'countries' => $countries,
			'languages' => $languages,
            'industry_histories' => $industry_histories,
            'education_overseas' => $education_overseas,
            'working_overseas' => $working_overseas,
            'languages_levels' => $languages_levels,
			'industries' => $industries,
			'occupation_list' => $occupation_list,
			'positions' => $positions,
			'language' => $language,
			'scout_status_check' => $scout_status_check,
			'apply_status_check' => $apply_status_check,
		]);
	}

	/**
	 * @desc Add a new jobseeker into scout
	 * @author Myo Ko Ko @ 2020/08/19
	 * @last_maintained Myo Ko Ko @ 2020/09/24
	 * @param  Request $request
	 * @return json
	 */
	public function scoutJobseeker(Request $request)
	{
		DB::beginTransaction();
		try {
			$recruiter = Recruiter::findOrFail($this->getLoginRecruiterID());
			$jobseeker = Jobseeker::findOrFail($request->input('jobseeker_id'));

			// --Create scout with waiting status
			$scout = Scout::create([
				'recruiter_id' => $this->getLoginRecruiterID(),
				'jobseeker_id' => $request->input('jobseeker_id'),
				'job_id' => $request->input('job_id'),
				'scout_status' => config('config.scouts.waiting'),
				'scouted_date' => Carbon::now(),
				'occupation_description' => $request->input('occupation_description'),
				'job_location' => $request->input('job_location'),
				'employment_types' => implode(",", $request->input('employment_types')),
			]);
            $scout->calculateManagementNumber();
            
            $job = Job::join('scouts', 'jobs.id', 'scouts.job_id')->select('jobs.*','scouts.occupation_description as scout_occupation_description','scouts.employment_types as scout_employment_types','scouts.job_location as scout_job_location')->where('scouts.id',$scout->id)->first();

			// --Send mail to Jobseeker
			$data = [
				'domain' => (request()->secure() ? 'https://' : 'http://').(request()->getHttpHost()),
				'user_id' => $jobseeker->user_id,
				'job_description'=>$job->job_description,
				'qualification'=>$job->qualification,
				'allowance'=>$job->allowance,
				'job_start_date'=>$job->job_start_date,
				'message'=>$job->message,
				'job_title' => $job->title,
				'job_number' => $job->job_number,
				'occupation_description' => $job->occupation_description,
				'employment_types' => $job->employment_types,
				'job_location' => $job->job_location,
				'jobseeker_number' => $jobseeker->jobseeker_number,
				'jobseeker_name' => $jobseeker->jobseeker_name,
				'recruiter_number' => $recruiter->recruiter_number,
				'recruiter_name' => $recruiter->recruiter_name,
				'management_number' => $scout->management_number,
				'scout_occupation_description' => $job->scout_occupation_description,
				'scout_employment_types' => $job->scout_employment_types,
				'scout_job_location' => $job->scout_job_location,
            ];
            
			Mail::to($jobseeker->email)->send(new ScoutSuccessMail($data));
			$this->removeFavourites($recruiter->id, $jobseeker->id, $job->id);
			DB::commit();
			
		} catch (\Exception $e) {
			DB::rollback();
			return $this->createErrorResponse($e->getMessage());
		}
		return $this->createResponse($scout);
	}

}
 