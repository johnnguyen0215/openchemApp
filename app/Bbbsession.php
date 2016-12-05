<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bbbsession extends Model
{
    protected $fillable = ['meeting_name', 'meeting_id', 'modpw', 'attendeepw'];

    public function users() {
    	return $this->belongsToMany("App\User");
    }
}
