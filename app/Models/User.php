<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable,  HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','department_id', 'position', 'surname', 'middle_name',
        'avatar_url', 'birthday', 'hired_at', 'mobile_phone', 'work_phone', 'skype_login', 'is_admin'
    ];
   // protected $with = ['accesses'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function settings()
    {
        return $this->hasOne(UserSettings::class);
    }
    public function accesses()
    {
        return $this->hasOne(UserAccesses::class);
    }

    public function logs()
    {
        return $this->hasMany(UserSecurityLogs::class)->orderBy('created_at', 'DESC');
    }

    public function twoFA()
    {
        return $this->hasOne(TwoFactorAuthTokens::class);
    }

    public function skipsAuthorization()
    {
        return true;
    }
}
