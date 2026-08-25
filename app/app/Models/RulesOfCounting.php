<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RulesOfCounting extends Model
{
    protected $fillable = [
        'competition_id',
        'custom_exercise_name',
        'image_file',
        'video_file',
        'description',
    ];

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }
}
