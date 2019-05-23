<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    protected $guarded = [];

    public function contest_categories()
    {
        return $this->hasMany(ContestCategory::class);
    }

    public function contestants()
    {
        return $this->hasMany(Contestant::class);
    }

    public function judges()
    {
        return $this->hasMany(User::class);
    }
    
}
