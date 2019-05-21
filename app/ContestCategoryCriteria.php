<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContestCategoryCriteria extends Model
{
    public function scores()
    {
        return $this->hasMany(Score::class);
    }
}
