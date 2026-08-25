<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use App\Models\Competition;
use Illuminate\Http\Request;
use App\Models\CompetitionUser;
use App\Models\CompetitionDetail;
use App\Models\CompetitionResult;

class competitionDetailController extends Controller
{
    public function edit($id)
    {
        $competitionDetail = CompetitionDetail::findOrFail($id);
        $coaches = Coach::all();
        return view('competition_details.edit', compact('competitionDetail', 'coaches'));
    }

    public function update(Request $request, $id)
    {
        $CompetitionDetail = CompetitionDetail::findOrFail($id);
        $validated = $request->validate([
            'coach_id' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required',
            'end_time' => 'required',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('competitionDetails', 'public');
        }

        $competitionId = $request->competition_id;

        $CompetitionDetail->update($validated);
        return redirect()->route('competitions.show', $competitionId)->with('success', 'Competition detail updated successfully.');
    }

    public function destroy($id)
    {
        $competition = CompetitionDetail::findOrFail($id);
        $competitionId = request()->competition_id;
        $competition->delete();
        return redirect()->route('competitions.show', $competitionId)->with('success', 'Competition detail deleted successfully.');
    }

    public function getCompetitionDetailUserUpdate($id)
    {
        $competitionUser = CompetitionUser::with(['user', 'competitionDetail.competition'])->findOrFail($id);

        // Get all exercises for this competition
        $competition = $competitionUser->competitionDetail->competition;
        $exercises = $competition->exercises; // via competition_exercises

        // Get existing scores
        $results = CompetitionResult::where('competition_user_id', $competitionUser->id)->get()->keyBy('exercise_id');

        return view('branch.competition-users-edit', compact('competitionUser', 'exercises', 'results'));
    }
}
