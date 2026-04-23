<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Constraint\Count;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filters = request()->only([
            'name',
            'email',
            'status',
            'city_id',
            'country_id',
        ]);

        $query = Branch::with(['country', 'city']);

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['email'])) {
            $query->where('email', 'like', '%' . $filters['email'] . '%');
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['city_id'])) {
            $query->where('city_id', $filters['city_id']);
        }

        if (!empty($filters['country_id'])) {
            $query->where('country_id', $filters['country_id']);
        }

        $branches = $query->latest()->get();
        $countries = Country::orderBy('name')->get();
        $cities = City::orderBy('name')->get();

        return view('branches.index', compact('branches', 'countries', 'cities', 'filters'));
    }

    private function generateUniqueCode()
    {
        do {
            $code = rand(100000, 999999);
        } while (Branch::where('code', $code)->exists());

        return $code;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $country = Country::all();
        $cities = City::all();
        return view('branches.create', compact('country', 'cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'        => 'required|string|max:255',
                'email'       => 'required|email|unique:branches,email',
                'phone'       => 'required|string|max:20',
                'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'bio'         => 'nullable|string',
                'address'     => 'required|string|max:255',
                'country_id'  => 'required|exists:countries,id',
                'city_id'     => 'required|exists:cities,id',
                'status'      => 'nullable|in:active,inactive',
                'percentage'  => 'required|numeric|min:0|max:100', // new field
            ]);

            // handle image
            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('branches', 'public');
            }
            $validated['code'] = $this->generateUniqueCode();

            // default password
            $validated['password'] = Hash::make('password');

            Branch::create($validated);

            return redirect()
                ->route('branches.index')
                ->with('success', 'Branch created successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create branch. ' . $e->getMessage());
        }
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $branch   = Branch::findOrFail($id);
        $countries = Country::all();
        $cities    = City::all();

        return view('branches.edit', compact('branch', 'countries', 'cities'));
    }

    public function update(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);

        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:branches,email,' . $branch->id,
            'phone'      => 'nullable|string|max:20',
            'image'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bio'        => 'nullable|string',
            'address'    => 'nullable|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'city_id'    => 'required|exists:cities,id',
            'status'     => 'required|in:active,inactive',
            'percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        // handle image
        if ($request->hasFile('image')) {
            if ($branch->image && Storage::disk('public')->exists($branch->image)) {
                Storage::disk('public')->delete($branch->image);
            }
            $validated['image'] = $request->file('image')->store('branches', 'public');
        } else {
            $validated['image'] = $branch->image;
        }

        // ⚠️ Don’t reset password on update unless intentional
        // $validated['password'] = Hash::make('password');

        $branch->update($validated);

        return redirect()
            ->route('branches.index')
            ->with('success', 'Branch updated successfully.');
    }

    /**
     * Display users for a specific branch.
     */
    public function users($id)
    {
        $branch = Branch::findOrFail($id);
        $users = $branch->users;
        return view('branches.users', compact('branch', 'users'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $branch = Branch::findOrFail($id);
        if ($branch->image && Storage::disk('public')->exists($branch->image)) {
            Storage::disk('public')->delete($branch->image);
        }
        $branch->delete();
        return redirect()->route('branches.index')->with('success', 'Branch deleted successfully.');
    }
}
