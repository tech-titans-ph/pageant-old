<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
	protected $guarded = [];
	
	public function category()
	{
		return $this->belongsTo(ContestCategory::class);
	}
}
