<?php

namespace App;

use App\Category;
use App\CategoryContestant;
use App\CategoryJudge;
use App\CriteriaScore;
use Illuminate\Database\Eloquent\Model;

class CategoryScore extends Model
{
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function categoryJudge()
    {
        return $this->belongsTo(CategoryJudge::class);
    }

    public function categoryContestant()
    {
        return $this->belongsTo(CategoryContestant::class);
    }

    public function criteriaScores()
    {
        return $this->hasMany(CriteriaScore::class);
    }
}
