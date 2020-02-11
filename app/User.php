<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{

    const TYPE_USER = 'user';
    const TYPE_ADMIN = 'admin';
    const TYPES = [self::TYPE_ADMIN, self::TYPE_USER];
    use Notifiable;

    /**
     * find and login the user through 1-email or 2-mobile
     * @param $username
     * @return mixed
     */
    public function findForPassport($username)
    {
        return static::where('mobile', $username)->orWhere('email', $username)->first();
    }

    /**
     * @param $value
     */
    public function setMobileAttribute($value)
    {
        $mobile = '+98' . substr($value, -10, 10);
        $this->attributes['mobile'] = $mobile;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'mobile', 'type', 'name', 'password', 'avatar', 'website', 'verify_code', 'verified_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'verify_code',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'verified_at' => 'datetime',
    ];
}


