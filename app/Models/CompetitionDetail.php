<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetitionDetail extends Model
{
    protected $fillable = [
        'competition_id',
        'coach_id',
        'city_id',
        'venue_id',
        'city',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'image',
        'description',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i', // Cast to time format
        'end_time' => 'datetime:H:i',   // Cast to time format
    ];

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function competitionUsers()
    {
        return $this->hasMany(CompetitionUser::class, 'competition_detail_id');
    }

    public function coach()
    {
        return $this->belongsTo(Coach::class, 'coach_id');
    }

    public function cityRelation()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function venueRelation()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }
}
