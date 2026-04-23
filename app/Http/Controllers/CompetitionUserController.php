<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompetitionUser;
use App\Models\CompetitionDetail;
use App\Models\CompetitionResult;
use App\Models\CompetitionUserTotal;
use App\Jobs\ProcessCompetitionResults;

class CompetitionUserController extends Controller
{
    public function index($id)
    {
        $competition = CompetitionDetail::with('competitionUsers.user')->findOrFail($id);

        return view('competition-users.index', compact('competition'));
    }

    public function edit($id)
    {
        $competitionUser = CompetitionUser::with(['user', 'competitionDetail.competition'])->findOrFail($id);

        // Get all exercises for this competition
        $competition = $competitionUser->competitionDetail->competition;
        $exercises = $competition->exercises; // via competition_exercises

        // Get existing scores
        $results = CompetitionResult::where('competition_user_id', $competitionUser->id)->get()->keyBy('exercise_id');

        return view('competition-users.edit', compact('competitionUser', 'exercises', 'results'));
    }

    public function update(Request $request, $id)
    {
        $competitionUser = CompetitionUser::findOrFail($id);

        $scores = $request->input('scores', []); // array: exercise_id => score
        $youtubeLinks = $request->input('youtube_links', []); // array of links

        $totalScore = 0;

        foreach ($scores as $exerciseId => $score) {
            $result = CompetitionResult::updateOrCreate(
                [
                    'competition_user_id' => $competitionUser->id,
                    'exercise_id' => $exerciseId
                ],
                ['score' => $score]
            );

            // Save YouTube links for this exercise result
            if (isset($youtubeLinks[$exerciseId])) {
                $result->videos()->delete(); // clear old
                foreach ($youtubeLinks[$exerciseId] as $link) {
                    if ($link) {
                        $result->videos()->create([
                            'youtube_link' => $link
                        ]);
                    }
                }
            }

            $totalScore += floatval($score);
        }

        // Save or update total score
        CompetitionUserTotal::updateOrCreate(
            ['competition_user_id' => $competitionUser->id],
            ['total_score' => $totalScore]
        );

        return redirect()->back()->with('success', 'Scores and videos updated!');
    }


    public function editRank($id)
    {
        $competitionUser = CompetitionUser::with('total')->findOrFail($id);
        return view('competition_user_totals.edit', compact('competitionUser'));
    }

    public function updateRank(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'rank' => 'nullable|integer|min:1',
        ]);

        $competitionUser = CompetitionUser::findOrFail($id);

        // Ensure the total exists
        if (!$competitionUser->total) {
            $competitionUser->total()->create([
                'status' => $request->status,
                'rank' => $request->rank,
            ]);
        } else {
            $competitionUser->total->update([
                'status' => $request->status,
                'rank' => $request->rank,
            ]);
        }

        return redirect()->route('competitions.show', $competitionUser->competition_id)
            ->with('success', 'User status and rank updated successfully.');
    }

    public function generateResults($competitionId)
    {
        if (config('queue.default') === 'sync') {
            ProcessCompetitionResults::dispatchSync($competitionId);
        } else {
            ProcessCompetitionResults::dispatch($competitionId);
        }

        return redirect()->back()->with('success', 'Result generation job dispatched!');
    }
}
