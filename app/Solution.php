<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Solution extends Model
{

	protected $hidden = ['pivot', 'created_at', 'updated_at'];

    protected $fillable = ['solution_name', 'url'];

    public function topics(){
    	return $this->belongsToMany('App\Topic');
    }
}
