<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Coach;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CoachController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filters = request()->only([
            'name',
            'email',
            'phone',
            'country_id',
            'city_id',
        ]);

        $query = Coach::with(['country', 'city']);

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['email'])) {
            $query->where('email', 'like', '%' . $filters['email'] . '%');
        }

        if (!empty($filters['phone'])) {
            $query->where('phone', 'like', '%' . $filters['phone'] . '%');
        }

        if (!empty($filters['country_id'])) {
            $query->where('country_id', $filters['country_id']);
        }

        if (!empty($filters['city_id'])) {
            $query->where('city_id', $filters['city_id']);
        }

        $coaches = $query->latest()->get();
        $countries = Country::orderBy('name')->get();
        $cities = City::orderBy('name')->get();

        return view('coaches.index', compact('coaches', 'countries', 'cities', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::all();
        $cities = City::all();
        return view('coaches.create', compact('countries', 'cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'       => 'required|string|max:255',
                'email'      => 'required|email|unique:coaches,email',
                'phone'      => 'required|string|max:20',
                'image'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'bio'        => 'nullable|string',
                'address'    => 'required|string|max:255',
                'country_id' => 'required|exists:countries,id',
                'city_id'    => 'required|exists:cities,id',
            ]);

            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('coaches', 'public');
            }

            // default password
            $validated['password'] = Hash::make('password');

            Coach::create($validated);

            return redirect()->route('coaches.index')->with('success', 'Coach created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Coach $coach)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $coach = Coach::findOrFail($id);
        $countries = Country::all();
        $cities = City::all();
        return view('coaches.edit', compact('coach', 'countries', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $coach = Coach::findOrFail($id);

            $validated = $request->validate([
                'name'       => 'required|string|max:255',
                'email'      => 'required|email|unique:coaches,email,' . $coach->id,
                'phone'      => 'nullable|string|max:20',
                'image'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'bio'        => 'nullable|string',
                'address'    => 'nullable|string|max:255',
                'country_id' => 'required|exists:countries,id',
                'city_id'    => 'required|exists:cities,id',
            ]);

            if ($request->hasFile('image')) {
                // delete old image if exists
                if ($coach->image && Storage::disk('public')->exists($coach->image)) {
                    Storage::disk('public')->delete($coach->image);
                }
                $validated['image'] = $request->file('image')->store('coaches', 'public');
            } else {
                $validated['image'] = $coach->image;
            }

            // Keep existing password instead of resetting on update
            $validated['password'] = $coach->password;

            $coach->update($validated);

            return redirect()->route('coaches.index')->with('success', 'Coach updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage())->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $coach = Coach::findOrFail($id);
        if ($coach->image && Storage::disk('public')->exists($coach->image)) {
            Storage::disk('public')->delete($coach->image);
        }
        $coach->delete();
        return redirect()->route('coaches.index')->with('success', 'Coach deleted successfully.');
    }
}
