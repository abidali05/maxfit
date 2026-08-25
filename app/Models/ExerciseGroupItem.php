<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExerciseGroupItem extends Model
{
    protected $guarded = [];

    public function group()
    {
        return $this->belongsTo(ExerciseGroup::class, 'exercise_group_id');
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class, 'exercise_id');
    }
}
