<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'username', 'password', 'admin', 'leader', 'inSession'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function groups(){
        return $this->belongsToMany('App\Group');
    }

    public function groupinvites(){
        return $this->hasMany('App\Groupinvite');
    }

    // public function bbbsessions() {
    //     return $this->belongsToMany('App\Bbbsession');
    // }

    public function ghangouts() {
        return $this->belongsToMany('App\Ghangout');
    }

    public function messages() {
        return $this->hasMany('App\Message');
    }
}

