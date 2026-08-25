<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExerciseCategory;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Contracts\ExerciseCategoryRepositoryInterface;

class ExerciseCategoryController extends Controller
{
    protected $exercise;

    public function __construct(ExerciseCategoryRepositoryInterface $exercise)
    {
        $this->exercise = $exercise;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $exercise_categories = $this->exercise->get_exercise_categories();
        return view('exercise_categories.index', compact('exercise_categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('exercise_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'tag' => 'required|string|in:popular,new_arrival,limited_edition,featured',
                'description' => 'nullable|string',
                'overall_time' => 'nullable|string|max:255',
                'over_kcal' => 'nullable|string|max:255',
                'exerice_lvl' => 'nullable|string|in:beginner,intermediate,advanced',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $data = [
                'name' => $validated['name'],
                'tag' => $validated['tag'],
                'description' => $validated['description'] ?? null,
                'overall_time' => $validated['overall_time'] ?? null,
                'over_kcal' => $validated['over_kcal'] ?? null,
                'exerice_lvl' => $validated['exerice_lvl'] ?? null,
            ];


            // Handle image upload if provided
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('exercise_categories', 'public');
                $data['image'] = $imagePath;
            }

            // Save the data using the service method
            $this->exercise->create_exercise_category($data);

            // Success message
            Toastr::success('Exercise Category created successfully', 'Success');
            return redirect()->route('exercise-categories.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            Toastr::error('Validation failed. Please check the form.', 'Error');
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            // Handle other errors (e.g., database issues)
            Toastr::error('Failed to create exercise category: ' . $e->getMessage(), 'Error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ExerciseCategory $exerciseCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExerciseCategory $exerciseCategory)
    {
        $exercise_category = $this->exercise->get_exercise_category($exerciseCategory->id);
        return view('exercise_categories.edit', compact('exercise_category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExerciseCategory $exerciseCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tag' => 'required|string|in:popular,new_arrival,limited_edition,featured',
            'overall_time' => 'nullable|string|max:255',
            'over_kcal' => 'nullable|string|max:255',
            'exerice_lvl' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($exerciseCategory->image && Storage::exists($exerciseCategory->image)) {
                Storage::delete($exerciseCategory->image);
            }

            // Store new image
            $validated['image'] = $request->file('image')->store('exercise_categories', 'public');
        }

        // Update via service or repository
        $this->exercise->update_exercise_category($exerciseCategory->id, $validated);

        Toastr::success('Exercise Category updated successfully', 'Success');
        return redirect()->route('exercise-categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExerciseCategory $exerciseCategory)
    {
        $this->exercise->delete_exercise_category($exerciseCategory->id);
        Toastr::success('Exercise Category deleted successfully', 'Success');
        return redirect()->route('exercise-categories.index');
    }
}
