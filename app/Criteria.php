<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    protected $guarded = [];
    
    public function category_criterias()
    {
        return $this->hasMany(CategoryCriteria::class);
    }
}
