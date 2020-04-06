<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function user()
    {
        return $this->belongsTo(Category::class);
    }

    protected $table = 'categories';

    protected $fillable = [
        'title',
        'icon',
        'banner',
        'user_id'
    ];
}
