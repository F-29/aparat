<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function video()
    {
        return $this->belongsToMany(Video::class, 'video_tags');
    }

    protected $table = 'tags';
    protected $fillable = ['title'];
}
