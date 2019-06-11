<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    protected $guarded = [];
    
    public function contestants()
    {
        return $this->hasMany(Contestant::class);
    }

    public function judges()
    {
        return $this->hasMany(Judge::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'contest_categories')->withPivot('id', 'percentage', 'status');
    }
    
    public function contestCategories()
    {
        return $this->hasMany(ContestCategory::class);
    }
}
