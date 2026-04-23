<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetitionUser extends Model
{
    protected $guarded = [];

    public function competitionDetail()
    {
        return $this->belongsTo(CompetitionDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function results()
    {
        return $this->hasMany(CompetitionResult::class);
    }

    public function total()
    {
        return $this->hasOne(CompetitionUserTotal::class, 'competition_user_id');
    }

    public function competitionResult()
    {
        return $this->hasOne(CompetitionResult::class);
    }
}
