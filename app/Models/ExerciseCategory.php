<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExerciseCategory extends Model
{
    protected $table = 'exercise_categories';
    protected $guarded = [];

    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }
}
