<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function criteria()
    {
        return $this->belongsTo(Criteria::class);
    }

    public function categoryJudge()
    {
        return $this->belongsTo(CategoryJudge::class);
    }

    public function categoryContestant()
    {
        return $this->belongsTo(CategoryContestant::class);
    }
}
