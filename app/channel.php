<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class channel extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function setSocialsAttribute($value)
    {
        if (is_array($value)) {
            /** @noinspection PhpComposerExtensionStubsInspection */
            $value = json_encode($value);
        }

        $this->attributes['socials'] = $value;
    }

    public function getSocialsAttribute()
    {
        /** @noinspection PhpComposerExtensionStubsInspection */
        return json_decode($this->attributes['socials'], true);
    }

    protected $table = 'channels';
    protected $fillable = [
        'user_id',
        'name',
        'info',
        'banner',
        'socials'
    ];
}
