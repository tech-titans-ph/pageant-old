<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    public function contest_category()
    {
        return $this->belongsTo(ContestCategory::class);
    }

    public function contest_category_contestant()
    {
        return $this->belongsTo(ContestCategoryContestant::class);
    }

    public function contest_category_criteria()
    {
        return $this->belongsTo(ContestCategoryCriteria::class);
    }

    public function contest_category_judge()
    {
        return $this->belongsTo(ContestCategoryJudge::class);
    }
    
}
