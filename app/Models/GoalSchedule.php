<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoalSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'goal_id',
        'scheduled_date',
        'days',
        'exercises'
    ];

    protected $casts = [
        'days' => 'array',
        'exercises' => 'array',
        'scheduled_date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }
}