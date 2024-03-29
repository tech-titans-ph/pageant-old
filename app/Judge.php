<?php

namespace App;

use App\{CategoryJudge, Contest};
use Illuminate\Foundation\Auth\User as Authenticatable;

class Judge extends Authenticatable
{
    protected $guarded = [];

    public function contest()
    {
        return $this->belongsTo(Contest::class);
    }

    public function categoryJudges()
    {
        return $this->hasMany(CategoryJudge::class);
    }
}
