<?php

namespace App;

use App\Category;
use App\CategoryScore;
use App\Judge;
use Illuminate\Database\Eloquent\Model;

class CategoryJudge extends Model
{
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function judge()
    {
        return $this->belongsTo(Judge::class);
    }

    public function categoryScores()
    {
        return $this->hasMany(CategoryScore::class);
    }
}
