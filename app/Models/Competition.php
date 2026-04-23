<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    protected $guarded = [];

    protected $appends = [
        'country_name',
        'organization_type_names',
    ];

    protected $casts = [
        'has_entry_fee' => 'boolean',
        'entry_fee' => 'decimal:2'
    ];

    public function videos()
    {
        return $this->hasMany(CompetitionVideo::class);
    }

    public function competitionResult()
    {
        return $this->hasMany(CompetitionResult::class);
    }

    public function details()
    {
        return $this->hasMany(CompetitionDetail::class);
    }

    public function competitionDetail()
    {
        return $this->hasOne(CompetitionDetail::class, 'competition_id');
    }

    public function competitionUsers()
    {
        return $this->hasMany(CompetitionUser::class);
    }

    public function exercises()
    {
        return $this->belongsToMany(Exercise::class, 'competition_exercises', 'competition_id', 'exercise_id');
    }

    public function organisation()
    {
        return $this->belongsTo(Organisations::class, 'org');
    }

    public function organisations()
    {
        return $this->belongsToMany(
            Organisations::class,
            'competition_organisations',
            'competition_id',
            'organisation_id'
        );
    }

    public function organisationType()
    {
        return $this->belongsTo(OrganisationTypes::class, 'org_type');
    }

    public function countryRelation()
    {
        return $this->belongsTo(Country::class, 'country');
    }

    public function organizationTypes()
    {
        return $this->belongsToMany(
            OrganisationTypes::class,
            'competition_organisation_types',
            'competition_id',
            'organisation_type_id'
        );
    }

    public function getGenzAttribute($value)
    {
        if (strtolower($value) === 'fatherfits') {
            return 'Father Fit';
        } elseif (strtolower($value) === 'motherfits') {
            return 'Mother Fit';
        }

        return $value;
    }

    public function getCountryNameAttribute(): string
    {
        if (empty($this->country)) {
            return '';
        }

        if (is_numeric($this->country)) {
            return $this->countryRelation?->name ?? (string) $this->country;
        }

        return Country::query()
            ->where('name', $this->country)
            ->value('name') ?? (string) $this->country;
    }

    public function getOrganizationTypeNamesAttribute(): string
    {
        $names = $this->relationLoaded('organizationTypes')
            ? $this->organizationTypes->pluck('name')->filter()->values()
            : collect();

        if ($names->isNotEmpty()) {
            return $names->implode(', ');
        }

        return $this->organisationType?->name ?? '';
    }
}
