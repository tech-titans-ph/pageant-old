<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Judge extends Authenticatable
{
    protected $guarded = [];

    public function contest()
    {
        return $this->belongsTo(Contest::class);
    }

    public function categoryJudges()
    {
        return $this->hasMany(CategoryJudge::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_judges')
            ->using(CategoryJudge::class)
            ->withPivot(['id'])
            ->withTimestamps();
    }
}
