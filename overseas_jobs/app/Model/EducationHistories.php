<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EducationHistories extends Model
{
    protected $table = 'education_histories';
    protected $fillable = [
      'jobseeker_id','school_name','subject','degree','from_year','from_month','to_year','to_month','education_status'
    ];
}
