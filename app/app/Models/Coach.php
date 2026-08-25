<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Coach extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'image',
        'bio',
        'address',
        'country_id',
        'city_id',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
