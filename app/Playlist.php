<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    //region Relations
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function videos()
    {
        return $this->belongsToMany(Video::class, 'playlist_videos');
    }
    //endregion

    //region Model config
    protected $table = 'playlists';
    protected $fillable = [
        'title',
        'user_id',
    ];
    //endregion
}
