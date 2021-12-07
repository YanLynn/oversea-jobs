<?php

namespace App\Model;
use App\Model\Job;

use Illuminate\Database\Eloquent\Model;

class JobApply extends Model
{
	protected $table = 'job_applies';
	
	protected $fillable = ['id','job_id', 'jobseeker_id','job_apply_status','record_status'];
	
	const PadLength = 4;
	protected $appends = [ 'job_apply_status_replace', ];

	public function getJobApplyStatusReplaceAttribute()
    {
		if(isset($this->attributes['job_apply_status'])){
			return ($this->attributes['job_apply_status'] == '内定未請求' || $this->attributes['job_apply_status'] == '請求済' || $this->attributes['job_apply_status'] == '入金確認済')? '内定' : $this->attributes['job_apply_status'];
		}        
    }

	public function calculateManagementNumber()
	{
		if (!$this->attributes['id']) {
			throw new \Exception("Id is not set. Please save the model first to get auto-generated Id.");
		}
		$prefix = 'K'.date('Ymd');
		$number = 1;
		$lastJobApply = self::where('management_number', 'LIKE', $prefix.'%')->orderBy('management_number', 'DESC')->first();
		if ($lastJobApply != null) {
			$exploded = preg_split("/$prefix/", $lastJobApply->management_number, -1, PREG_SPLIT_NO_EMPTY);
			if (count($exploded) >= 1) {
				$last_number = $exploded[0];
				$number = intval($last_number) + 1;
			}
		}
		$number = str_pad($number, self::PadLength, "0", STR_PAD_LEFT);
		$this->attributes['management_number'] = \sprintf("%s%s", $prefix, $number);
		$this->save();
	}

	/**
	 * Get applied job.
	 */
	public function job()
	{
		return $this->belongsTo(Job::class,'id');
	}

	public function jobseeker()
    {
        return $this->belongsTo(Jobseeker::class);
	}

	public function payment()
	{
		return $this->morphOne(PaymentTransaction::class, 'payable');
	}

}
