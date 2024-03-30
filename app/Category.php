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
            ->withPivot(['id', 'order', 'completed'])
            ->withTimestamps();
    }

    public function contestants()
    {
        return $this->belongsToMany(Contestant::class, 'category_contestants')
            ->using(CategoryContestant::class)
            ->withPivot(['id', 'order'])
            ->withTimestamps();
    }

    public function criterias()
    {
        return $this->hasMany(Criteria::class);
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }
}
