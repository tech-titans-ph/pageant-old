<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    protected $guarded = [];
    public function contest_categories()
    {
        return $this->belongsToMany(ContestCategory::class, 'contest_category_criterias');
    }
}
