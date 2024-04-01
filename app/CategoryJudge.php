<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CategoryJudge extends Pivot
{
    protected $table = 'category_judges';

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function judge()
    {
        return $this->belongsTo(Judge::class);
    }

    public function scores()
    {
        return $this->hasMany(Score::class, 'category_judge_id', 'id');
    }
}
