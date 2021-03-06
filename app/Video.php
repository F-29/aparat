<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    //region Video state
    const STATE_PENDING = 'pending';
    const STATE_CONVERTED = 'converted';
    const STATE_ACCEPTED = 'accepted';
    const STATE_BLOCKED = 'blocked';
    const STATES = [self::STATE_PENDING, self::STATE_CONVERTED, self::STATE_ACCEPTED, self::STATE_BLOCKED];
    //endregion

    //region Relations

    /**
     * manyToOne relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

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
        'title', 'user_id', 'category_id', 'channel_category_id', 'state',
        'slug', 'info', 'duration', 'banner', 'publish_at', 'commentable', 'watermark'
    ];

    //endregion

    //region Override Model Method(s)
    public function getRouteKeyName()
    {
        return 'slug';
    }
    //endregion
}
