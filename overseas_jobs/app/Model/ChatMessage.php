<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
	protected $table = 'chat_messages';
	
    protected $guarded = [
        'id',
    ];

    public function scopeActive($query){
		return $query->whereNull('expired')->orWhere('expired',0);
	}
}