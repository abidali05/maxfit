<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Coach;
use App\Models\Competition;
use App\Models\Exercise;
use App\Models\ExerciseCategory;
use App\Models\Plan;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $currentYear = now()->year;
        $usersQuery = User::query()->where('role', 'user');
        $totalUsers = (clone $usersQuery)->count();
        $statusColumnExists = Schema::hasColumn('users', 'status');

        if ($statusColumnExists) {
            $activeUsers = (clone $usersQuery)->where('status', 'active')->count();
            $inactiveUsers = (clone $usersQuery)->where('status', 'inactive')->count();
        } else {
            $activeUsers = (clone $usersQuery)->whereNotNull('email_verified_at')->count();
            $inactiveUsers = (clone $usersQuery)->whereNull('email_verified_at')->count();
        }

        $totalRevenue = (float) (Plan::where('status', 'active')->sum('price') ?? 0);

        $chartLabels = [];
        $chartData = [];
        foreach (range(1, 12) as $month) {
            $chartLabels[] = date('M', mktime(0, 0, 0, $month, 1));
            $chartData[] = (clone $usersQuery)->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $month)
                ->count();
        }

        $summaryCards = [
            [
                'title' => 'Total Users',
                'count' => $totalUsers,
                'icon' => 'fa-user',
                'color' => 'primary',
                'route' => route('users.index'),
            ],
            [
                'title' => 'Active Users',
                'count' => $activeUsers,
                'icon' => 'fa-user-check',
                'color' => 'success',
                'route' => route('users.index', ['status' => 'active']),
            ],
            [
                'title' => 'Inactive Users',
                'count' => $inactiveUsers,
                'icon' => 'fa-user-times',
                'color' => 'warning',
                'route' => route('users.index', ['status' => 'inactive']),
            ],
            // [
            //     'title' => 'Total Revenue',
            //     'count' => $totalRevenue,
            //     'icon' => 'fa-chart-pie',
            //     'color' => 'info',
            //     'prefix' => '$',
            // ],
        ];

        $overviewCards = [
            [
                'title' => 'Branches',
                'count' => Branch::count(),
                'icon' => 'fa-building',
                'color' => 'primary',
                'route' => route('branches.index'),
            ],
            [
                'title' => 'Coaches',
                'count' => Coach::count(),
                'icon' => 'fa-chalkboard-teacher',
                'color' => 'success',
                'route' => route('coaches.index'),
            ],
            [
                'title' => 'Venues',
                'count' => Venue::count(),
                'icon' => 'fa-map-marker-alt',
                'color' => 'warning',
                'route' => route('venues.index'),
            ],
            [
                'title' => 'Exercises',
                'count' => Exercise::count(),
                'icon' => 'fa-dumbbell',
                'color' => 'info',
                'route' => route('exercises.index'),
            ],
            [
                'title' => 'Competitions',
                'count' => Competition::count(),
                'icon' => 'fa-trophy',
                'color' => 'danger',
                'route' => route('competitions.index'),
            ],
            [
                'title' => 'Categories',
                'count' => ExerciseCategory::count(),
                'icon' => 'fa-layer-group',
                'color' => 'dark',
                'route' => route('exercise-categories.index'),
            ],
        ];

        $recentUsers = (clone $usersQuery)->latest()->take(5)->get();

        return view('dashboard', compact(
            'chartLabels',
            'chartData',
            'summaryCards',
            'overviewCards',
            'recentUsers'
        ));
    }
}
