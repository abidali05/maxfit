<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExerciseSubGroup extends Model
{
    protected $guarded = [];

    public function group()
    {
        return $this->belongsTo(ExerciseGroup::class, 'exercise_group_id');
    }

    public function items()
    {
        return $this->hasMany(ExerciseSubGroupItem::class, 'exercise_sub_group_id')->orderBy('order');
    }

    public function exercises()
    {
        return $this->belongsToMany(Exercise::class, 'exercise_sub_group_items', 'exercise_sub_group_id', 'exercise_id')
            ->withPivot('order')
            ->withTimestamps()
            ->orderByPivot('order', 'asc');
    }
}
