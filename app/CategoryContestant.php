<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CategoryContestant extends Pivot
{
    protected $table = 'category_contestants';

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function contestant()
    {
        return $this->belongsTo(Contestant::class);
    }
}
