<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserExerciseAssessment extends Model
{

    protected $table = 'user_exercise_assessment';

    protected $fillable = ['user_id', 'exercise_id', 'assessment_data'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}
