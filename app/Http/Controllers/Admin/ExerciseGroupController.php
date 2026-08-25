<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExerciseGroup;
use App\Models\ExerciseSubGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Brian2694\Toastr\Facades\Toastr;

class ExerciseGroupController extends Controller
{
    public function index()
    {
        $exerciseGroups = ExerciseGroup::withCount('subGroups')
            ->with(['subGroups.exercises'])
            ->orderBy('id', 'desc')
            ->get();

        return view('exercise_groups.index', compact('exerciseGroups'));
    }

    public function show($id)
    {
        $group = ExerciseGroup::with(['subGroups.exercises' => function ($q) {
            $q->orderByPivot('order', 'asc');
        }])->findOrFail($id);

        return view('exercise_groups.show', compact('group'));
    }

    public function create()
    {
        return view('exercise_groups.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'icon' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        DB::beginTransaction();
        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('exercise_group_images', 'public');
            }

            $exerciseGroup = ExerciseGroup::create([
                'name' => $request->name,
                'sub_title' => $request->sub_title,
                'image' => $imagePath,
                'icon' => $request->icon,
                'status' => $request->status ?? 'active',
            ]);

            DB::commit();
            Toastr::success('Exercise Group created successfully. Now you can add sub-groups.', 'Success');
            return redirect()->route('exercise-groups.show', $exerciseGroup->id);
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error('An error occurred while creating group: ' . $e->getMessage(), 'Error');
            return back()->withInput();
        }
    }

    public function edit($id)
    {
        $exerciseGroup = ExerciseGroup::findOrFail($id);
        return view('exercise_groups.edit', compact('exerciseGroup'));
    }

    public function update(Request $request, $id)
    {
        $exerciseGroup = ExerciseGroup::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'icon' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'name' => $request->name,
                'sub_title' => $request->sub_title,
                'icon' => $request->icon,
                'status' => $request->status ?? 'active',
            ];

            if ($request->hasFile('image')) {
                if ($exerciseGroup->image && Storage::disk('public')->exists($exerciseGroup->image)) {
                    Storage::disk('public')->delete($exerciseGroup->image);
                }
                $data['image'] = $request->file('image')->store('exercise_group_images', 'public');
            }

            $exerciseGroup->update($data);

            DB::commit();
            Toastr::success('Exercise Group updated successfully', 'Success');
            return redirect()->route('exercise-groups.show', $exerciseGroup->id);
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error('An error occurred while updating group: ' . $e->getMessage(), 'Error');
            return back()->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $exerciseGroup = ExerciseGroup::findOrFail($id);
            if ($exerciseGroup->image && Storage::disk('public')->exists($exerciseGroup->image)) {
                Storage::disk('public')->delete($exerciseGroup->image);
            }
            $exerciseGroup->delete();

            Toastr::success('Exercise Group deleted successfully', 'Success');
            return redirect()->route('exercise-groups.index');
        } catch (\Exception $e) {
            Toastr::error('An error occurred while deleting group', 'Error');
            return back();
        }
    }
}
