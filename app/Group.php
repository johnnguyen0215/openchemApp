<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{

	protected $fillable = ['group_name'];
    
    public function users(){
    	return $this->belongsToMany('App\User');
    }
}
