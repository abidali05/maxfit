<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Set extends Model
{
    protected $fillable = ['name', 'genz'];

    public function exercises()
    {
        return $this->belongsToMany(Exercise::class, 'set_exercises')
                    ->withPivot('sequence')
                    ->orderBy('set_exercises.sequence');
    }
    
    public function setExercises()
    {
        return $this->hasMany(SetExercise::class, 'set_id')
                    ->orderBy('sequence');
    }


    // public function getGenzAttribute($value)
    // {
    //     if (strtolower($value) === 'fatherfits') {
    //         return 'Father Fit';
    //     } elseif (strtolower($value) === 'motherfits') {
    //         return 'Mother Fit';
    //     }

    //     return $value;
    // }
}
