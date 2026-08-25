<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetitionResult extends Model
{
    protected $guarded = [];

    public function competitionUser()
    {
        return $this->belongsTo(CompetitionUser::class);
    }

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }

    public function videos()
    {
        return $this->hasMany(CompetitionResultVideo::class);
    }

    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            CompetitionUser::class,
            'id',           // Foreign key on competition_users
            'id',           // Foreign key on users
            'competition_user_id', // Local key on competition_results
            'user_id'       // Local key on competition_users
        );
    }
}
