<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class channel extends Model
{
    protected $table = 'channels';
    protected $fillable = [
        'user_id',
        'name',
        'info',
        'banner',
        'socials'
    ];
}
