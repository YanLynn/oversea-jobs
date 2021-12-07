<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Occupation extends Model
{
    protected $table = 'occupations';
    protected $fillable = [
        'id', 'occupation_name',
    ];
}
