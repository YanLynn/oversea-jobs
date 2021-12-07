<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
class DesiredIndustries extends Model
{
    protected $table = 'desired_industries';
    protected $fillable = [
        'id', 'jobseeker_id', 'industry_id',
    ];
    /**
	 * Get the desired industries for the jobseeker.
	 */
	public function jobseeker()
	{
		return $this->belongsTo(jobseeker::class);
	}
}