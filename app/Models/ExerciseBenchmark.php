<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExerciseBenchmark extends Model
{
    protected $table = 'exercise_benchmarks';

    protected $fillable = [
        'exercise_key','exercise_name','gender','age_min','age_max',
        'p90','p80','p70','unit'
    ];
}
