<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryJudge extends Model
{
    protected $guarded = [];
    
    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    public function judge()
    {
        return $this->belongsTo(Judge::class);
    }
}
