<?php

namespace App\Http\Controllers;

use App\Models\Set;
use App\Models\Exercise;
use Illuminate\Http\Request;

class SetsController extends Controller
{
    private function validateSetRequest(Request $request): array
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'genz' => 'required|in:fatherfits,motherfits',
            'fitness_level' => 'required|in:Expert,Immature',
            'gender' => 'required|in:Male,Female,Other',
            'exercise_ids' => 'required|array|min:1',
            'exercise_ids.*' => 'exists:exercises,id',
            'sequences' => 'required|array|min:1',
            'sequences.*' => 'integer|min:1'
        ]);

        $matchedExerciseIds = Exercise::query()
            ->matchingCriteria($validated['genz'], $validated['fitness_level'], $validated['gender'])
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $invalidIds = collect($validated['exercise_ids'])
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => !in_array($id, $matchedExerciseIds, true))
            ->values()
            ->all();

        if ($invalidIds !== []) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'exercise_ids' => ['Selected exercises must match set criteria (genz, exercise type, gender).'],
            ]);
        }

        if (count($validated['exercise_ids']) !== count($validated['sequences'])) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'sequences' => ['Sequence values must match selected exercises.'],
            ]);
        }

        return $validated;
    }

    public function index()
    {
        $sets = Set::with('exercises')->get();
        return view('sets.index', compact('sets'));
    }

    public function create()
    {
        return view('sets.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateSetRequest($request);

        $set = Set::create([
            'name' => $validated['name'],
            'genz' => $validated['genz'],
            'fitness_level' => $validated['fitness_level'],
            'gender' => $validated['gender'],
        ]);

        // Attach exercises with sequence
        $exerciseData = [];
        foreach ($validated['exercise_ids'] as $index => $exerciseId) {
            $exerciseData[$exerciseId] = ['sequence' => $validated['sequences'][$index]];
        }
        $set->exercises()->attach($exerciseData);

        return redirect()->route('sets.index')->with('success', 'Set created successfully!');
    }

    public function edit(string $id)
    {
        $set = Set::with('exercises')->findOrFail($id);
        return view('sets.edit', compact('set'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $this->validateSetRequest($request);

        $set = Set::findOrFail($id);
        $set->update([
            'name' => $validated['name'],
            'genz' => $validated['genz'],
            'fitness_level' => $validated['fitness_level'],
            'gender' => $validated['gender'],
        ]);

        // Sync exercises with sequence
        $exerciseData = [];
        foreach ($validated['exercise_ids'] as $index => $exerciseId) {
            $exerciseData[$exerciseId] = ['sequence' => $validated['sequences'][$index]];
        }
        $set->exercises()->sync($exerciseData);

        return redirect()->route('sets.index')->with('success', 'Set updated successfully!');
    }

    public function destroy(string $id)
    {
        $set = Set::findOrFail($id);
        $set->delete();
        return redirect()->route('sets.index')->with('success', 'Set deleted successfully!');
    }

    public function getExercisesByCriteria(Request $request)
    {
        $validated = $request->validate([
            'genz' => 'required|in:fatherfits,motherfits,both',
            'fitness_level' => 'required|in:Expert,Amateur,both',
            'gender' => 'required|in:Male,Female,both',
        ]);

        $exercises = Exercise::query()
            ->matchingCriteria($validated['genz'], $validated['fitness_level'], $validated['gender'])
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($exercises);
    }
}
