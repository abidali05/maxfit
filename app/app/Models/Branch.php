<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Branch extends Authenticatable
{
    use HasFactory;

    protected $table = 'branches';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'image',
        'bio',
        'status',
        'address',
        'percentage',
        'country_id',
        'code',
        'city_id',
    ];


    protected $hidden = [
        'password',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'branch_id');
    }
}
