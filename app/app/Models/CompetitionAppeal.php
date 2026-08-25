<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetitionAppeal extends Model
{
    protected $fillable = [
        'user_id',
        'competition_video_id',
        'appeal_text',
        'status',
    ];

    public function competitionVideo()
    {
        return $this->belongsTo(CompetitionVideo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
