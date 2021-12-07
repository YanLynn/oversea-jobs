<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class JobSeekerLanguages extends Model
{
    protected $table = 'jobseeker_languages';
    protected $fillable = [
      'jobseeker_id','language_level','language_id',
    ];
}
