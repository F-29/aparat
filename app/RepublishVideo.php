<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class RepublishVideo extends Pivot
{
    protected $table = "video_republishes";
    protected $fillable = [
        'user_id',
        'video_id'
    ];
}
