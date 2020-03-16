<?php

namespace App;

use App\CategoryJudge;
use App\Contest;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Judge extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contest()
    {
        return $this->belongsTo(Contest::class);
    }

    public function categoryJudges()
    {
        return $this->hasMany(CategoryJudge::class);
    }
}
