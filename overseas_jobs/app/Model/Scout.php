<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Job;

class Scout extends Model
{
    protected $table = "scouts";
    protected $fillable = ['recruiter_id','jobseeker_id','job_id','management_number','scout_status','scouted_date','record_status','occupation_description','employment_types','job_location'];
    const PadLength = 4;
    protected $appends = [ 'scout_status_replace', ];

    public function getScoutStatusReplaceAttribute()
    {
		if(isset($this->attributes['scout_status'])){
			return ($this->attributes['scout_status'] == '内定未請求' || $this->attributes['scout_status'] == '請求済' || $this->attributes['scout_status'] == '入金確認済')? '内定' : $this->attributes['scout_status'];
		}        
    }

	public function calculateManagementNumber()
	{
		if (!$this->attributes['id']) {
			throw new \Exception("Id is not set. Please save the model first to get auto-generated Id.");
		}
		$prefix = 'S'.date('Ymd');
		$number = 1;
		$lastScout = self::where('management_number', 'LIKE', $prefix.'%')->orderBy('management_number', 'DESC')->first();
		if ($lastScout != null) {
			$exploded = preg_split("/$prefix/", $lastScout->management_number, -1, PREG_SPLIT_NO_EMPTY);
			if (count($exploded) >= 1) {
				$last_number = $exploded[0];
				$number = intval($last_number) + 1;
			}
		}
		$number = str_pad($number, self::PadLength, "0", STR_PAD_LEFT);
		$this->attributes['management_number'] = \sprintf("%s%s", $prefix, $number);
		$this->save();
	}

	public function jobseeker() {
		return  $this->belongsTo('App\Model\Jobseeker');
	}

	/**
	 * Get scouted job.
	 */
	public function job()
	{
		return $this->belongsTo(Job::class);
	}

	/**
     * Get the scout for the recruiter.
     */
    public function recruiter()
    {
        return $this->belongsTo(Recruiter::class);
	}

	public function payment()
	{
		return $this->morphOne(PaymentTransaction::class, 'payable');
	}
}
