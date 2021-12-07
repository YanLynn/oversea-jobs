<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Job extends Model
{
    protected $table = 'jobs';
    protected $fillable = ['id','job_number', 'recruiter_id','occupation_id','country_id','recruiter_show_name','title','occupation_description','employment_types','job_description','qualification','allowance','job_start_date','job_location','message','other_keywords','application_address','job_post_status','job_post_date','record_status'];

    const PadLength = 4;

	public function calculateJobNumber() 
    {
        if (!$this->attributes['id']){
            throw new \Exception("Id is not set. Please save the model first to get auto-generated Id.");
        }
        if (!$this->attributes['recruiter_id']){
            throw new \Exception("Recruiter Id is not set. A job post must have a recruiter.");
        }
        $recruiter_number = str_pad($this->attributes['recruiter_id'], Recruiter::PadLength, "0", STR_PAD_LEFT);
        $new_number = 1;
        $lastJob = self::where('recruiter_id', $this->attributes['recruiter_id'])->orderBy('job_number', 'DESC')->first();
        if ($lastJob != null) {
            $exploded = explode("-", $lastJob->job_number);
            if (count($exploded) == 2) {
                $last_number = $exploded[1];
                $new_number = intval($last_number) + 1;
            }
        }       
        $job_number = str_pad($new_number, self::PadLength, "0", STR_PAD_LEFT);
        $this->attributes['job_number'] = sprintf("%s-%s", $recruiter_number, $job_number);
        $this->save();
    }

    /**
	 * Get the jobs for the recruiter.
	 */
	public function recruiter()
	{
		return $this->belongsTo(Recruiter::class);
	}

	/**
	 * Get applied job.
	 */
	public function jobApply()
	{
		return $this->hasMany(JobApply::class,'job_id');
	}

	/**
	 * Get scouted job.
	 */
	public function scout()
	{
		return $this->hasMany(Scout::class);
	}

	public function isExpired()
	{
		$post_date = Carbon::createFromFormat('Y-m-d H:i:s', $this->job_post_date);
		$expiry = $post_date->addMonths(1);
		return Carbon::today()->greaterThan($expiry);
	}
}