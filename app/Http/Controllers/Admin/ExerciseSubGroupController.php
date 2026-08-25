<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use App\Models\ExerciseCategory;
use App\Models\ExerciseGroup;
use App\Models\ExerciseSubGroup;
use App\Models\ExerciseSubGroupItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Brian2694\Toastr\Facades\Toastr;

class ExerciseSubGroupController extends Controller
{
    public function create(Request $request)
    {
        $groupId = $request->input('group_id');
        $group = ExerciseGroup::findOrFail($groupId);

        $categories = ExerciseCategory::with(['exercises' => function ($q) {
            $q->orderBy('name');
        }])->orderBy('name')->get();

        $uncategorizedExercises = Exercise::whereNull('exercise_category_id')
            ->orderBy('name')
            ->get();

        return view('exercise_sub_groups.create', compact('group', 'categories', 'uncategorizedExercises'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'exercise_group_id' => 'required|exists:exercise_groups,id',
            'name' => 'required|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'status' => 'required|in:active,inactive',
            'exercise_ids' => 'required|array|min:1',
            'exercise_ids.*' => 'required|exists:exercises,id',
        ]);

        DB::beginTransaction();
        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('exercise_sub_group_images', 'public');
            }

            $subGroup = ExerciseSubGroup::create([
                'exercise_group_id' => $request->exercise_group_id,
                'name' => $request->name,
                'sub_title' => $request->sub_title,
                'image' => $imagePath,
                'status' => $request->status ?? 'active',
                'order' => ExerciseSubGroup::where('exercise_group_id', $request->exercise_group_id)->count(),
            ]);

            $exerciseIds = $request->input('exercise_ids', []);
            foreach ($exerciseIds as $index => $exerciseId) {
                ExerciseSubGroupItem::create([
                    'exercise_sub_group_id' => $subGroup->id,
                    'exercise_id' => $exerciseId,
                    'order' => $index,
                ]);
            }

            DB::commit();
            Toastr::success('Sub Group created successfully', 'Success');
            return redirect()->route('exercise-groups.show', $subGroup->exercise_group_id);
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error('An error occurred while creating sub group: ' . $e->getMessage(), 'Error');
            return back()->withInput();
        }
    }

    public function edit($id)
    {
        $subGroup = ExerciseSubGroup::with(['group', 'exercises' => function ($q) {
            $q->orderByPivot('order', 'asc');
        }])->findOrFail($id);

        $group = $subGroup->group;
        $selectedExercises = $subGroup->exercises;
        $selectedExerciseIds = $selectedExercises->pluck('id')->toArray();

        $categories = ExerciseCategory::with(['exercises' => function ($q) {
            $q->orderBy('name');
        }])->orderBy('name')->get();

        $uncategorizedExercises = Exercise::whereNull('exercise_category_id')
            ->orderBy('name')
            ->get();

        return view('exercise_sub_groups.edit', compact('subGroup', 'group', 'selectedExercises', 'selectedExerciseIds', 'categories', 'uncategorizedExercises'));
    }

    public function update(Request $request, $id)
    {
        $subGroup = ExerciseSubGroup::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'status' => 'required|in:active,inactive',
            'exercise_ids' => 'required|array|min:1',
            'exercise_ids.*' => 'required|exists:exercises,id',
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'name' => $request->name,
                'sub_title' => $request->sub_title,
                'status' => $request->status ?? 'active',
            ];

            if ($request->hasFile('image')) {
                if ($subGroup->image && Storage::disk('public')->exists($subGroup->image)) {
                    Storage::disk('public')->delete($subGroup->image);
                }
                $data['image'] = $request->file('image')->store('exercise_sub_group_images', 'public');
            }

            $subGroup->update($data);

            // Sync exercises in preserved sequence
            $subGroup->items()->delete();
            $exerciseIds = $request->input('exercise_ids', []);
            foreach ($exerciseIds as $index => $exerciseId) {
                ExerciseSubGroupItem::create([
                    'exercise_sub_group_id' => $subGroup->id,
                    'exercise_id' => $exerciseId,
                    'order' => $index,
                ]);
            }

            DB::commit();
            Toastr::success('Sub Group updated successfully', 'Success');
            return redirect()->route('exercise-groups.show', $subGroup->exercise_group_id);
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error('An error occurred while updating sub group: ' . $e->getMessage(), 'Error');
            return back()->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $subGroup = ExerciseSubGroup::findOrFail($id);
            $groupId = $subGroup->exercise_group_id;

            if ($subGroup->image && Storage::disk('public')->exists($subGroup->image)) {
                Storage::disk('public')->delete($subGroup->image);
            }
            $subGroup->delete();

            Toastr::success('Sub Group deleted successfully', 'Success');
            return redirect()->route('exercise-groups.show', $groupId);
        } catch (\Exception $e) {
            Toastr::error('An error occurred while deleting sub group', 'Error');
            return back();
        }
    }
}
