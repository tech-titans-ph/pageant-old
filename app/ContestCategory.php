<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContestCategory extends Model
{
    public function contest()
    {
        return $this->belongsTo(Contest::class);
    }
    public function contestants()
    {
        return $this->belongsToMany(Contestant::class, 'contest_category_contestants');
    }
    public function criterias()
    {
        return $this->belongsToMany(Criteria::class, 'contest_category_criterias');
    }
    public function judges()
    {
        return $this->belongsToMany(User::class, 'contest_category_judges');
    }
    public function scores()
    {
        return $this->hasMany(Score::class);
    }
}
