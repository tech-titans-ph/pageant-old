<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bestin extends Model
{
    protected $guarded = [];

    public function contest()
    {
        return $this->belongsTo(Contest::class);
    }

    public function group()
    {
        return $this->morphTo(__FUNCTION__, 'type', 'type_id', 'id');
    }
}
