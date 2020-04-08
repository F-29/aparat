<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    //region Relations
    /**
     * oneToMany relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function playlist()
    {
        return $this->belongsToMany(Playlist::class, 'playlist_videos');
        // in curse video: added a ->first() in the end
    }

    /**
     * oneToMany relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'video_tags');
    }
    //endregion

    //region Model config
    protected $table = 'videos';
    protected $fillable = [
        'title', 'user_id', 'category_id', 'channel_category_id',
        'slug', 'info', 'duration', 'banner', 'publish_at',
    ];
    //endregion
}
