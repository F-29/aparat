<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    public function videos()
    {
        return $this->belongsToMany(Video::class, 'playlist_videos');
    }

    protected $table = 'playlists';
    protected $fillable = [
        'title',
        'user_id',
    ];
}
