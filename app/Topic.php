<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = ['topic_name', 'video_id', 'video_url', 'video_description'];

    public function keywords(){
        return $this->belongsToMany('App\Keyword');
    }
	
	public function chemtexts(){
		return $this->belongsToMany('App\Chemtext');
	}
	
	public function problems(){
		return $this->belongsToMany('App\Problem');
	}
	
	public function solutions(){
		return $this->belongsToMany('App\Solution');
	}

	public function simulations(){
		return $this->belongsToMany('App\Simulation');
	}
}
