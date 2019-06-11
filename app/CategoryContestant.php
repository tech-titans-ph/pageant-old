<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryContestant extends Model
{
    protected $guarded = [];
    
    public function contestant()
    {
        return $this->belongsTo(Contestant::class);
	}
	
	public function scores()
	{
		return $this->hasMany(Score::class);
	}

	public function contestCategory()
	{
		return $this->belongsTo(ContestCategory::class);
	}

}
