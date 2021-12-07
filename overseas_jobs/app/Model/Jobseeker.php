<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\CustomLog;
use DB;

class Jobseeker extends Model
{
    public $table = 'jobseekers';
    public $fillable = ['id','user_id','country_id','jobseeker_name','jobseeker_furigana_name','jobseeker_nick_name','face_image','face_image_private_status','dob',
            'address','phone','email','gender','skype_account','video','final_education','current_situation','num_of_experienced_companies','last_currency',
            'last_annual_income','self_pr','job_change_reason','job_search_activity','main_fact_when_change','desired_change_period','desired_location_1',
            'desired_location_2','desired_location_3','desired_industry_status','desired_occupation_status','desired_salary_status','desired_currency',
            'desired_min_annual_income','desired_max_annual_income','scout_setting_status','visa_status','visa_country','toeic_score','other_language_certificate',
			'other_certificate','record_status'];
			
	protected $appends = ['face_image_url','face_image_blur','desired_occupation'];

    // $jobseeker->custom_id
    // public function getCustomIdAttribute() {
    //     return sprintf("%06d", $this->id);        
    // }

    // public function getJobIdAttribute() {
    //     $recruiter_id = sprintf("%06d", $this->rec)
    // }

    // protected $appends = ['custom_id'];

    const RoleId = 3;

	const PadLength = 7;

	protected $attributes = [
		'jobseeker_nick_name' => NULL,
		'face_image' => NULL,
		'face_image_private_status' => 0,
		'address' => NULL,
		'phone' => '',
		'gender' => NULL,
		'skype_account' => NULL,
		'video' => NULL,
		'final_education' => NULL,
		'current_situation' => NULL,
		'num_of_experienced_companies' => null,
		'last_currency' => NULL,
		'last_annual_income' => NULL,
		'self_pr' => NULL,
		'job_change_reason' => NULL,
		'job_search_activity' => NULL,
		'main_fact_when_change' => NULL,
		'desired_change_period' => NULL,
		'desired_location_1' => NULL,
		'desired_location_2' => NULL,
		'desired_location_3' => NULL,
		'desired_industry_status' => 0,
		'desired_occupation_status' => 0,
		'desired_salary_status' => 0,
		'desired_currency' => NULL,
		'desired_min_annual_income' => NULL,
		'desired_max_annual_income' => NULL,
		'scout_setting_status' => 0,
		'visa_status' => 0,
		'visa_country' => NULL,
		'toeic_score' => NULL,
		'other_language_certificate' => NULL,
		'other_certificate' => NULL,
	];

	public function calculateJobsekerNumber()
	{
		// if (!$this->attributes['id']) {
		// 	throw new \Exception("Id is not set. Please save the model first to get auto-generated Id.");
		// }
		// $this->attributes['jobseeker_number'] = str_pad($this->attributes['id'], self::PadLength, "0", STR_PAD_LEFT);
        // $this->save();
        
        if (!$this->attributes['id']) {
			throw new \Exception("Id is not set. Please save the model first to get auto-generated Id.");
		}
		$last = DB::table('jobseekers')
			->join('users','jobseekers.user_id','users.id')
			->where('users.email_verified', 1)
			->select('jobseekers.*')
			->orderBy('jobseeker_number', 'DESC')
			->first();
		$number = 1;
		if ($last != null){
			$number = \intval($last->jobseeker_number) + 1;
		}
		$this->attributes['jobseeker_number'] = str_pad($number, self::PadLength, "0", STR_PAD_LEFT);
		$this->save();
	}

	public function related_images()
	{
		return $this->hasMany(UploadedFile::class, 'related_user_id')->jobseekerImage()->orderBy('url', 'ASC');
	}

	public function cv_jp()
	{
		return $this->hasOne(UploadedFile::class, 'related_user_id')->jobseekerCvJp()->orderBy('url', 'ASC');
	}

	public function cv_en()
	{
		return $this->hasOne(UploadedFile::class, 'related_user_id')->jobseekerCvEn()->orderBy('url', 'ASC');
	}

	public function cv_other()
	{
		return $this->hasOne(UploadedFile::class, 'related_user_id')->jobseekerCvOther()->orderBy('url', 'ASC');
	}

	public function exp_jp()
	{
		return $this->hasOne(UploadedFile::class, 'related_user_id')->jobseekerExpJp()->orderBy('url', 'ASC');
	}

	public function exp_en()
	{
		return $this->hasOne(UploadedFile::class, 'related_user_id')->jobseekerExpEn()->orderBy('url', 'ASC');
	}

	public function exp_other()
	{
		return $this->hasOne(UploadedFile::class, 'related_user_id')->jobseekerExpOther()->orderBy('url', 'ASC');
	}

	public function getFaceImageUrlAttribute() 
    {		
		return generate_image_link($this->face_image, "jobseekers/images");
	}
	public function desiredOccupations()
    {
        return $this->hasMany(DesiredOccupations::class);
	}
	public function desiredIndustries()
    {
        return $this->hasMany(DesiredIndustries::class);
	}

	public function education_histories()
    {
        return $this->hasMany(EducationHistories::class);
	}
	public function education_overseas()
    {
        return $this->hasMany(EducationOverseas::class);
	}
	public function experience_histories()
    {
        return $this->hasMany(ExperienceHistories::class);
	}
	public function experience_overseas()
    {
        return $this->hasMany(ExperienceOverseas::class);
	}
	public function industry_history()
    {
        return $this->hasMany(IndustryHistory::class);
	}
	public function jobseeker_languages()
    {
        return $this->hasMany(JobSeekerLanguages::class);
	}
	public function job_apply()
    {
        return $this->hasMany(JobApply::class);
	}
	// public function qualification()
    // {
    //     return $this->hasMany(Scout::class);
	// }
	public function scout()
    {
        return $this->hasMany(Scout::class);
	}

	public function occupations()
	{
		return $this->belongsToMany(Occupation::class, 'desired_occupations', 'jobseeker_id', 'occupation_id');
    }
    
    public function getDesiredOccupationAttribute()
	{
		if(isset($this->attributes['desired_occupation_id'])){
            if($this->attributes['desired_occupation_id'] != 0){
                $occupation = Occupation::find($this->attributes['desired_occupation_id'])->occupation_name;
			    return  $occupation;
            }
			
		}
	}
	public function getFaceImageBlurAttribute()
	{
		if(isset($this->attributes['face_image'])){
            CustomLog::info("ChangeStatus ".$this->attributes['face_image']);
            if($this->attributes['face_image'] != ''){
                $exploded = explode(".", $this->attributes['face_image']);
                $blurred_name = md5($exploded[0]).".".($exploded[1]);
            }
            else{
                $blurred_name = '';
            }			
            
            return generate_image_link($blurred_name, "jobseekers/images");
		}
	}

}
