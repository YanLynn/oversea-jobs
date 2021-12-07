<?php

namespace App\Http\Controllers\API\V1\Jobseeker;

use App\Http\Controllers\API\V1\BaseController;
use Illuminate\Http\Request;
use App\Model\Position;
use App\Model\Jobseeker;
use App\Model\Recruiter;
use App\Model\EmploymentType;
use App\Helpers\CustomLog;
use App\Helpers\functions;
use App\Model\UploadedFile;
use App\Model\Country;
use App\Model\Language;
use App\Model\JobSeekerLanguages;
use App\Model\ExperienceHistories;
use App\Model\EducationHistories;
use Carbon\Carbon;
use App\Model\Scout;
use App\Model\Industry;
use App\Model\Occupation;
use App\Model\DesiredIndustry;
use App\Model\DesiredOccupation;
use App\Model\IndustryHistory;
use App\Model\EducationOverseas;
use App\Model\ExperienceOverseas;
use App\User;
use Auth;
use Image;
use DB;

class JobseekerProfileController extends BaseController
{
    /**
	 * @author Thuzar @ 2020/07/23
	 */
    public function requiredList()
    {		
        $iso_list =  config('config.iso_code');
        $positions = Position::all();
        $employment_types = EmploymentType::all();
        $data = array("positions"=>$positions, "employment_types"=>$employment_types,"iso_list"=>$iso_list);

        $del_edu_oversea = "delete from education_overseas where country_id is null and period is null and purpose is null";
        DB::delete($del_edu_oversea);

        $del_exp_oversea = "delete from experience_overseas where country_id is null and period is null and position_id is null";
        DB::delete($del_exp_oversea);

        $del_edu = "delete from education_histories where school_name is null and subject is null and degree is null and from_year is null and from_month is null and to_year is null and to_month is null and education_status is null";
	
        DB::delete($del_edu);
        
        $del_exp = "delete from experience_histories where position_id=0 and employment_type_id=0 and job_location is null and main_duty is null and from_year is null and from_month is null and to_year is null and to_month is null";
		
		DB::delete($del_exp);
		
		$del_industry_his = "delete from industry_histories where industry_id is null and occupation_keyword_id is null and position_id is null and experience_year is null";
		
        DB::delete($del_industry_his);
        
        $del_js_lang = "delete from jobseeker_languages where language_id is null and language_level is null";
		
		DB::delete($del_js_lang);

        return $this->createResponse($data,200);
    }

    //generated by zinko
	//14.8.2020(fri)
    public function getBasicInfo(Request $request)
    {
        $id = $request['jobseekerid'] ?? $this->getLoginJobseekerID();
        $profile = Jobseeker::leftjoin('countries','countries.id','=','jobseekers.country_id')
                           ->leftjoin('jobseeker_languages','jobseeker_languages.jobseeker_id','=','jobseekers.id')
                           ->leftjoin('languages','languages.id','=','jobseeker_languages.language_id')
                           ->where('jobseekers.id',$id)
                           ->select('jobseekers.only_country','jobseekers.jobseeker_furigana_name_status','jobseekers.gender_status','jobseekers.dob_status','jobseekers.current_address_status','jobseekers.jobseeker_number','jobseekers.last_annual_income','jobseekers.last_currency','jobseekers.other_certificate','jobseekers.num_of_experienced_companies','countries.id','jobseekers.country_id as country_id','jobseekers.final_education','jobseekers.address','jobseekers.jobseeker_name','jobseekers.gender','jobseekers.dob','jobseekers.phone','jobseekers.email','jobseekers.skype_account','jobseekers.other_certificate','jobseekers.current_situation','jobseekers.jobseeker_furigana_name','countries.country_name','countries.city_name','jobseeker_languages.language_id','languages.language_name','jobseeker_languages.language_level')
                           ->first();

        // $profile->dob = explode('-', $profile->dob);  
        // return $profile->dob;
        $cities = Country::where('country_name',$profile->country_name)->get();
        $countries = Country::groupBy('country_name')->get();   
        $languages = Language::all();
        
        return $this->createResponse(array('profile'=>$profile,'cities'=>$cities,'countries'=>$countries,'languages'=>$languages),200);     
    }

    //generated by zinko
	//14.8.2020(fri)
     public function updateBasicInfo(Request $request)
	{		
		// $years = null;$months = null;$days = null;
		$id = $request['jobseekerid'] ?? $this->getLoginJobseekerID();
		DB::beginTransaction();
		
		// if($request->input('dobyears') != null)
        // {
        //     $year = explode(' 年',$request->input('dobyears'));
        //     $years  = implode($year);
        // }
        // if($request->input('dobmonth') != null)
        // {
        //     $month = explode(' 月',$request->input('dobmonth'));
        //     $months = implode($month);
		// }
		// if($request->input('dobday') != null)
        // {
        //     $day = explode(' 日',$request->input('dobday'));
        //     $days = implode($day);
        // }
	

	 try {
            $jobseeker = Jobseeker::findOrFail($id);
            $user = User::findOrFail($jobseeker->user_id);			
			$user->name = $request->input('jobseeker_name');
			$user->update();
				
			$jobseeker->jobseeker_name = $request->input('jobseeker_name');
			$jobseeker->jobseeker_furigana_name = $request->input('jobseeker_furigana_name');
			if($request->input('jobseeker_furigana_name_status') == 'true' || $request->input('jobseeker_furigana_name_status')== 1 ){
				$jobseeker->jobseeker_furigana_name_status = 1;
			}
			else{
				$jobseeker->jobseeker_furigana_name_status = 0;
			}

	
			$jobseeker->gender = $request->input('gender');
			if($request->input('gender_status') == 'true' || $request->input('gender_status') == 1){
				$jobseeker->gender_status = 1;
			}
			else{
				$jobseeker->gender_status = 0;
			}

			$jobseeker->dob = $request->input('dob');
			if($request->input('dob_status') == 'true' || $request->input('dob_status') == 1){
				$jobseeker->dob_status = 1;
			}
			else{
				$jobseeker->dob_status = 0;
			}

			if($request->input('current_address_status') == 'true' || $request->input('current_address_status') == 1){
				$jobseeker->current_address_status = 1;
			}
			else{
				$jobseeker->current_address_status = 0;
			}

			// $jobseeker->dob =Carbon::createFromDate($years, $months,$days);
			// if($years == null || $months == null || $days == null ){
            //     $jobseeker->dob = null;
            // }
            // else{
            //     $jobseeker->dob =Carbon::createFromDate($years, $months,$days);
            // }
            $jobseeker->address = $request->input('address');
            $jobseeker->phone = $request->input('phone');
            $jobseeker->email = $request->input('email');
            $jobseeker->skype_account = $request->input('skype_account');
            $jobseeker->final_education = $request->input('final_education');
			$jobseeker->current_situation = $request->input('current_situation');

			if($request->city_name)
			{
				$jobseeker->only_country = 0;
				$country_id = Country::where('city_name',$request->city_name)->value('id');
				$jobseeker->country_id = $country_id;
			}
			else{
				$jobseeker->only_country = 1;
				$country_id = Country::where('country_name',$request->country_name)->value('id');
				$jobseeker->country_id = $country_id;
			}

		
            $jobseeker->update();
          
            if($request->language_id)
            {
                $language_id = JobSeekerLanguages::where('jobseeker_id',$id)->value('language_id');
                if($language_id){
                    JobSeekerLanguages::where('jobseeker_id',$id)->update(['language_id' => $request->input('language_id')]);

                }
                else{
                    $jobseeker_lang = new JobSeekerLanguages();
                    $jobseeker_lang->jobseeker_id = $id;
                    $jobseeker_lang->language_id = $request->input('language_id');
                    $jobseeker_lang->save();
                }

            }
            else if($request->language_id == 0)
			{
				JobSeekerLanguages::where('jobseeker_id',$id)->update(['language_id' => '']);
			}
           
			DB::commit();
			CustomLog::info("Update".$jobseeker->id);
		} catch (\Exception $e) {
			DB::rollback();
			CustomLog::error($e->getMessage());			
			return $this->createErrorResponse($e->getMessage(), 500);
		}
		

		return $this->createResponse($jobseeker, 200);
    }

    //generated by zinko
    //20 Aug 2020(Thu)
    public function getCarrer(Request $request)
    {
        $id = $request['jobseekerid'] ?? $this->getLoginJobseekerID();
        $carrerdetails = Jobseeker::where('jobseekers.id',$id)
                                       ->select('jobseekers.num_of_experienced_companies','jobseekers.last_annual_income','jobseekers.last_currency')->first();
        $education  = EducationHistories::where('jobseeker_id',$id)->get();
		$experience = ExperienceHistories::leftjoin('positions','positions.id','experience_histories.position_id')
										   ->leftjoin('employment_types','employment_types.id','experience_histories.employment_type_id')
										   ->where('jobseeker_id',$id)
										   ->select('experience_histories.*','positions.position_name','employment_types.employment_type_name')->get();
        $data = array("carrers"=>$carrerdetails, "educations"=>$education, "experiences"=> $experience);
        return $this->createResponse($data,200); 
    }
    
    //generated by zinko
	//19 Aug 2020(Wed)
    public function updateCarrer(Request $request)
    {
      

        $id = $request['jobseekerid'] ?? $this->getLoginJobseekerID();
        $jobseeker = Jobseeker::with('related_images')->findOrFail($id);
       
        try {

        //education
		EducationHistories::where('jobseeker_id',$id)->delete();
			

        foreach ($request->educations as $edu) {
           
                $education = new EducationHistories();
                $education->jobseeker_id = $id;
                $education->school_name = $edu['school_name'];
				$education->subject = $edu['subject'];
				$education->degree = $edu['degree'];
				$education->education_status = $edu['education_status'];
               
                if($edu['from_year'] != null){
                    $year = explode(" ",$edu['from_year']) [0];
                    $education->from_year = $year - 1920;
                }

                if($edu['from_month'] != null){
                    $month = explode(" ",$edu['from_month']) [0];
                    $education->from_month = $month - 12;
                }

                if($edu['to_year'] != null){
                    $year = explode(" ",$edu['to_year']) [0];
                    $education->to_year = $year - 1920;
                }

                if($edu['to_month'] != null){
					$month = explode(" ",$edu['to_month']) [0];
					$education->to_month = $month - 12;
				}

				$education->save();
			}

		$del_edu = "delete from education_histories where school_name is null and subject is null and degree is null and from_year is null and from_month is null and to_year is null and to_month is null and education_status is null";
	
		DB::delete($del_edu);
                

        //experience
        ExperienceHistories::where('jobseeker_id',$id)->delete();
        
        foreach ($request->experiences as $exp){
            $experience = new ExperienceHistories();
            $experience->jobseeker_id = $id;
            $experience->position_id = $exp['position_id'];
            $experience->employment_type_id = $exp['employment_type_id'];
            $experience->job_location = $exp['job_location'];
            $experience->main_duty = $exp['main_duty'];

            if($exp['position_id'] != '0'){
                $experience->position_id = $exp['position_id'];
            }

            if($exp['employment_type_id'] != 0){
                $experience->employment_type_id = $exp['employment_type_id'];
            }

            if($exp['from_year'] != '年'){
                $year = explode(" ",$exp['from_year']) [0];
                $experience->from_year = $year - 1920;
			}
			
            if($exp['from_month'] != '月' ){
                $month = explode(" ",$exp['from_month']) [0];
                $experience->from_month = $month - 12;
            }
          
            if($exp['to_year'] != '年'){
                $year = explode(" ",$exp['to_year']) [0];
                $experience->to_year = $year - 1920;
            }
           
            if($exp['to_month'] != '月'){
                $month = explode(" ",$exp['to_month']) [0];
                $experience->to_month = $month - 12;
            }
            
            $experience->current = $exp['current'];
            $experience->private_status = $exp['private_status'];
			$experience->save();
            
		}

		$del_exp = "delete from experience_histories where position_id=0 and employment_type_id=0 and job_location is null and main_duty is null and from_year is null and from_month is null and to_year is null and to_month is null";
		
		DB::delete($del_exp);
        
        //carrers
        $carrer =Jobseeker::findOrFail($id);
        $carrer->num_of_experienced_companies = $request->carrers["num_of_experienced_companies"];
        $carrer->last_annual_income = $request->carrers["last_annual_income"];
        $carrer->last_currency =$request->carrers["last_currency"];
        $carrer->update();
    
        DB::commit();
           CustomLog::info("Update".$jobseeker->id);
        } catch (\Exception $e) {
            DB::rollback();
            CustomLog::error($e->getMessage());			
            return $this->createErrorResponse($e->getMessage(), 500);
        }
    }
	
    
    //generated by su sandy aung
	//30.7.2020(Thu)
	public function getSelfIntro(Request $request)
	{

		$id = $request['jobseekerid'] ?? $this->getLoginJobseekerID();

		$related_images = Jobseeker::with('related_images')->findorFail($id);

		$selfIntroDetails = Jobseeker::leftjoin('countries as ca','ca.id','=','jobseekers.desired_location_1')
									->leftjoin('countries as cb','cb.id','=','jobseekers.desired_location_2')
									->leftjoin('countries as cc','cc.id','=','jobseekers.desired_location_3')
									->leftjoin('desired_occupations','jobseekers.id','desired_occupations.jobseeker_id')
									->leftjoin('occupations','occupations.id','desired_occupations.occupation_id')
									->select(DB::raw("'' as language_level,group_concat(occupations.occupation_name) as occupation_name"),
										    'jobseekers.jobseeker_nick_name', 'jobseekers.jobseeker_number','ca.country_name as desired_location_1',
											 'cb.country_name as desired_location_2','cc.country_name as desired_location_3','jobseekers.video','jobseekers.self_pr')
									->where('jobseekers.id',$id)
									->first();	


		// $language = Jobseeker::select(DB::raw("group_concat(languages.language_name,'|',jobseeker_languages.language_level) as language_level"),'jobseekers.id')
		// 			->leftJoin('jobseeker_languages','jobseeker_languages.jobseeker_id','=','jobseekers.id')
		// 			->leftJoin('languages','languages.id','=','jobseeker_languages.language_id')
		// 			->where('jobseekers.id',$id)
		// 			->groupBy('jobseeker_languages.jobseeker_id')
		// 			->get(); 

		$language = Jobseeker::select(DB::raw("group_concat(languages.language_name,'|',IFNULL(jobseeker_languages.language_level, '')) as language_level"),'jobseekers.id')
					->leftJoin('jobseeker_languages','jobseeker_languages.jobseeker_id','=','jobseekers.id')
					->leftJoin('languages','languages.id','=','jobseeker_languages.language_id')
					->where('jobseekers.id',$id)
					->groupBy('jobseeker_languages.jobseeker_id')
					->get(); 


									
	
		if($selfIntroDetails->video)
		{
			$selfIntroDetails->video = generate_youtube_embed_link($selfIntroDetails->video);
		}

		$file_names = ['photo_1','photo_2','photo_3','photo_4'];
		$file_hashed = [];

		foreach($file_names as $file)
		{
			$hash_name = md5($selfIntroDetails->jobseeker_number.'_'.$file);
			array_push($file_hashed,$hash_name);
		}
																							
		return $this->createResponse(array("selfIntro"=>$related_images,"selfIntroDetails"=>$selfIntroDetails,"hashedFile"=>$file_hashed,"language"=>$language));

	}


	//generated by su sandy aung
	//30.7.2020(Thu)
    public function updateSelfIntro(Request $request)
	{
		
		$id = $request['jobseekerid'] ?? $this->getLoginJobseekerID();
		$jobseeker = Jobseeker::with('related_images')->findOrFail($id);
		
		DB::beginTransaction();

		try {
			
			$jobseeker->jobseeker_nick_name = $request->input('jobseeker_nick_name');
            $jobseeker->video = $request->input('video');
			$jobseeker->self_pr = $request->input('self_pr');
			$jobseeker->face_image_private_status = $request->input('face_image_private_status');
            

			/* FaceImage */
			if ($request->input('delete_fac_image') === 'true') {
				
				if($jobseeker->face_image != '')
				{
					
					$arr= explode('.',$jobseeker->face_image);
					$fac_blur = md5($arr[0]).'.'.$arr[1];
					delete_file($fac_blur, 'jobseekers/images');
				}
				
				delete_file($jobseeker->face_image, 'jobseekers/images');
				$jobseeker->face_image = '';
			}		

		    /* Face Images */
			if ($request->file('face_image') != null) {
			
				// --Delete existing file
				if($jobseeker->face_image != '')
				{
					$arr= explode('.',$jobseeker->face_image);
					$fac_blur = md5($arr[0]).'.'.$arr[1];
					delete_file($fac_blur, 'jobseekers/images');

				}
				delete_file($jobseeker->face_image, 'jobseekers/images');
				
				//--Define file meta
				$file = $request->file('face_image');
				$ext = $file->getClientOriginalExtension();
				$name = $jobseeker->jobseeker_number .'_face_image';
				$filename = blur_image('face_image',$file,$name);

				put_file($file, $filename.'.'.$ext, 'jobseekers/images');

				$jobseeker->face_image = $filename.'.'.$ext;
			}
			

			/* Related Images deletion */
			if ($request->input('delete_related_images') != null) {
				
				$to_delete = $request->input('delete_related_images');
				foreach	($to_delete as $filename) {
					$old = $jobseeker->related_images()->where('url', 'LIKE', $filename.'%')->first();
					
					if ($old) {
					
						delete_file(md5((explode('.', $old->url))[0]).'.'.(explode('.', $old->url))[1],'jobseekers/images');
						delete_file($old->url, 'jobseekers/images');
						$old->delete();
					}
				}
			}			

			/* Related Images */
			if ($request->file('related_images') != null) {
			
				$files = ($request->all())['related_images'];
				
				foreach ($files as $fileInfo) {
					if (isset($fileInfo['file'])) {
						$file = $fileInfo['file'];
						// $prefix = md5((explode('.', $fileInfo['url']))[0]);
						$name = (explode('.', $fileInfo['url']))[0];
					
						$prefix = blur_image('related_images',$file,$name);
					
						// --delete old record & file
						$old = $jobseeker->related_images()->where('url', 'LIKE', $prefix.'%')->first();
					     
						if ($old) {
							delete_file($old->url, 'jobseekers/images');
							$old->delete();
						}

						// --put new file & save in db
						$ext = $file->getClientOriginalExtension();
						$filename = $prefix.".".$ext;
						put_file($file, $filename, 'jobseekers/images');
						$jobseeker->related_images()->updateOrCreate([
							'url' => $filename,
							'file_type' => 'photo',
							'user_type' => 'jobseeker',
						]);
					}
				}
			}

			$jobseeker->save();
			DB::commit();
			CustomLog::info("Update ".$jobseeker->id);
		} catch (\Exception $e) {
			DB::rollback();
			CustomLog::error($e->getMessage());			
			return $this->createErrorResponse($e->getMessage(), 500);
		}
		

		return $this->createResponse($jobseeker, 200);
	}


    //generated by su sandy aung
	//26.8.2020(Wed)
	public function getDesiredCondition(Request $request)
	{
		$jobseeker_id = $request['jobseekerid'] ?? $this->getLoginJobseekerID();

		$city_list = Country::groupBy('country_name')->get();
		$industry_list = Industry::all();
		$occupation_list = Occupation::all();

		$industries = DesiredIndustry::select('industry_id as id','jobseeker_id')->where('jobseeker_id',$jobseeker_id)->get();

		$occupations = DesiredOccupation::where('jobseeker_id',$jobseeker_id)->select('occupation_id as id','jobseeker_id')->get();

		$jobseeker = Jobseeker::leftjoin('countries as ca','ca.id','=','jobseekers.desired_location_1')
								->leftjoin('countries as cb','cb.id','=','jobseekers.desired_location_2')
								->leftjoin('countries as cc','cc.id','=','jobseekers.desired_location_3')
								->select(DB::raw("'' as industry_name,'' as occupation_name"),'ca.country_name as desired_locations_1',
								'jobseekers.job_change_reason','jobseekers.job_search_activity','jobseekers.main_fact_when_change','jobseekers.desired_change_period',
								'jobseekers.desired_min_annual_income','jobseekers.desired_occupation_status','jobseekers.desired_industry_status','jobseekers.desired_currency',
								'jobseekers.desired_max_annual_income','cb.country_name as desired_locations_2','cc.country_name as desired_locations_3',
								'jobseekers.desired_location_1','jobseekers.desired_location_2','jobseekers.desired_location_3')
								->where('jobseekers.id',$jobseeker_id)
								->first();
								
		$industry_details = DesiredIndustry::join('industries','industries.id','desired_industries.industry_id')
							->select('industries.industry_name')
							->where('desired_industries.jobseeker_id',$jobseeker_id)
							->get();

		$jobseeker->industry_name = $industry_details;

		$occupation_details = DesiredOccupation::join('occupations','occupations.id','desired_occupations.occupation_id')
										 ->select('occupations.occupation_name')
										 ->where('desired_occupations.jobseeker_id',$jobseeker_id)
										 ->get();

		$jobseeker->occupation_name = $occupation_details;
		$date = Carbon::now()->startOfMonth()->addMonth()->toDateString();

		for( $i = 0; $i < 12 ; $i++) {
			$date_list[] = date('Y-m', strtotime($date. ' +'.$i.' month'));
			$date_arr[] = explode('-',$date_list[$i]);
			$date_list[$i] = $date_arr[$i][0].'年'.$date_arr[$i][1].'月('. ($i+1) .'ヵ月後までに)';
        }
	
		return response()->json(array("city_list"=>$city_list,"industry_list"=>$industry_list,"occupation_list"=>$occupation_list,"date_list"=>$date_list,"desired_condition"=>$jobseeker,
									   "industries"=>$industries,"occupations"=>$occupations));
	}


	//generated by su sandy aung
	//27.8.2020(Thu)
	public function updateDesiredCondition(Request $request)
	{
		
		$jobseeker_id =  $request['jobseekerid'] ?? $this->getLoginJobseekerID();
		$industries = $request->industries;
		$occupations = $request->occupations;
		DB::beginTransaction();
		try{
			$jobseeker = Jobseeker::findOrFail($jobseeker_id);
			$jobseeker->job_change_reason =  $request->desired_condition['job_change_reason'];
			$jobseeker->job_search_activity = $request->desired_condition['job_search_activity'];
			$jobseeker->main_fact_when_change = $request->desired_condition['main_fact_when_change'];
			$jobseeker->desired_change_period = $request->desired_condition['desired_change_period'];
			$jobseeker->desired_location_1 = $request->desired_condition['desired_location_1'];
			$jobseeker->desired_location_2 = $request->desired_condition['desired_location_2'];
			$jobseeker->desired_location_3 = $request->desired_condition['desired_location_3'];
			$jobseeker->desired_currency = $request->desired_condition['desired_currency'];
			$jobseeker->desired_min_annual_income =  $request->desired_condition['desired_min_annual_income'];
			$jobseeker->desired_max_annual_income =  $request->desired_condition['desired_max_annual_income'];
	
			if( $request->desired_condition['desired_occupation_status'] == false){
				$jobseeker->desired_occupation_status = 0;
			}
			else{
				$jobseeker->desired_occupation_status = 1;
			}
			if($request->desired_condition['desired_industry_status'] == false){
				$jobseeker->desired_industry_status = 0; 
			}
			else{
				$jobseeker->desired_industry_status = 1;
			}

			$jobseeker->update();
			
		    //insert related industries
            $industry = DesiredIndustry::where('jobseeker_id',$jobseeker_id)->delete();
				
			for($i = 0;$i<sizeof($industries);$i++){
				$industry = new  DesiredIndustry();
				if($industries[$i]['id'] != 0 && $industries[$i]['id'] != null){
					$industry->industry_id = $industries[$i]['id'];
					$industry->jobseeker_id = $jobseeker_id;
					$industry->save();
				}
				
			}
		
			
			//insert related occupation 
			$occ = DesiredOccupation::where('jobseeker_id',$jobseeker_id)->delete();
				
			for($i = 0;$i<sizeof($occupations);$i++){
				$occupation = new  DesiredOccupation();
				if($occupations[$i]['id'] != 0 && $occupations[$i]['id'] != null){
					$occupation->occupation_id = $occupations[$i]['id'];
					$occupation->jobseeker_id = $jobseeker_id;
					$occupation->save();
				}
				
			}
		
			DB::commit();
			CustomLog::info("Update".$jobseeker_id);

		}catch (\Exception $e) {
			DB::rollback();
			CustomLog::error($e->getMessage());			
			return $this->createErrorResponse($e->getMessage(), 500);
		}

		return $this->createResponse($jobseeker, 200);
	}
	 
	//generated by su sandy aung
	//12.8.2020(Thu)
	public function getResume()
	{
		$id = $this->getLoginJobseekerID();
		$resume_list = Jobseeker::with('cv_jp','cv_en','cv_other','exp_jp','exp_en','exp_other')->findorFail($id);
		return $resume_list;
	
	}
	
    //generated by su sandy aung
	//12.8.2020(Thu)
	public function fileUpload(Request $request)
	{
		
		$file = $request->file;
		$type = $request->type;
	
		if($type == 'cv_jp') $file_type = 'cv-jp';
		if($type == 'cv_en') $file_type = 'cv-en';
		if($type == 'cv_other') $file_type = 'cv-other';
		if($type == 'exp_jp') $file_type = 'exp-jp';
		if($type == 'exp_en') $file_type = 'exp-en';
		if($type == 'exp_other') $file_type = 'exp-other';


		$id = $this->getLoginJobseekerID();
		$jobseeker = Jobseeker::findOrFail($id);


		DB::beginTransaction();
		try {
            date_default_timezone_set('UTC');

			if($file != null)
			{	

				$old = $jobseeker->$type()->where('url', 'LIKE', $jobseeker->jobseeker_number.'_'.$type.'%')->first();
			   
				if ($old) {
					delete_file($old->url, 'jobseekers/cvs');
					$old->delete();
				}	

				$ext = $file->getClientOriginalExtension();

				$filename = $jobseeker->jobseeker_number."_".$type.'.'.$ext;
				// $real_name = explode('.',$file->getClientOriginalName())[0] ;
				$real_name = $file->getClientOriginalName();
			
				put_file($file, $filename, 'jobseekers/cvs');

				$jobseeker->$type()->updateOrCreate([
					'url' => $filename,
					'file_type' => $file_type,
					'user_type' => 'jobseeker',
					'real_url' => $real_name,
				]);
			}

			$jobseeker->save();
			DB::commit();
			CustomLog::info("Update ".$jobseeker->id);
		}catch (\Exception $e) {
			DB::rollback();
			CustomLog::error($e->getMessage());			
			return $this->createErrorResponse($e->getMessage(), 500);
		}
	
		return $this->createResponse($jobseeker, 200);
	
	
	}

    //generated by su sandy aung
	//12.8.2020(Thu)
	public function fileRemove($type,$lang)
	{
		$id = $this->getLoginJobseekerID();
		$jobseeker = Jobseeker::where('id',$id)->first();
		$fun = $type.'_'.$lang;
		$old = $jobseeker->$fun()->where('url', 'LIKE', $jobseeker->jobseeker_number.'_'.$type.'_'.$lang.'%')->first();
		if($old){
			delete_file($old->url, 'jobseekers/cvs');
			$old->delete();
		}
		return 'success';
	
	}

	public function scoutedCount()
	{
	
		$count = 0;
		$id = $this->getLoginJobseekerID();
		$scouts = Scout::where('jobseeker_id',$id)->where('scout_status','回答待ち')->get();
		if(sizeof($scouts)){
			$count = Count($scouts);
		}
		return $count;
		
	}

	public function countryList($name)
	{
		$query = "SELECT '' as completed,id,continent_name,country_name  from countries where continent_name like '".$name."' group by country_name";
		$country_list = DB::select($query);
		// $country_list = Country::where('continent_name','like',$name)->select('country_name')->get();
		return $country_list;
	}

	/**
     * @get all data of industries tables
     * @author Zayar Phone Naing @ 2020/08/20
     * @last_maintained @ 2020/08/21
     * @param 
     * @return response
     */
    public function getExperiencesQualifications(Request $request)
    {
		$id_column 			= $request->jobseekerid ? 'id' : 'user_id';
		$jobseeker          = Jobseeker::where($id_column, $request->jobseekerid ?? auth()->user()->id)->first();
        $industry_histories = IndustryHistory::where('jobseeker_id', $jobseeker->id)->get();
        $education_overseas = EducationOverseas::where('jobseeker_id', $jobseeker->id)->get();
        $working_overseas   = ExperienceOverseas::where('jobseeker_id', $jobseeker->id)->get();
        $languages_levels   = JobSeekerLanguages::where('jobseeker_id', $jobseeker->id)->get();
		$industries         = Industry::all();
		$countries          = Country::groupBy('country_name')->get();
        $occupations        = Occupation::all();
        $positions          = Position::all();
        $languages          = Language::all();

        $response_array = [
            'jobseeker_detail'      => $jobseeker,
            'industry_histories'    => $industry_histories,
            'education_overseas'    => $education_overseas,
            'working_overseas'      => $working_overseas,
            'languages_levels'      => $languages_levels,
			'industries'            => $industries,
			'countries'            	=> $countries,
            'occupations'           => $occupations,
            'positions'             => $positions,
            'languages'             => $languages,
        ];
        return $this->createResponse($response_array, 200);
    }

    /**
     * @store industry-histories of jobseeker
     * @author Zayar Phone Naing @ 2020/08/21
     * @last_maintained @ 2020/08/26
     * @param request
     * @return response
     */
    public function saveExperiencesQualifications(Request $request)
    {
		$jobseeker_id 	= $request->request_id ?? $this->getLoginJobseekerID();
        // $experience_jobs_ids_to_delete          = array_column($request->delete_experience_jobs, 'industry_history_id');
        // $study_abroad_exp_ids_to_delete         = array_column($request->delete_study_abroad, 'study_abroad_id');
        // $overseas_working_exp_ids_to_delete     = array_column($request->delete_working_abroad, 'working_abroad_id');
        // $foreign_language_level_ids_to_delete   = array_column($request->delete_foreign_languages_experiences, 'foreign_language_id');
        DB::beginTransaction();
        try {
            // Firstly delete records which users want to remove and then do update or create records

            //experience job type
            if (count($request->delete_experience_jobs) > 0) {
				IndustryHistory::where('jobseeker_id', $jobseeker_id)->whereIn('id', $request->delete_experience_jobs)->delete();
				CustomLog::info("Delete " . json_encode($request->delete_experience_jobs));
            }
            foreach ($request->experienced_jobs as $experienced_job) {
			
				$IndustryHistoryRecords = [
					'jobseeker_id'          => $jobseeker_id,
					'industry_id'           => $experienced_job['experience_industry'],
					'occupation_keyword_id' => $experienced_job['experience_occupation'],
					'position_id'           => $experienced_job['experience_position'],
					'experience_year'       => $experienced_job['experience_year']
				];
				if(empty($experienced_job['industry_history_id']) && ($experienced_job['experience_industry'] != null || $experienced_job['experience_occupation'] != null || $experienced_job['experience_position'] != null || $experienced_job['experience_year'] != null ) )
				{
				    IndustryHistory::create($IndustryHistoryRecords);
				}
				else if(!empty($experienced_job['industry_history_id']))
				{
					IndustryHistory::find($experienced_job['industry_history_id'])->update($IndustryHistoryRecords);
				}
					
				CustomLog::info("Insert ".$jobseeker_id);
            }

            //study abroad experience
            if (count($request->delete_study_abroad) > 0) {
				EducationOverseas::where('jobseeker_id', $jobseeker_id)->whereIn('id', $request->delete_study_abroad)->delete();
				CustomLog::info("Delete " . json_encode($request->delete_study_abroad));
            }
            foreach ($request->study_abroad_experiences as $study_abroad_experience) {
				$EducationOverseasRecords = [
                    'jobseeker_id'  => $jobseeker_id,
                    'country_id'    => $study_abroad_experience['study_abroad_country'],
                    'period'        => $study_abroad_experience['study_abroad_period'],
                    'purpose'       => $study_abroad_experience['study_abroad_purpose'],
				];
				if(empty($study_abroad_experience['study_abroad_id']) && ($study_abroad_experience['study_abroad_country'] != null || $study_abroad_experience['study_abroad_period'] != null || $study_abroad_experience['study_abroad_purpose'] != null))
					EducationOverseas::create($EducationOverseasRecords);
				else if(!empty($study_abroad_experience['study_abroad_id']))
					EducationOverseas::find($study_abroad_experience['study_abroad_id'])->update($EducationOverseasRecords);
				CustomLog::info("Insert ".$jobseeker_id);
            }

            //working at overseas experience
            if (count($request->delete_working_abroad) > 0) {
				ExperienceOverseas::where('jobseeker_id', $jobseeker_id)->whereIn('id', $request->delete_working_abroad)->delete();
				CustomLog::info("Delete " . json_encode($request->delete_working_abroad));
            }
            foreach ($request->working_abroad_experiences as $working_abroad_experience) {
				$ExperienceOverseasRecords = [
                    'jobseeker_id'  => $jobseeker_id,
                    'country_id'    => $working_abroad_experience['working_abroad_country'],
                    'period'        => $working_abroad_experience['working_abroad_period'],
                    'position_id'   => $working_abroad_experience['working_abroad_position'],
				];
				if(empty($working_abroad_experience['working_abroad_id']) && ($working_abroad_experience['working_abroad_country'] != null || $working_abroad_experience['working_abroad_period'] != null || $working_abroad_experience['working_abroad_position'] != null))
					ExperienceOverseas::create($ExperienceOverseasRecords);
				else if(!empty($working_abroad_experience['working_abroad_id']))
					ExperienceOverseas::find($working_abroad_experience['working_abroad_id'])->update($ExperienceOverseasRecords);
				CustomLog::info("Insert ".$jobseeker_id);
            }

            //work visa && other qualifications
            Jobseeker::find($jobseeker_id)
                ->update([
                    'visa_status' 	=> $request->work_visa['status'],
                    'visa_country' 	=> $request->work_visa['status'] == 1 ? $request->work_visa['country'] : NULL,
                    'toeic_score' 	=> $request->other_qualifications['TOEIC_score'],
                    'other_language_certificate' => $request->other_qualifications['language_qualifications'],
                    'other_certificate' => $request->other_qualifications['qualifications'],
                ]);

            //foregin language level experience
            if (count($request->delete_foreign_languages_experiences) > 0) {
				JobSeekerLanguages::where('jobseeker_id', $jobseeker_id)->whereIn('id', $request->delete_foreign_languages_experiences)->delete();
				CustomLog::info("Delete " . json_encode($request->delete_foreign_languages_experiences));
            }
            foreach ($request->foreign_language_experiences as $foreign_language_experience) {
				$JobSeekerLanguagesRecords = [
                    'jobseeker_id'		=> $jobseeker_id,
                    'language_id'		=> $foreign_language_experience['foreign_language'],
                    'language_level'	=> $foreign_language_experience['language_level'],
				];
				if(empty($foreign_language_experience['foreign_language_id']))
					JobSeekerLanguages::create($JobSeekerLanguagesRecords);
				else
					JobSeekerLanguages::find($foreign_language_experience['foreign_language_id'])
					->update($JobSeekerLanguagesRecords);
				CustomLog::info("Insert ".$jobseeker_id);
            }

            $del_edu_oversea = "delete from education_overseas where country_id is null and period is null and purpose is null";
            DB::delete($del_edu_oversea);

            $del_exp_oversea = "delete from experience_overseas where country_id is null and period is null and position_id is null";
            DB::delete($del_exp_oversea);

            DB::commit();
        } catch (\Exception $e) {
			DB::rollback();
			CustomLog::error($e->getMessage());	
            return $this->createErrorResponse($e->getMessage(), 500);
        }

        return $this->createResponse("Successfully Saved", 200);
    }

}