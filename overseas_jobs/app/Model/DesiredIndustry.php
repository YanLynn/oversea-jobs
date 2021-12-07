<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DesiredIndustry extends Model
{
    protected $table = 'desired_industries';
    protected $fillable = [
        'id','jobseeker_id','industry_id'
    ];
}
