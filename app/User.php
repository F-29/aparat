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

    /**
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->type === User::TYPE_ADMIN;
    }

    /**
     * @return boolean
     */
    public function isBaseUser()
    {
        return $this->type === User::TYPE_USER;
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
     * oneToMany relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function myVideos()
    {
        return $this->hasMany(Video::class);
    }

    /**
     * manyToOne relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }

    /**
     * oneToMany relation
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function channel()
    {
        return $this->hasOne(Channel::class);
    }

    /**
     * oneToMany relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * manyToMany relation
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function republishedVideos()
    {
        return $this->hasManyThrough(
            Video::class,
            RepublishVideo::class,
            'user_id', // video_republishes.user_id
            'id', // video.id
            'id', // user.id
            'video_id' // video_republishes.video_id
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\Illuminate\Database\Query\Builder
     */
    public function allVideos()
    {
        return $this->myVideos()
            ->union(
                $this->republishedVideos()
                    ->selectRaw('videos.*')
            );
    }

    public function likedVideos()
    {
        return $this->hasManyThrough(
            Video::class,
            LikedVideo::class,
            'user_id', // LikedVideo.user_id
            'id', // video.id
            'id', // user.id
            'video_id' // LikedVideo.video_id
        );
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


