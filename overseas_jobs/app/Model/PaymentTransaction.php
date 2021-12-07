<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Job;

class PaymentTransaction extends Model
{
	protected $table = 'payment_transactions';

	protected $guarded = ['id'];

	protected $dates = ['invoice_date','due_date'];

	public function payable()
	{
		return $this->morphTo('payable', "payment_job_type", "scoutid_or_applyid");
	}	
	/**
	 * Number of characters for invoice number
	 *
	 * @var int PadLength
	 */
	const PadLength = 4;
	const OrderPadLength = 2;

	public function scopeScout($query)
	{
		return $query->where('payment_job_type', config('config.payment_job_type.scout'));
	}

	public function scopeJobapply($query)
	{
		return $query->where('payment_job_type', config('config.payment_job_type.job_apply'));
	}

	/**
     * Get the scout for the recruiter.
     */
    public function recruiter()
    {
        return $this->belongsTo(Recruiter::class);
	}

	/**
	 * @desc Internally generate invoice number for current model
	 * @author Myo Ko Ko @ 2020/08/04
	 * @last_maintained Myo Ko Ko @ 2020/08/04
	 * @param  null
	 * @return void
	 */
	public function generateInvoiceNumber($management_number)
	{
		$prefix = $management_number."-";
		$number = 1;
		$last = self::where('invoice_number', 'LIKE', $prefix.'%')->orderBy('invoice_number', 'DESC')->first();
		if ($last != null) {
			$exploded = preg_split("/$prefix/", $last->invoice_number, -1, PREG_SPLIT_NO_EMPTY); 
			if (count($exploded) >= 1) { 
				$last_number = $exploded[0]; 
				$number = intval($last_number) + 1; 
			} 
		}
		// $number = str_pad($number, self::PadLength, "0", STR_PAD_LEFT);
		$this->attributes['invoice_number'] = \sprintf("%s%s", $prefix, $number);
	}

	public function generateOrderNumber($management_number)
	{		
		$number = 1;
		$last = self::where('invoice_number', 'LIKE', $management_number.'%')->orderBy('invoice_number', 'DESC')->first();
		if ($last != null) {
			$exploded = preg_split("/$management_number/", $last->invoice_number, -1, PREG_SPLIT_NO_EMPTY); 
			if (count($exploded) >= 1) { 
				$last_number = $exploded[0]; 
				$number = intval($last_number) + 1; 
			} 
		}
		$number = str_pad($number, self::OrderPadLength, "0", STR_PAD_LEFT);
		$this->attributes['invoice_number'] = \sprintf("%s%s", $management_number, $number);
	}
}