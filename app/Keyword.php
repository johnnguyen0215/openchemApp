<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{

	protected $hidden = ['pivot', 'created_at', 'updated_at'];

    protected $fillable = ['word'];

    public function topics(){
        return $this->belongsToMany('App\Topic');
    }

}
