<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupExercise;
use App\Models\GroupExerciseSubmission;
use App\Models\User;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $coachId = Auth::guard('coach')->id();
        $groups = Group::where('coach_id', $coachId)
            ->with(['groupUsers.user', 'groupExercises.exercise'])
            ->orderBy('name')
            ->get();

        $selectedGroupId = $request->input('group_id');
        $selectedGroup = $selectedGroupId 
            ? $groups->firstWhere('id', (int)$selectedGroupId) 
            : $groups->first();

        $selectedUserIds = collect((array) $request->input('user_ids', []))
            ->filter()
            ->map(fn($v) => (int)$v)
            ->values()
            ->all();

        $selectedExerciseId = $request->input('exercise_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $submissionsQuery = GroupExerciseSubmission::query()
            ->with(['user', 'exercise.exercise_category', 'group']);

        if ($selectedGroup) {
            $submissionsQuery->where('group_id', $selectedGroup->id);
        } else {
            $submissionsQuery->whereIn('group_id', $groups->pluck('id'));
        }

        if (!empty($selectedUserIds)) {
            $submissionsQuery->whereIn('user_id', $selectedUserIds);
        }

        if ($selectedExerciseId) {
            $submissionsQuery->where('exercise_id', $selectedExerciseId);
        }

        if ($startDate) {
            $submissionsQuery->whereDate('submitted_date', '>=', $startDate);
        }

        if ($endDate) {
            $submissionsQuery->whereDate('submitted_date', '<=', $endDate);
        }

        $submissions = $submissionsQuery
            ->orderBy('submitted_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        // Compute summary metrics
        $totalSubmissions = $submissions->count();
        $totalCountSum = $submissions->sum('count');
        $uniqueAthletesCount = $submissions->pluck('user_id')->unique()->count();
        $activeDaysCount = $submissions->pluck('submitted_date')->unique()->count();

        // Group-level aggregate per athlete
        $athleteStats = $submissions->groupBy('user_id')->map(function ($athleteSubs) {
            $firstSub = $athleteSubs->first();
            return [
                'user' => $firstSub->user,
                'submissions_count' => $athleteSubs->count(),
                'total_reps' => $athleteSubs->sum('count'),
                'active_days' => $athleteSubs->pluck('submitted_date')->unique()->count(),
                'last_submission' => $athleteSubs->max('submitted_date'),
                'exercises_breakdown' => $athleteSubs->groupBy('exercise_id')->map(function ($exSubs) {
                    return [
                        'exercise' => $exSubs->first()->exercise,
                        'count_sum' => $exSubs->sum('count'),
                        'submissions_count' => $exSubs->count(),
                    ];
                }),
            ];
        })->values();

        // Available exercises for the selected group
        $availableExercises = $selectedGroup 
            ? $selectedGroup->groupExercises->map(fn($ge) => $ge->exercise)->filter()->unique('id')->values()
            : Exercise::orderBy('name')->get();

        return view('coach.reports.index', compact(
            'groups',
            'selectedGroup',
            'selectedUserIds',
            'selectedExerciseId',
            'startDate',
            'endDate',
            'submissions',
            'athleteStats',
            'totalSubmissions',
            'totalCountSum',
            'uniqueAthletesCount',
            'activeDaysCount',
            'availableExercises'
        ));
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $coachId = Auth::guard('coach')->id();
        $groups = Group::where('coach_id', $coachId)->get();

        $selectedGroupId = $request->input('group_id');
        $selectedUserIds = collect((array) $request->input('user_ids', []))
            ->filter()
            ->map(fn($v) => (int)$v)
            ->values()
            ->all();

        $selectedExerciseId = $request->input('exercise_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $submissionsQuery = GroupExerciseSubmission::query()
            ->with(['user', 'exercise.exercise_category', 'group']);

        if ($selectedGroupId) {
            $submissionsQuery->where('group_id', $selectedGroupId)
                ->whereIn('group_id', $groups->pluck('id'));
        } else {
            $submissionsQuery->whereIn('group_id', $groups->pluck('id'));
        }

        if (!empty($selectedUserIds)) {
            $submissionsQuery->whereIn('user_id', $selectedUserIds);
        }

        if ($selectedExerciseId) {
            $submissionsQuery->where('exercise_id', $selectedExerciseId);
        }

        if ($startDate) {
            $submissionsQuery->whereDate('submitted_date', '>=', $startDate);
        }

        if ($endDate) {
            $submissionsQuery->whereDate('submitted_date', '<=', $endDate);
        }

        $submissions = $submissionsQuery
            ->orderBy('submitted_date', 'desc')
            ->get();

        $filename = 'group_exercise_report_' . date('Y_m_d_His') . '.csv';

        return response()->streamDownload(function () use ($submissions) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Submission Date',
                'Group Name',
                'Athlete Name',
                'Athlete Email',
                'Exercise Name',
                'Exercise Category',
                'Count / Reps',
                'Unit Type',
                'Recorded At'
            ]);

            foreach ($submissions as $sub) {
                $unit = ($sub->exercise->exercise_type === 'sec' || $sub->exercise->exercise_type === 'seconds') ? 'per sec' : 'per count';
                fputcsv($handle, [
                    $sub->submitted_date,
                    $sub->group->name ?? 'N/A',
                    $sub->user->name ?? 'N/A',
                    $sub->user->email ?? 'N/A',
                    $sub->exercise->name ?? 'N/A',
                    $sub->exercise->exercise_category->name ?? 'N/A',
                    $sub->count,
                    $unit,
                    $sub->created_at ? $sub->created_at->format('Y-m-d H:i:s') : 'N/A',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
