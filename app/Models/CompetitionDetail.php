<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

    public function selectedUsers()
    {
        return $this->belongsToMany(User::class, 'competition_detail_user', 'competition_detail_id', 'user_id')
            ->withTimestamps();
    }

    public function getResolvedSelectedUserIdsAttribute(): array
    {
        $ids = $this->relationLoaded('selectedUsers')
            ? $this->selectedUsers->pluck('id')->map(fn ($id) => (int) $id)->all()
            : [];

        if (Schema::hasTable('competition_detail_users')) {
            $legacyIds = DB::table('competition_detail_users')
                ->where('competition_detail_id', $this->id)
                ->pluck('user_id')
                ->map(fn ($id) => (int) $id)
                ->all();
            $ids = array_merge($ids, $legacyIds);
        }

        return array_values(array_unique(array_filter($ids)));
    }
}
