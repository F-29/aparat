<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class channel extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
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
