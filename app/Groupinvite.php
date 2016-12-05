<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Groupinvite extends Model
{

	protected $fillable = ['sender_id', 'recipient_id', 'group_id'];

    public function users() {
    	return $this->belongsToMany('App\User');
    }

}
