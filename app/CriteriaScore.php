<?php

namespace App;

use App\CategoryScore;
use App\Criteria;
use Illuminate\Database\Eloquent\Model;

class CriteriaScore extends Model
{
    protected $guarded = [];

    public function categoryScore()
    {
        return $this->belongsTo(CategoryScore::class);
    }

    public function criteria()
    {
        return $this->belongsTo(Criteria::class);
    }
}
