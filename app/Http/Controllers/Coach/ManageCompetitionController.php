<?php

namespace App\Http\Controllers\Coach;

use Illuminate\Http\Request;
use App\Models\CompetitionUser;
use App\Models\CompetitionDetail;
use App\Models\CompetitionResult;
use App\Http\Controllers\Controller;
use App\Models\CompetitionUserTotal;
use Illuminate\Support\Facades\Auth;
use App\Models\Set;
use App\Models\PhysicalAssessment;

class ManageCompetitionController extends Controller
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
            'amateur' => 'Amateur',
            default => null,
        };
    }

    public function getCompetitionDetail()
    {
        $competitionDetails = CompetitionDetail::with(['competition', 'coach'])
            ->where('coach_id', Auth::guard('coach')->user()->id)
            ->get();
        return view('coach.competition-list', compact('competitionDetails'));
    }

    public function getCompetitionDetailUser($id)
    {
        $competition = CompetitionDetail::with('competitionUsers.user')->findOrFail($id);

        return view('coach.competition-list-user', compact('competition'));
    }

    public function getCompetitionDetailUserUpdate($id)
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

        return view('coach.competition-users-edit', compact('competitionUser', 'competition', 'sets', 'results'));
    }

    public function getCompetitionResultUpdate(Request $request,$id)
    {
        $competitionUser = CompetitionUser::findOrFail($id);

        $validated = $request->validate([
            'scores' => ['required', 'array'],
            'scores.*' => ['nullable', 'numeric', 'min:0'],
            'youtube_links' => ['nullable', 'array'],
            'youtube_links.*' => ['nullable', 'array'],
            'youtube_links.*.*' => ['nullable', 'url'],
        ]);

        $scores = $validated['scores']; // array: exercise_id => score
        $youtubeLinks = $validated['youtube_links'] ?? []; // array: exercise_id => [links...]

        $totalScore = 0;

        foreach ($scores as $exerciseId => $score) {
            if ($score === null || $score === '') {
                CompetitionResult::where([
                    'competition_user_id' => $competitionUser->id,
                    'exercise_id' => $exerciseId
                ])->delete();
                continue;
            }

            $result = CompetitionResult::updateOrCreate(
                [
                    'competition_user_id' => $competitionUser->id,
                    'exercise_id' => $exerciseId
                ],
                ['score' => $score]
            );

            // Save YouTube links for this exercise result (coach side uses a single input, but supports arrays)
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
}
