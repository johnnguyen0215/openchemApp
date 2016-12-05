<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chemtext extends Model
{

	protected $hidden = ['pivot', 'created_at', 'updated_at'];

    protected $fillable = ['chemtext_name', 'url'];

    public function topics(){
        return $this->belongsToMany('App\Topic');
    }
}
