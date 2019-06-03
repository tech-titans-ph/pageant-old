<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryCriteria extends Model
{
    protected $guarded = [];
    
    public function criteria()
    {
        return $this->belongsTo(Criteria::class);
    }
    
    public function scores()
    {
        return $this->hasMany(Score::class);
    }
}
