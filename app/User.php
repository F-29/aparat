<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * @method static where(string $string, $username)
 */
class User extends Authenticatable
{

    //region User type config
    const TYPE_USER = 'user';
    const TYPE_ADMIN = 'admin';
    const TYPES = [self::TYPE_ADMIN, self::TYPE_USER];
    //endregion

    //region Traits
    use HasApiTokens, Notifiable;
    //endregion

    //region Custom util method for model
    /**
     * find and login the user through 1-email or 2-mobile
     * @param $username
     * @return mixed
     */
    public function findForPassport($username)
    {
        return static::where('mobile', $username)->orWhere('email', $username)->first();
    }
    //endregion

    //region Model setters
    /**
     * @param $value
     */
    public function setMobileAttribute($value)
    {
        $this->attributes['mobile'] = to_valid_mobile_number($value);
    }
    //endregion

    //region Relations
    /**
     * manyToOne relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }

    /**
     * oneToMany relationship
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function channel()
    {
        return $this->hasOne(Channel::class);
    }

    /**
     * oneToMany relationship
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }
    //endregion

    //region Model config
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
    //endregion
}


