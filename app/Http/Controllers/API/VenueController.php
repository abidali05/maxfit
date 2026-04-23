<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Venue;

class VenueController extends Controller
{
    public function index()
    {
        $venues = Venue::with('city')->get();
        
        return response()->json([
            'status' => true,
            'message' => 'Venues retrieved successfully',
            'data' => $venues
        ]);
    }
}