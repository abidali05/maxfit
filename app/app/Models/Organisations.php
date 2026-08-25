<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organisations extends Model
{
    protected $table = 'organisations';
    protected $guarded = [];
    
    public function organisation_type()
    {
        return $this->belongsTo(OrganisationTypes::class, 'type');
    }

    
}
