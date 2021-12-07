<?php

namespace App\Model;
use DB;
use App\User;
use Auth;

use Illuminate\Database\Eloquent\Model;

class UploadedFile extends Model
{
	protected $table ="uploaded_files";

	protected $appends = ['file_url'];

	protected $guarded = ['id'];

	public function scopeRecruiterImage($query)
	{
		return $query->where('file_type', config('config.uploaded_file_type.photo'))
					->where('user_type', config('config.uploaded_file_user_type.recruiter'));
	}

	public function scopeJobseekerImage($query)
	{
		return $query->where('file_type', config('config.uploaded_file_type.photo'))
					->where('user_type', config('config.uploaded_file_user_type.jobseeker'));
	}

	public function scopeJobseekerCvEn($query)
	{
        $timezone = User::find(Auth::user()->id)->selectedTimeZone();
		return $query->select('*',DB::raw("convert_tz(updated_at,'+00:00','$timezone') as user_time"))->where('file_type', config('config.uploaded_file_type.cv-en'))
					->where('user_type', config('config.uploaded_file_user_type.jobseeker'));
	}

	public function scopeJobseekerCvJp($query)
	{
        $timezone = User::find(Auth::user()->id)->selectedTimeZone();
		return $query->select('*',DB::raw("convert_tz(updated_at,'+00:00','$timezone') as user_time"))->where('file_type', config('config.uploaded_file_type.cv-jp'))
		// return $query->where('file_type', config('config.uploaded_file_type.cv-jp'))
					->where('user_type', config('config.uploaded_file_user_type.jobseeker'));
	}
	
	public function scopeJobseekerCvOther($query)
	{
        $timezone = User::find(Auth::user()->id)->selectedTimeZone();
		return $query->select('*',DB::raw("convert_tz(updated_at,'+00:00','$timezone') as user_time"))->where('file_type', config('config.uploaded_file_type.cv-other'))
					->where('user_type', config('config.uploaded_file_user_type.jobseeker'));
	}
	
	public function scopeJobseekerExpJp($query)
	{
        $timezone = User::find(Auth::user()->id)->selectedTimeZone();
		return $query->select('*',DB::raw("convert_tz(updated_at,'+00:00','$timezone') as user_time"))->where('file_type', config('config.uploaded_file_type.exp-jp'))
					->where('user_type', config('config.uploaded_file_user_type.jobseeker'));
	}

	public function scopeJobseekerExpEn($query)
	{
        $timezone = User::find(Auth::user()->id)->selectedTimeZone();
		return $query->select('*',DB::raw("convert_tz(updated_at,'+00:00','$timezone') as user_time"))->where('file_type', config('config.uploaded_file_type.exp-en'))
					->where('user_type', config('config.uploaded_file_user_type.jobseeker'));
	}

	public function scopeJobseekerExpOther($query)
	{
        $timezone = User::find(Auth::user()->id)->selectedTimeZone();
		return $query->select('*',DB::raw("convert_tz(updated_at,'+00:00','$timezone') as user_time"))->where('file_type', config('config.uploaded_file_type.exp-other'))
					->where('user_type', config('config.uploaded_file_user_type.jobseeker'));
	}

	public function getFileUrlAttribute()
	{
		$subfolder = '';
		$jobseekerUserType = config('config.uploaded_file_user_type.jobseeker');
		$recruiterUserType = config('config.uploaded_file_user_type.recruiter');
		
		switch ($this->file_type)
		{
			// -- Images
			case config('config.uploaded_file_type.photo'):
				switch ($this->user_type)
				{
					// --Jobseeker related images
					case $jobseekerUserType:
						$subfolder = 'jobseekers/images';
						$this->determineJsRelatedImage();
						break;
					
					// --Recruiter related images
					case $recruiterUserType:
						$subfolder = 'recruiters/images';
						break;
				}
			break;

			// -- CVs
			default:
			break;
		}

		return generate_image_link($this->url, $subfolder);
	}
    
    private function determineJsRelatedImage()
    {
        $user = auth()->user();
        // $getLoignRecuriter = Recruiter::where('user_id', $user ? $user->id : 0)->value('id');
        if (!$user) {
            // return blur image
            $exploded = explode(".", $this->url);
            $blurred_name = md5($exploded[0]).".".($exploded[1]);
            $this->attributes['url'] = $blurred_name;
        }
    }
}
