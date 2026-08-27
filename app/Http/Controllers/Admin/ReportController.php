<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupExercise;
use App\Models\GroupExerciseSubmission;
use App\Models\User;
use App\Models\Coach;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $coaches = Coach::orderBy('name')->get();
        $selectedCoachId = $request->input('coach_id');

        $groupsQuery = Group::with(['coach', 'groupUsers.user', 'groupExercises.exercise'])->orderBy('name');
        if ($selectedCoachId) {
            $groupsQuery->where('coach_id', $selectedCoachId);
        }
        $groups = $groupsQuery->get();

        $selectedGroupId = $request->input('group_id');
        $selectedGroup = $selectedGroupId ? $groups->firstWhere('id', (int)$selectedGroupId) : null;

        $selectedUserIds = collect((array) $request->input('user_ids', []))
            ->filter()
            ->map(fn($v) => (int)$v)
            ->values()
            ->all();

        $selectedExerciseId = $request->input('exercise_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $submissionsQuery = GroupExerciseSubmission::query()
            ->with(['user', 'exercise.exercise_category', 'group.coach']);

        if ($selectedCoachId) {
            $submissionsQuery->whereHas('group', function ($q) use ($selectedCoachId) {
                $q->where('coach_id', $selectedCoachId);
            });
        }

        if ($selectedGroup) {
            $submissionsQuery->where('group_id', $selectedGroup->id);
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
            ->paginate(30)
            ->withQueryString();

        // Metrics for all matched records
        $metricsQuery = clone $submissionsQuery;
        $allMatched = $metricsQuery->get();
        $totalSubmissions = $allMatched->count();
        $totalCountSum = $allMatched->sum('count');
        $uniqueAthletesCount = $allMatched->pluck('user_id')->unique()->count();
        $uniqueGroupsCount = $allMatched->pluck('group_id')->unique()->count();

        // Group-level aggregate per athlete
        $athleteStats = $allMatched->groupBy('user_id')->map(function ($athleteSubs) {
            $firstSub = $athleteSubs->first();
            return [
                'user' => $firstSub->user,
                'group' => $firstSub->group,
                'submissions_count' => $athleteSubs->count(),
                'total_reps' => $athleteSubs->sum('count'),
                'active_days' => $athleteSubs->pluck('submitted_date')->unique()->count(),
                'last_submission' => $athleteSubs->max('submitted_date'),
            ];
        })->values();

        $availableExercises = Exercise::orderBy('name')->get();
        $availableAthletes = User::where('role', 'user')->orderBy('name')->get();

        return view('admin.reports.index', compact(
            'coaches',
            'selectedCoachId',
            'groups',
            'selectedGroup',
            'selectedGroupId',
            'selectedUserIds',
            'selectedExerciseId',
            'startDate',
            'endDate',
            'submissions',
            'athleteStats',
            'totalSubmissions',
            'totalCountSum',
            'uniqueAthletesCount',
            'uniqueGroupsCount',
            'availableExercises',
            'availableAthletes'
        ));
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $selectedCoachId = $request->input('coach_id');
        $selectedGroupId = $request->input('group_id');
        $selectedUserIds = collect((array) $request->input('user_ids', []))->filter()->map(fn($v) => (int)$v)->values()->all();
        $selectedExerciseId = $request->input('exercise_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $submissionsQuery = GroupExerciseSubmission::query()
            ->with(['user', 'exercise.exercise_category', 'group.coach']);

        if ($selectedCoachId) {
            $submissionsQuery->whereHas('group', function ($q) use ($selectedCoachId) {
                $q->where('coach_id', $selectedCoachId);
            });
        }

        if ($selectedGroupId) {
            $submissionsQuery->where('group_id', $selectedGroupId);
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

        $filename = 'admin_group_exercise_report_' . date('Y_m_d_His') . '.csv';

        return response()->streamDownload(function () use ($submissions) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Submission Date',
                'Coach Name',
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
                    $sub->group->coach->name ?? 'N/A',
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

    public function downloadReceipt(Request $request)
    {
        $selectedCoachId = $request->input('coach_id');
        $coach = $selectedCoachId ? Coach::find($selectedCoachId) : null;

        $selectedGroupId = $request->input('group_id');
        $selectedGroup = $selectedGroupId ? Group::with('coach')->find($selectedGroupId) : null;

        $selectedUserIds = collect((array) $request->input('user_ids', []))->filter()->map(fn($v) => (int)$v)->values()->all();
        $selectedExerciseId = $request->input('exercise_id');
        $selectedExercise = $selectedExerciseId ? Exercise::find($selectedExerciseId) : null;
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $submissionsQuery = GroupExerciseSubmission::query()
            ->with(['user', 'exercise.exercise_category', 'group.coach']);

        if ($selectedCoachId) {
            $submissionsQuery->whereHas('group', function ($q) use ($selectedCoachId) {
                $q->where('coach_id', $selectedCoachId);
            });
        }

        if ($selectedGroup) {
            $submissionsQuery->where('group_id', $selectedGroup->id);
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

        $totalSubmissions = $submissions->count();
        $totalCountSum = $submissions->sum('count');
        $uniqueAthletesCount = $submissions->pluck('user_id')->unique()->count();
        $activeDaysCount = $submissions->pluck('submitted_date')->unique()->count();

        $athleteStats = $submissions->groupBy('user_id')->map(function ($athleteSubs) {
            $firstSub = $athleteSubs->first();
            return [
                'user' => $firstSub->user,
                'submissions_count' => $athleteSubs->count(),
                'total_reps' => $athleteSubs->sum('count'),
                'active_days' => $athleteSubs->pluck('submitted_date')->unique()->count(),
                'last_submission' => $athleteSubs->max('submitted_date'),
            ];
        })->values();

        $pdfHtml = view('coach.reports.receipt_pdf', [
            'coach' => $coach ?? ($selectedGroup->coach ?? null),
            'selectedGroup' => $selectedGroup,
            'selectedExercise' => $selectedExercise,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'submissions' => $submissions,
            'athleteStats' => $athleteStats,
            'totalSubmissions' => $totalSubmissions,
            'totalCountSum' => $totalCountSum,
            'uniqueAthletesCount' => $uniqueAthletesCount,
            'activeDaysCount' => $activeDaysCount
        ])->render();

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($pdfHtml);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $groupSlug = $selectedGroup ? \Illuminate\Support\Str::slug($selectedGroup->name) : 'admin-all-groups';
        $filename = 'MaxFit-Receipt-' . $groupSlug . '-' . now()->format('Ymd-His') . '.pdf';

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
