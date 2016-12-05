<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['message_type', 'sender_id', 'user_id', 'group_id', 'message_content'];

    public function user() {
    	return $this->belongsTo('App\User');
    }

}
