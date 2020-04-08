<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //region Relations
    /**
     * manyToOne relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(Category::class);
    }
    //endregion

    //region Model config
    protected $table = 'categories';

    protected $fillable = [
        'title',
        'icon',
        'banner',
        'user_id'
    ];
    //endregion
}
