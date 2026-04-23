<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'challenger_id',
        'challenge_to_id',
        'venue_type',
        'venue_id',
        'name',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'status',
    ];

    // Relation: who created this challenge
    public function challenger()
    {
        return $this->belongsTo(User::class, 'challenger_id');
    }

    // Relation: who is challenged
    public function challengeTo()
    {
        return $this->belongsTo(User::class, 'challenge_to_id');
    }

    // Relation: all exercises linked to this challenge
    public function challengeExercises()
    {
        return $this->hasMany(ChallengeExercise::class, 'challenge_id', 'id');
    }
}
