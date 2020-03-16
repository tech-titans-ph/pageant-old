<?php

namespace App;

use App\CategoryContestant;
use App\CategoryJudge;
use App\CategoryScore;
use App\Contest;
use App\Criteria;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    public function contest()
    {
        return $this->belongsTo(Contest::class);
    }

    public function criterias()
    {
        return $this->hasMany(Criteria::class);
    }

    public function categoryScores()
    {
        return $this->hasMany(CategoryScore::class);
    }

    public function categoryJudges()
    {
        return $this->hasMany(CategoryJudge::class);
    }

    public function categoryContestants()
    {
        return $this->hasMany(CategoryContestant::class);
    }
}
