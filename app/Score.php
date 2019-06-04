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
    
    public function category_criteria()
    {
        return $this->belongsTo(CategoryCriteria::class);
    }
    
    public function category_judge()
    {
        return $this->belongsTo(CategoryJudge::class);
    }
}
