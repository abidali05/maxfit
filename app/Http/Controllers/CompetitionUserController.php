<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompetitionUser;
use App\Models\CompetitionDetail;
use App\Models\CompetitionResult;
use App\Models\CompetitionUserTotal;
use App\Models\Set;
use App\Models\PhysicalAssessment;
use App\Jobs\ProcessCompetitionResults;

class CompetitionUserController extends Controller
{
    private function normalizeGender(?string $gender): ?string
    {
        $value = strtolower(trim((string) $gender));
        return match ($value) {
            'male' => 'Male',
            'female' => 'Female',
            'other' => 'Other',
            default => null,
        };
    }

    private function normalizeFitnessLevel(?string $value): ?string
    {
        $normalized = strtolower(trim((string) $value));
        return match ($normalized) {
            'expert' => 'Expert',
            'immature' => 'Immature',
            default => null,
        };
    }

    public function index($id)
    {
        $competition = CompetitionDetail::with(['competition', 'selectedUsers'])->findOrFail($id);

        foreach ($competition->selectedUsers as $selectedUser) {
            CompetitionUser::firstOrCreate(
                [
                    'competition_detail_id' => $competition->id,
                    'user_id' => $selectedUser->id,
                ],
                [
                    'competition_id' => $competition->competition?->id,
                    'status' => 'accepted',
                ]
            );
        }

        $competition->load([
            'competitionUsers.user',
            'competitionUsers.total',
        ]);

        return view('competition-users.index', compact('competition'));
    }

    public function edit($id)
    {
        $competitionUser = CompetitionUser::with(['user', 'competitionDetail.competition'])->findOrFail($id);

        $competition = $competitionUser->competitionDetail->competition;
        $genz = strtolower((string) $competition->getRawOriginal('genz'));

        $user = $competitionUser->user;

        $latestAssessment = PhysicalAssessment::query()
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->first();

        $fitnessLevel = $this->normalizeFitnessLevel($latestAssessment?->exercise_type);
        $gender = $this->normalizeGender($user->gender ?: $latestAssessment?->gender);

        $criteriaInfo = [
            'genz' => $genz,
            'fitness_level' => $fitnessLevel,
            'gender' => $gender,
        ];

        $sets = collect();
        if (!empty($fitnessLevel) && !empty($gender)) {
            $sets = Set::query()
                ->with(['setExercises.exercise'])
                ->matchingCriteria($genz, $fitnessLevel, $gender)
                ->orderBy('id')
                ->get();
        }

        // Get existing scores
        $results = CompetitionResult::with('videos')
            ->where('competition_user_id', $competitionUser->id)
            ->get()
            ->keyBy('exercise_id');

        return view('competition-users.edit', compact('competitionUser', 'competition', 'sets', 'results', 'criteriaInfo'));
    }

    public function update(Request $request, $id)
    {
        $competitionUser = CompetitionUser::findOrFail($id);

        $validated = $request->validate([
            'scores' => ['required', 'array'],
            'scores.*' => ['required', 'numeric', 'min:0'],
            'youtube_links' => ['nullable', 'array'],
            'youtube_links.*' => ['nullable', 'array'],
            'youtube_links.*.*' => ['nullable', 'url'],
        ]);

        $scores = $validated['scores']; // array: exercise_id => score
        $youtubeLinks = $validated['youtube_links'] ?? []; // array: exercise_id => [links...]

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
            if (array_key_exists($exerciseId, $youtubeLinks)) {
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
