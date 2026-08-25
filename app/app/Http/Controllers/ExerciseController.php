<?php

namespace App\Http\Controllers;

use getID3;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Contracts\ExerciseRepositoryInterface;

class ExerciseController extends Controller
{
    protected $exe;

    public function __construct(ExerciseRepositoryInterface $exe)
    {
        $this->exe = $exe;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $exercises = $this->exe->get_exercises();
        return view('exercises.index', compact('exercises'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $exercises = $this->exe->get_exercise_caetegories();
        return view('exercises.create', compact('exercises'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'genz' => 'required|string|max:255',
            'exercise_category_id' => [
                'required',
                'integer',
                Rule::exists('exercise_categories', 'id'),
            ],
            'exercise_type' => 'required|string|in:Per Second,Per Count',
            'fitness_level' => 'required|string|in:Expert,Amateur,both',
            'gender' => 'required|string|in:Male,Female,both',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'youtube_link' => 'nullable|url', // Validate as video_file from frontend
        ]);

        // Begin database transaction
        DB::beginTransaction();
        try {
            // Handle image upload
            if (isset($validated['image']) && $validated['image']->isValid()) {
                $validated['image'] = $validated['image']->store('uploads/exercises', 'public');
            }

            // Create the exercise
            $category = DB::table('exercise_categories')->find($validated['exercise_category_id']);

            if (!$category) {
                Toastr::error('Selected category does not exist!', 'Error');
                return back()->withInput();
            }
            $this->exe->create_exercise($validated);

            DB::commit();
            Toastr::success('Exercise created successfully', 'Success');
            return redirect()->route('exercises.index');
        } catch (QueryException $e) {
            DB::rollBack();
            // Handle foreign key constraint violation specifically
            if ($e->getCode() == '23000') {
                Toastr::error('Invalid exercise category selected. Please choose a valid category.', 'Error');
            } else {
                Toastr::error('Failed to create exercise: ' . $e->getMessage(), 'Error');
            }
            Log::error('Exercise creation failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to create exercise.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error('Failed to create exercise: ' . $e->getMessage(), 'Error');
            Log::error('Exercise creation failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to create exercise.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Exercise $exercise)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Exercise $exercise)
    {
        $exercises = $this->exe->get_exercise_caetegories();
        return view('exercises.edit', compact('exercises', 'exercise'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, Exercise $exercise)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'genz' => 'required|string|max:255',
                'exercise_category_id' => 'required|string',
                'exercise_type' => 'required|string|in:Per Second,Per Count',
                'fitness_level' => 'required|string|in:Expert,Amateur,both',
                'gender' => 'required|string|in:Male,Female,both',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'youtube_link' => 'nullable|url'
            ]);

            // Handle Image Upload
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                // Delete old image if exists
                if ($exercise->image && Storage::disk('public')->exists($exercise->image)) {
                    Storage::disk('public')->delete($exercise->image);
                }

                $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
                $path = $request->file('image')->storeAs('uploads/exercises', $imageName, 'public');
                $validated['image'] = $path;
            } else {
                unset($validated['image']); // don't overwrite image if no new one
            }

            // Update exercise
            $this->exe->update_exercise($exercise->id, $validated);

            Toastr::success('Exercise updated successfully', 'Success');
            return redirect()->route('exercises.index');
        } catch (\Exception $e) {
            Toastr::error('Failed to update exercise. ' . $e->getMessage(), 'Error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exercise $exercise)
    {
        if (!$exercise) {
            return false;
        }

        if ($exercise->image) {
            $imagePath = str_replace(asset('storage') . '/', '', $exercise->image);
            $fullPath = public_path('storage/' . $imagePath);

            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }

        $this->exe->delete_exercise($exercise->id);

        Toastr::success('Exercise deleted successfully', 'Success');
        return redirect()->route('exercises.index');
    }
}
