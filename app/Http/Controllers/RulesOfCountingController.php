<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use Illuminate\Http\Request;
use App\Models\RulesOfCounting;

class RulesOfCountingController extends Controller
{
    public function index()
    {
        $rules = RulesOfCounting::all();
        return view('rules_of_counting.index', compact('rules'));
    }

    public function create()
    {
        $competitions = Competition::all();
        return view('rules_of_counting.create', compact('competitions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'competition_id' => 'required|exists:competitions,id',
            'custom_exercise_name' => 'required|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_file' => 'nullable|mimes:mp4,avi,mov,wmv|max:20480',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image_file')) {
            $validated['image_file'] = $request->file('image_file')->store('rules_images', 'public');
        }
        if ($request->hasFile('video_file')) {
            $validated['video_file'] = $request->file('video_file')->store('rules_videos', 'public');
        }

        RulesOfCounting::create($validated);
        return redirect()->route('rulesof-counting.index')->with('success', 'Rule created successfully.');
    }

    public function edit($id)
    {
        $rule = RulesOfCounting::findOrFail($id);
        $competitions = Competition::all();
        return view('rules_of_counting.edit', compact('rule','competitions'));
    }

    public function update(Request $request, $id)
    {
        $rule = RulesOfCounting::findOrFail($id);
        $validated = $request->validate([
            'competition_id' => 'required|exists:competitions,id',
            'custom_exercise_name' => 'required|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_file' => 'nullable|mimes:mp4,avi,mov,wmv|max:20480',
            'description' => 'nullable|string',
        ]);
        if ($request->hasFile('image_file')) {
            $validated['image_file'] = $request->file('image_file')->store('rules_images', 'public');
        } else {
            $validated['image_file'] = $rule->image_file;
        }
        if ($request->hasFile('video_file')) {
            $validated['video_file'] = $request->file('video_file')->store('rules_videos', 'public');
        } else {
            $validated['video_file'] = $rule->video_file;
        }
        $rule->update($validated);
        return redirect()->route('rulesof-counting.index')->with('success', 'Rule updated successfully.');
    }

    public function destroy($id)
    {
        $rule = RulesOfCounting::findOrFail($id);
        $rule->delete();
        return redirect()->route('rulesof-counting.index')->with('success', 'Rule deleted successfully.');
    }
}
