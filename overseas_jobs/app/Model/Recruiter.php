<?php

namespace App\Model;
use DB;

use Illuminate\Database\Eloquent\Model;

class Recruiter extends Model
{
    protected $table = "recruiters";
    
    protected $fillable = ['recruiter_number', 'user_id','recruiter_name','recruiter_nick_name','establishment_date','num_of_employees','business_description','address','phone','email','website','logo','video','incharge_name','incharge_name_furigana','record_status','created_at','updated_at'];

    protected $appends = ['logo_url', 'incharge_photo_url', 'video_url'];

    const PadLength = 5;
	
	public function calculateRecruiterNumber()
	{
		// if (!$this->attributes['id']) {
		// 	throw new \Exception("Id is not set. Please save the model first to get auto-generated Id.");
		// }
		// $this->attributes['recruiter_number'] = str_pad($this->attributes['id'], self::PadLength, "0", STR_PAD_LEFT);
        // $this->save();
        if (!$this->attributes['id']) {
			throw new \Exception("Id is not set. Please save the model first to get auto-generated Id.");
		}
		$last = DB::table('recruiters')
			->join('users','recruiters.user_id','users.id')
			->where('users.email_verified', 1)
			->select('recruiters.*')
			->orderBy('recruiter_number', 'DESC')
			->first();
		$number = 1;
		if ($last != null){
			$number = \intval($last->recruiter_number) + 1;
		}

		$this->attributes['recruiter_number'] = str_pad($number, self::PadLength, "0", STR_PAD_LEFT);
		$this->save();
    }
    
    /**
     * Get the jobs for the recruiter.
     */
    public function job()
    {
        return $this->hasMany(Job::class);
	}

	/**
     * Get the scout for the recruiter.
     */
    public function scout()
    {
        return $this->hasMany(Scout::class);
	}

	/**
     * Get the PaymentTransaction for the recruiter.
     */
    public function payment_transaction()
    {
        return $this->hasMany(PaymentTransaction::class);
	}

	public function related_images()
	{
		return $this->hasMany(UploadedFile::class, 'related_user_id')->recruiterImage()->orderBy('url', 'ASC');
	}

	public function getLogoUrlAttribute() 
    {		
		return generate_image_link($this->logo, "recruiters/images");
	}
	
	public function getInchargePhotoUrlAttribute()
	{
		return generate_image_link($this->incharge_photo, "recruiters/images");
	}

	public function getVideoUrlAttribute()
	{
		return generate_youtube_embed_link($this->video);
	}
}