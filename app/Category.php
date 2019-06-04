<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];
    
    public function contests()
    {
        return $this->belongsToMany(Contest::class, 'contest_categories');
    }
    
    public function contest_categories()
    {
        return $this->hasMany(ContestCategory::class);
    }
}
