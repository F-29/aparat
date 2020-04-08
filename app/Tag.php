<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //region Relations
    /**
     * manyToOne relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function video()
    {
        return $this->belongsToMany(Video::class, 'video_tags');
    }
    //endregion

    //region Model config
    protected $table = 'tags';
    protected $fillable = ['title'];
    //endregion
}
