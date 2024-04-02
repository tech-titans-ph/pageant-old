<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Contestant extends Model
{
    protected $guarded = [];

    public function contest()
    {
        return $this->belongsTo(Contest::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_contestants')
            ->using(CategoryContestant::class)
            ->withPivot(['id'])
            ->withTimestamps();
    }

    public function getAvatarUrlAttribute()
    {
        return Storage::url($this->avatar);
    }
}
