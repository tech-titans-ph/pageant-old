<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        return $this->hasMany(Category::class);
    }

    public function getLogoUrlAttribute()
    {
        return Storage::url($this->logo);
    }
}
