<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetExercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'set_id',
        'exercise_id',
        'competition_user_id',
        'score',
        'sequence',
    ];

    public function set()
    {
        return $this->belongsTo(Set::class, 'set_id');
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class, 'exercise_id');
    }
}
