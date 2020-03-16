<?php

namespace App;

use App\Category;
use App\CategoryScore;
use App\Contestant;
use Illuminate\Database\Eloquent\Model;

class CategoryContestant extends Model
{
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function contestant()
    {
        return $this->belongsTo(Contestant::class);
    }

    public function categoryScores()
    {
        return $this->hasMany(CategoryScore::class);
    }
}
