<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ghangout extends Model
{
    protected $fillable = ['meeting_name', 'url'];

    public function users() {
    	return $this->belongsToMany("App\User");
    }
}
