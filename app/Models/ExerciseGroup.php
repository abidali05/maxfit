<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExerciseGroup extends Model
{
    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(ExerciseGroupItem::class, 'exercise_group_id')->orderBy('order');
    }

    public function exercises()
    {
        return $this->belongsToMany(Exercise::class, 'exercise_group_items', 'exercise_group_id', 'exercise_id')
            ->withPivot('order')
            ->withTimestamps()
            ->orderByPivot('order');
    }
}
