<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class IndustryHistory extends Model
{
    protected $table = 'industry_histories';
    protected $fillable = ['jobseeker_id', 'industry_id', 'occupation_keyword_id', 'position_id', 'experience_year'];
}
