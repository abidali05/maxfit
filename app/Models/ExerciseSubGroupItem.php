<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExerciseSubGroupItem extends Model
{
    protected $guarded = [];

    public function subGroup()
    {
        return $this->belongsTo(ExerciseSubGroup::class, 'exercise_sub_group_id');
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class, 'exercise_id');
    }
}
