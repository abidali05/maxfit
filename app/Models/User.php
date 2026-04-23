<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];
    protected $appends = ['country_name', 'state_province_name', 'city_name', 'organisation_name', 'organisation_type_name'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'assessment' => 'boolean',
            'goal_setting' => 'boolean',
            'is_age_changed' => 'boolean',
            'profile_updated_for_father_fit' => 'boolean',
            'age_at_registration' => 'integer',
        ];
    }

    public function getImageAttribute($value)
    {
        if (empty($value)) {
            return asset('assets/images/user.jpg');
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        return asset('storage/' . ltrim($value, '/'));
    }

    public function getCountryNameAttribute()
    {
        return Country::find($this->country)->name ?? '';
    }

    public function countryRelation()
    {
        return $this->belongsTo(Country::class, 'country');
    }

    public function getStateProvinceNameAttribute()
    {
        return Province::find($this->state_province)->name ?? '';
    }

    public function getCityNameAttribute()
    {
        return City::find($this->city)->name ?? '';
    }

    public function getOrganisationNameAttribute()
    {
        return Organisations::find($this->organisation_id)->name ?? '';
    }

    public function getOrganisationTypeNameAttribute()
    {
        return OrganisationTypes::find($this->organisation_type)->name ?? '';
    }

    public function organisation()
    {
        return $this->belongsTo(Organisations::class, 'organisation_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function getBranchNameAttribute()
    {
        return $this->branch?->name ?? $this->attributes['branch_name'] ?? '';
    }

    public function physical_assessment()
    {
        return $this->hasMany(PhysicalAssessment::class, 'user_id', 'id');
    }
}
