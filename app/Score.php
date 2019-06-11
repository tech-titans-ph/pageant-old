<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $guarded = [];
    
    public function category()
    {
        return $this->belongsTo(ContestCategory::class);
    }

    public function categoryCriteria()
    {
        return $this->belongsTo(CategoryCriteria::class);
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
