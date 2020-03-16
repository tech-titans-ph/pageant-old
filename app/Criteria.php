<?php

namespace App;

use App\Category;
use App\Score;
use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
