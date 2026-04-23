<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetitionUserTotal extends Model
{
    protected $table = 'competition_user_totals';

    protected $fillable = [
        'competition_user_id',
        'total_score',
        'rank',
    ];


    public function competitionUser()
    {
        return $this->belongsTo(CompetitionUser::class);
    }
}
