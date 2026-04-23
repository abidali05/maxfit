<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\CompetitionDetail;
use App\Models\CompetitionResult;
use App\Models\CompetitionUser;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $coachId = Auth::guard('coach')->id();
        $currentYear = now()->year;

        $competitionDetailsQuery = CompetitionDetail::query()->where('coach_id', $coachId);

        $totalCompetitions = (clone $competitionDetailsQuery)->count();
        $totalParticipants = CompetitionUser::query()
            ->whereHas('competitionDetail', fn ($query) => $query->where('coach_id', $coachId))
            ->count();

        $participantsWithResults = CompetitionResult::query()
            ->whereHas('competitionUser.competitionDetail', fn ($query) => $query->where('coach_id', $coachId))
            ->distinct('competition_user_id')
            ->count('competition_user_id');

        $pendingResults = CompetitionUser::query()
            ->whereHas('competitionDetail', fn ($query) => $query->where('coach_id', $coachId))
            ->whereDoesntHave('results')
            ->count();

        $chartLabels = [];
        $chartData = [];
        foreach (range(1, 12) as $month) {
            $chartLabels[] = date('M', mktime(0, 0, 0, $month, 1));
            $chartData[] = CompetitionUser::query()
                ->whereHas('competitionDetail', fn ($query) => $query->where('coach_id', $coachId))
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $month)
                ->count();
        }

        $summaryCards = [
            [
                'title' => 'Assigned Competitions',
                'count' => $totalCompetitions,
                'icon' => 'fa-trophy',
                'color' => 'primary',
                'route' => route('coach.competition-details'),
            ],
            [
                'title' => 'Total Participants',
                'count' => $totalParticipants,
                'icon' => 'fa-users',
                'color' => 'success',
                'route' => route('coach.competition-details'),
            ],
            [
                'title' => 'Pending Results',
                'count' => $pendingResults,
                'icon' => 'fa-hourglass-half',
                'color' => 'warning',
                'route' => route('coach.competition-details'),
            ],
        ];

        $overviewCards = [
            [
                'title' => 'Results Submitted',
                'count' => $participantsWithResults,
                'icon' => 'fa-check-circle',
                'color' => 'primary',
                'route' => route('coach.competition-details'),
            ],
            [
                'title' => 'Competitions',
                'count' => $totalCompetitions,
                'icon' => 'fa-trophy',
                'color' => 'success',
                'route' => route('coach.competition-details'),
            ],
            [
                'title' => 'Participants',
                'count' => $totalParticipants,
                'icon' => 'fa-users',
                'color' => 'dark',
                'route' => route('coach.competition-details'),
            ],
        ];

        $recentParticipants = CompetitionUser::query()
            ->with(['user', 'competitionDetail.competition'])
            ->whereHas('competitionDetail', fn ($query) => $query->where('coach_id', $coachId))
            ->latest()
            ->take(5)
            ->get();

        return view('coach.dashboard', compact(
            'chartLabels',
            'chartData',
            'summaryCards',
            'overviewCards',
            'recentParticipants'
        ));
    }
}

