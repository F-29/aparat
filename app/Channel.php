<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    //region Relations
    /**
     * oneToOne relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }
    //endregion

    //region Model setters
    /**
     * @param $value
     */
    public function setSocialsAttribute($value)
    {
        if (is_array($value)) {
            /** @noinspection PhpComposerExtensionStubsInspection */
            $value = json_encode($value);
        }

        $this->attributes['socials'] = $value;
    }
    //endregion

    //region Model getters
    /**
     * @return mixed
     */
    public function getSocialsAttribute()
    {
        /** @noinspection PhpComposerExtensionStubsInspection */
        return json_decode($this->attributes['socials'], true);
    }
    //endregion

    //region Model config
    protected $table = 'channels';
    protected $fillable = [
        'user_id',
        'name',
        'info',
        'banner',
        'socials'
    ];
    //endregion
}
