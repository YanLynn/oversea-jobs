<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ExperienceOverseas extends Model
{
    protected $table = 'experience_overseas';
    protected $fillable = [
      'jobseeker_id','country_id','position_id','period',
    ];
}
