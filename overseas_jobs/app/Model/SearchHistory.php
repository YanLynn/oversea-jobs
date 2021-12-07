<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
    protected $table = "search_histories";
    protected $fillable = [
        'field_id','table_type','user_type','search_date'
    ];

}
