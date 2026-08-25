<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'coach_id',
        'name',
        'age_group',
        'gender',
        'genz',
        'country',
        'org_types',
        'orgs',
        'status',
        'instructions',
    ];

    protected $casts = [
        'org_types' => 'array',
        'orgs' => 'array',
    ];

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    public function groupUsers()
    {
        return $this->hasMany(GroupUser::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'group_users', 'group_id', 'user_id')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function groupExercises()
    {
        return $this->hasMany(GroupExercise::class);
    }

    public function countryRelation()
    {
        return $this->belongsTo(Country::class, 'country', 'id');
    }
}
