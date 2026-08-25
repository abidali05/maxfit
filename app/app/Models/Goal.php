<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $guarded = [];

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }

    public function set()
    {
        return $this->belongsTo(Set::class);
    }
}
