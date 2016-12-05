<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Problem extends Model
{

	protected $hidden = ['pivot', 'created_at', 'updated_at'];

    protected $fillable = ['problem_name', 'url'];

    public function topics(){
        return $this->belongsToMany('App\Topic');
    }
}
