<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
class DesiredOccupations extends Model
{
    protected $table = 'desired_occupations';
    protected $fillable = [
        'id', 'jobseeker_id', 'occupation_id',
    ];
    /**
	 * Get the desired occupations for the jobseeker.
	 */
	public function jobseeker()
	{
		return $this->belongsTo(jobseeker::class);
	}
}