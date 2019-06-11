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
    
    public function contestCategories()
    {
        return $this->hasMany(ContestCategory::class);
    }
}
