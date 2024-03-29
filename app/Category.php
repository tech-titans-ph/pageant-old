<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    public function contest()
    {
        return $this->belongsTo(Contest::class);
    }

    public function judges()
    {
        return $this->belongsToMany(Judge::class, 'category_judges')
            ->using(CategoryJudge::class)
            ->withPivot(['id'])
            ->withTimestamps();
    }

    public function criterias()
    {
        return $this->hasMany(Criteria::class);
    }

    public function categoryScores()
    {
        return $this->hasMany(CategoryScore::class);
    }

    public function categoryContestants()
    {
        return $this->hasMany(CategoryContestant::class);
    }
}
