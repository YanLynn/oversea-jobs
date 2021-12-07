<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ExperienceHistories extends Model
{
    protected $table = 'experience_histories';
    protected $fillable = [
        'jobseeker_id','position_id','employment_type_id','job_location','main_duty','from_year','from_month','to_year','to_month','current','private_status',
    ];
}
