<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContestCategory extends Model
{
    protected $guarded = [];

    public function contest()
    {
        return $this->belongsTo(Contest::class);
    }

    public function contestants()
    {
        return $this->belongsToMany(Contestant::class, 'category_contestants')->withPivot('id');
    }

    public function judges()
    {
        return $this->belongsToMany(User::class, 'category_judges')->withPivot('id');
    }

    public function criterias()
    {
        return $this->belongsToMany(Criteria::class, 'category_criterias')->withPivot('id', 'percentage');
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function scores()
    {
        return $this->hasMany(Score::class);
    }
}
