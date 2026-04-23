<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Competition;
use App\Models\ExerciseBenchmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserAllCompetitionResultsController extends Controller
{
    /**
     * ✅ Single competition results for a single user
     * Route example:
     * GET /api/users/{user}/competitions/{competition}/results
     */
    public function index(Request $request, User $user, Competition $competition)
    {
        // ✅ Age must be INT (not float)
        $age = (int) Carbon::parse($user->dob)->diffInYears(now());
        $gender = strtolower((string) $user->gender);

        $rows = DB::table('competition_users as cu')
            ->join('competition_details as cd', 'cd.id', '=', 'cu.competition_detail_id')
            ->join('competitions as c', 'c.id', '=', 'cd.competition_id')
            ->leftJoin('competition_results as cr', 'cr.competition_user_id', '=', 'cu.id')
            ->leftJoin('exercises as e', 'e.id', '=', 'cr.exercise_id')
            ->where('cu.user_id', $user->id)
            ->where('c.id', $competition->id) // ✅ Only this competition
            ->select([
                'c.id as competition_id',
                'c.name as competition_name',
                'c.genz',
                'c.time_allowed',
                'cd.id as competition_detail_id',
                'cd.start_date',
                'cd.end_date',
                'cd.start_time',
                'cd.end_time',
                'cu.id as competition_user_id',
                'cu.status as user_status',
                'cr.score as score',
                'e.exercise_key',
                'e.name as exercise_name',
            ])
            ->orderBy('cu.id')
            ->orderBy('e.name')
            ->get();

        // ✅ If user not in this competition / no results found
        if ($rows->isEmpty()) {
            return response()->json([
                'message' => 'No results found for this user in this competition.',
                'user_id' => (int) $user->id,
                'competition_id' => (int) $competition->id,
            ], 404);
        }

        $competitions = [];
        $grouped = $rows->groupBy('competition_user_id');

        foreach ($grouped as $competitionUserId => $items) {
            $first = $items->first();

            $benchmarked = [];
            $unbenchmarked = [];

            $totalPoints = 0;
            $benchmarkedCount = 0;

            foreach ($items as $it) {
                if (!$it->exercise_key) continue;

                $score = $it->score !== null ? (float) $it->score : null;

                $benchmark = null;
                if ($score !== null) {
                    $benchmark = ExerciseBenchmark::query()
                        ->where('exercise_key', $it->exercise_key)
                        ->where('gender', $gender)
                        ->where('age_min', '<=', $age)
                        ->where('age_max', '>=', $age)
                        ->first();
                }

                if (!$benchmark || $score === null) {
                    $unbenchmarked[] = [
                        'exercise_key' => $it->exercise_key,
                        'exercise_name' => $it->exercise_name,
                        'value' => $score,
                        'percentage' => null,
                        'band' => null,
                        'level' => null,
                        'points' => 0,
                        'note' => 'No benchmark found',
                    ];
                    continue;
                }

                // ✅ Cast benchmark values as float (avoid "60.00" strings)
                $p90 = (float) $benchmark->p90;
                $p80 = (float) $benchmark->p80;
                $p70 = (float) $benchmark->p70;

                $percentage = $this->calcPercentage($score, $p90, $p80, $p70);
                [$band, $level, $points] = $this->scoreBand($score, $p90, $p80, $p70);

                $totalPoints += $points;
                $benchmarkedCount++;

                $benchmarked[] = [
                    'exercise_key' => $it->exercise_key,
                    'exercise_name' => $it->exercise_name,
                    'value' => $score,
                    'unit' => $benchmark->unit,
                    'benchmark' => [
                        'age_group' => $benchmark->age_min . '-' . $benchmark->age_max,
                        'gender' => $benchmark->gender,
                        'p90' => $p90,
                        'p80' => $p80,
                        'p70' => $p70,
                    ],
                    'percentage' => $percentage,
                    'band' => $band,
                    'level' => $level,
                    'points' => $points,
                ];
            }

            $avgPoints = $benchmarkedCount ? round($totalPoints / $benchmarkedCount, 2) : 0.0;
            $overallPercentage = $avgPoints ? round(($avgPoints / 90) * 100, 2) : 0.0;

            $competitions[] = [
                'competition' => [
                    'competition_id' => (int) $first->competition_id,
                    'name' => $first->competition_name,
                    'genz' => $first->genz,
                    'time_allowed' => (int) $first->time_allowed,
                ],
                'competition_detail' => [
                    'competition_detail_id' => (int) $first->competition_detail_id,
                    'start_date' => $first->start_date,
                    'end_date' => $first->end_date,
                    'start_time' => $first->start_time,
                    'end_time' => $first->end_time,
                ],
                'participant' => [
                    'competition_user_id' => (int) $first->competition_user_id,
                    'status' => $first->user_status,
                ],
                'benchmarked_exercises' => $benchmarked,
                'unbenchmarked_exercises' => $unbenchmarked,
                'summary' => [
                    'benchmarked_count' => $benchmarkedCount,
                    'unbenchmarked_count' => count($unbenchmarked),
                    'total_points' => $totalPoints,
                    'avg_points' => $avgPoints,
                    'overall_percentage' => $overallPercentage,
                    'overall_level' => $this->overallLevel($avgPoints),
                ],
            ];
        }

        return response()->json([
            'user_id' => (int) $user->id,
            'competition_id' => (int) $competition->id,
            'age' => $age, // ✅ int
            'gender' => $gender,
            'competitions' => $competitions, // usually 1 attempt, but can be multiple
        ]);
    }

    private function calcPercentage(float $value, float $p90, float $p80, float $p70): float
    {
        if ($value >= $p90) return 100.0;
        if ($value >= $p80) return 80.0;
        if ($value >= $p70) return 70.0;

        if ($p70 <= 0) return 0.0;

        // below p70: scale 0..70
        return round(min(69.99, max(0.0, ($value / $p70) * 70.0)), 2);
    }

    private function scoreBand(float $value, float $p90, float $p80, float $p70): array
    {
        if ($value >= $p90) return [90, 'Elite', 90];
        if ($value >= $p80) return [80, 'Advanced', 80];
        if ($value >= $p70) return [70, 'Intermediate', 70];
        return [0, 'Beginner', 50];
    }

    private function overallLevel(float $avg): string
    {
        if ($avg >= 85) return 'Elite';
        if ($avg >= 75) return 'Advanced';
        if ($avg >= 65) return 'Intermediate';
        return 'Beginner';
    }
}
