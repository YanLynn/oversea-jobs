<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EducationOverseas extends Model
{
    protected $table = 'education_overseas';
    protected $fillable = [
        'id', 'jobseeker_id', 'country_id', 'period', 'purpose'
    ];
}
