<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'set_id',
        'count',
        'exercise_id',
    ];

    // 🧩 Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function set()
    {
        return $this->belongsTo(Set::class);
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}
