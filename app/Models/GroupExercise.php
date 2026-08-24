<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupExercise extends Model
{
    protected $guarded = [];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}
