<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $guarded = [];

    public function exercise_category()
    {
        return $this->belongsTo(ExerciseCategory::class);
    }

    public function goals()
    {
        return $this->hasMany(Goal::class);
    }

    public function competitions()
    {
        return $this->belongsToMany(Competition::class, 'competition_exercises', 'exercise_id', 'competition_id');
    }

    public function sets()
    {
        return $this->belongsToMany(Set::class, 'set_exercises');
    }

    public function getGenzAttribute($value)
    {
        if (strtolower($value) === 'fatherfits') {
            return 'Father Fit';
        } elseif (strtolower($value) === 'motherfits') {
            return 'Mother Fit';
        }

        return $value; // return as-is if not matched
    }

    public function setExercises()
{
    return $this->hasMany(SetExercise::class, 'exercise_id');
}

}
