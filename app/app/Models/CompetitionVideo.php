<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetitionVideo extends Model
{
    protected $guarded = [];

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }
}
