<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class LikedVideo extends Pivot
{
    protected $table = "video_favourites";
    protected $fillable = [
        'user_id',
        'video_id'
    ];
}
