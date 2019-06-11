<?php

namespace App;

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

    public function categories()
    {
        return $this->belongsToMany(ContestCategory::class, 'category_judges');
    }
    
    public function categoryJudges()
    {
        return $this->hasMany(CategoryJudge::class);
    }
}
