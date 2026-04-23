<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CompetitionAppeal;

class CompetitionResultVideo extends Model
{
    protected $guarded = [];

    public function result()
    {
        return $this->belongsTo(CompetitionResult::class, 'competition_result_id');
    }
    public function result_appel()
    {
        return $this->hasMany(CompetitionAppeal::class, 'competition_video_id');
    }
}
