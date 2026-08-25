<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\City;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class VenueController extends Controller
{
    public function index()
    {
        $filters = request()->only([
            'name',
            'city_id',
        ]);

        $query = Venue::with('city');

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['city_id'])) {
            $query->where('city_id', $filters['city_id']);
        }

        $venues = $query->latest()->get();
        $cities = City::orderBy('name')->get();

        return view('venues.index', compact('venues', 'cities', 'filters'));
    }

    public function create()
    {
        $cities = City::all();
        return view('venues.create', compact('cities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
        ]);

        Venue::create($validated);
        Toastr::success('Venue created successfully', 'Success');
        return redirect()->route('venues.index');
    }

    public function edit(Venue $venue)
    {
        $cities = City::all();
        return view('venues.edit', compact('venue', 'cities'));
    }

    public function update(Request $request, Venue $venue)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
        ]);

        $venue->update($validated);
        Toastr::success('Venue updated successfully', 'Success');
        return redirect()->route('venues.index');
    }

    public function destroy(Venue $venue)
    {
        $venue->delete();
        Toastr::success('Venue deleted successfully', 'Success');
        return redirect()->route('venues.index');
    }
}
