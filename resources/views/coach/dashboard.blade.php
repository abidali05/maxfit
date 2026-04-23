@extends('layouts.coach.app')
@section('title', 'Dashboard')
@section('content')
    <style>
        .dashboard-shell {
            padding-top: 1.25rem;
        }

        .dashboard-hero {
            background: linear-gradient(135deg, #ffffff 0%, #f5f8ff 100%);
            border: 1px solid rgba(15, 23, 42, 0.06);
            border-radius: 1.25rem;
            box-shadow: 0 16px 40px rgba(15, 23, 42, 0.06);
        }

        .metric-card,
        .overview-card,
        .panel-card {
            border-radius: 1.1rem;
            border: 1px solid rgba(15, 23, 42, 0.06);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.05);
        }

        .metric-card {
            min-height: 104px;
            padding: 1.25rem;
        }

        .metric-icon,
        .overview-icon {
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(13, 110, 253, 0.08);
        }

        .metric-label,
        .overview-label {
            font-size: 0.86rem;
            color: #6b7280;
            margin-bottom: 0.2rem;
        }

        .metric-value,
        .overview-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
        }

        .overview-link {
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .overview-link:hover {
            transform: translateY(-2px);
        }

        .section-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
        }

        .section-subtitle {
            font-size: 0.84rem;
            color: #6b7280;
        }
    </style>

    <div class="container-fluid dashboard-shell px-4">
        <div class="dashboard-hero p-4 p-md-4 mb-4">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div>
                    <h4 class="mb-1">Dashboard Overview</h4>
                    <div class="text-muted">A quick view of your assigned competitions and participant activity.</div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('coach.competition-details') }}" class="btn btn-primary">Go to Competitions</a>
                    <a href="{{ route('coach.profile') }}" class="btn btn-outline-primary">My Profile</a>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-1">
            @foreach ($summaryCards as $card)
                <div class="col-12 col-sm-6 col-xl-4">
                    <a href="{{ $card['route'] ?? '#' }}" class="overview-link text-decoration-none d-block h-100">
                        <div class="bg-light metric-card d-flex align-items-center justify-content-between">
                            <div>
                                <div class="metric-label">{{ $card['title'] }}</div>
                                <div class="metric-value">
                                    {{ is_numeric($card['count']) ? number_format($card['count']) : $card['count'] }}
                                </div>
                            </div>
                            <div class="metric-icon text-{{ $card['color'] }}">
                                <i class="fa {{ $card['icon'] }} fa-lg"></i>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="row g-4 mt-1">
            @foreach ($overviewCards as $card)
                <div class="col-12 col-sm-6 col-xl-4">
                    <a href="{{ $card['route'] }}" class="overview-link text-decoration-none d-block h-100">
                        <div class="bg-light overview-card d-flex align-items-center justify-content-between p-4 h-100">
                            <div class="d-flex align-items-center gap-3">
                                <div class="overview-icon text-{{ $card['color'] }}">
                                    <i class="fa {{ $card['icon'] }} fa-lg"></i>
                                </div>
                                <div>
                                    <div class="overview-label">{{ $card['title'] }}</div>
                                    <div class="overview-value">{{ number_format($card['count']) }}</div>
                                </div>
                            </div>
                            <i class="fa fa-chevron-right text-muted"></i>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="row g-4 mt-1">
            <div class="col-12 col-xl-8">
                <div class="bg-light panel-card p-4 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <p class="section-title">Monthly Participants</p>
                            <div class="section-subtitle">Current year participant additions</div>
                        </div>
                    </div>
                    <div style="min-height: 320px;">
                        <canvas id="coach-monthly-participants-chart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="bg-light panel-card p-4 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <p class="section-title">Recent Participants</p>
                            <div class="section-subtitle">Latest competition user activity</div>
                        </div>
                        <a href="{{ route('coach.competition-details') }}">Show All</a>
                    </div>
                    <div class="d-flex flex-column gap-3">
                        @forelse ($recentParticipants as $participant)
                            <div class="d-flex align-items-center justify-content-between border-bottom pb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $participant->user->image ?? asset('assets/images/user.jpg') }}"
                                        alt="{{ $participant->user->name ?? 'User' }}" class="rounded-circle flex-shrink-0"
                                        style="width: 44px; height: 44px; object-fit: cover;">
                                    <div>
                                        <div class="fw-semibold">{{ $participant->user->name ?? 'User' }}</div>
                                        <small class="text-muted d-block">
                                            {{ $participant->competitionDetail->competition->name ?? 'Competition' }}
                                        </small>
                                    </div>
                                </div>
                                <span class="badge bg-light text-muted border">New</span>
                            </div>
                        @empty
                            <div class="text-muted">No participants available.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const labels = @json($chartLabels);
            const data = @json($chartData);

            const canvas = document.getElementById('coach-monthly-participants-chart');
            if (!canvas) {
                return;
            }

            if (window.coachMonthlyChart && typeof window.coachMonthlyChart.destroy === 'function') {
                window.coachMonthlyChart.destroy();
            }

            const rootStyles = getComputedStyle(document.documentElement);
            const themePrimary = (rootStyles.getPropertyValue('--bs-primary') || '#009CFF').trim();
            const themeText = '#64748b';
            const themeGrid = 'rgba(15, 23, 42, 0.08)';

            window.coachMonthlyChart = new Chart(canvas, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Participants",
                        data: data,
                        borderWidth: 3,
                        borderColor: themePrimary,
                        backgroundColor: 'rgba(0, 156, 255, 0.16)',
                        pointBackgroundColor: themePrimary,
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: themePrimary,
                        fill: true,
                        tension: 0.35,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: themeText,
                                usePointStyle: true,
                                boxWidth: 8,
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: themeGrid,
                                drawBorder: false
                            },
                            ticks: {
                                color: themeText
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: themeGrid,
                                drawBorder: false
                            },
                            ticks: {
                                color: themeText,
                                precision: 0
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection

