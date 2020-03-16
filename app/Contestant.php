<?php

namespace App;

use App\CategoryContestant;
use App\Contest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Contestant extends Model
{
    protected $guarded = [];

    public function contest()
    {
        return $this->belongsTo(Contest::class);
    }

    public function categoryContestants()
    {
        return $this->hasMany(CategoryContestant::class);
    }

    public function getPictureUrlAttribute()
    {
        return Storage::url($this->picture);
    }
}
