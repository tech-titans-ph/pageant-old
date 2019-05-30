<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contestant extends Model
{
	protected $guarded = [];
	
	public function contest()
	{
		return $this->belongsTo(Contest::class);
	}

	public function categories()
	{
		return $this->belongsToMany(ContestCategory::class, 'category_contestants');
	}
}
