<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChallengeExercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'challenge_id',
        'set_id',
        'exercise_id',
    ];

    // Relation: challenge
    public function challenge()
    {
        return $this->belongsTo(Challenge::class, 'challenge_id');
    }

    // Relation: set
    public function set()
    {
        return $this->belongsTo(Set::class, 'set_id');
    }

    // Relation: exercise
    public function exercise()
    {
        return $this->belongsTo(Exercise::class, 'exercise_id');
    }
}
