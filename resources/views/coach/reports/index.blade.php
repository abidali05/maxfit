@extends('layouts.coach.app')
@section('title', 'Group Exercise Reports')
@section('content')
    <div class="container-fluid pt-4 px-4" style="min-height: 82.5vh">
        <!-- Header & Action Buttons -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="mb-1 text-dark fw-bold"><i class="fa fa-chart-line text-primary me-2"></i>Group Exercise Reports</h4>
                <p class="text-muted small mb-0">Analyze and export daily exercise counts and performance metrics across your athlete groups.</p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" onclick="window.print()" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-print me-1"></i>Print Report
                </button>
                <a href="{{ route('coach.reports.export-csv', request()->all()) }}" class="btn btn-success btn-sm">
                    <i class="fa fa-file-csv me-1"></i>Export CSV
                </a>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card border-0 shadow-sm rounded mb-4">
            <div class="card-body p-4 bg-light rounded">
                <form action="{{ route('coach.reports.index') }}" method="GET" id="reportFilterForm">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-6 col-lg-3">
                            <label for="group_id" class="form-label fw-bold small text-muted">Select Group</label>
                            <select name="group_id" id="group_id" class="form-select select2">
                                <option value="">All Groups</option>
                                @foreach($groups as $grp)
                                    <option value="{{ $grp->id }}" {{ ($selectedGroup && $selectedGroup->id == $grp->id) ? 'selected' : '' }}>
                                        {{ $grp->name }} ({{ $grp->groupUsers->count() }} athletes)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="user_ids" class="form-label fw-bold small text-muted">Filter Athletes</label>
                                <a href="javascript:void(0)" id="selectAllAthletes" class="small text-primary text-decoration-none">Select All</a>
                            </div>
                            <select name="user_ids[]" id="user_ids" class="form-select select2" multiple>
                                @if($selectedGroup)
                                    @foreach($selectedGroup->groupUsers as $gu)
                                        @if($gu->user)
                                            <option value="{{ $gu->user->id }}" {{ in_array($gu->user->id, $selectedUserIds) ? 'selected' : '' }}>
                                                {{ $gu->user->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="col-md-6 col-lg-2">
                            <label for="exercise_id" class="form-label fw-bold small text-muted">Exercise</label>
                            <select name="exercise_id" id="exercise_id" class="form-select select2">
                                <option value="">All Exercises</option>
                                @foreach($availableExercises as $ex)
                                    <option value="{{ $ex->id }}" {{ $selectedExerciseId == $ex->id ? 'selected' : '' }}>
                                        {{ $ex->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 col-lg-2">
                            <label for="start_date" class="form-label fw-bold small text-muted">From Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate }}">
                        </div>

                        <div class="col-md-3 col-lg-2">
                            <label for="end_date" class="form-label fw-bold small text-muted">To Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate }}">
                        </div>

                        <div class="col-12 d-flex justify-content-end gap-2 mt-3 pt-2 border-top">
                            <a href="{{ route('coach.reports.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter me-1"></i>Apply Filters</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Metric Summary Cards -->
        <div class="row g-4 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 shadow-sm">
                    <i class="fa fa-users fa-3x text-primary"></i>
                    <div class="ms-3 text-end">
                        <p class="mb-1 text-muted small">Athletes Reporting</p>
                        <h4 class="mb-0 fw-bold">{{ $uniqueAthletesCount }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 shadow-sm">
                    <i class="fa fa-check-circle fa-3x text-success"></i>
                    <div class="ms-3 text-end">
                        <p class="mb-1 text-muted small">Total Submissions</p>
                        <h4 class="mb-0 fw-bold">{{ $totalSubmissions }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 shadow-sm">
                    <i class="fa fa-dumbbell fa-3x text-info"></i>
                    <div class="ms-3 text-end">
                        <p class="mb-1 text-muted small">Total Counts / Reps</p>
                        <h4 class="mb-0 fw-bold">{{ number_format($totalCountSum) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 shadow-sm">
                    <i class="fa fa-calendar-alt fa-3x text-warning"></i>
                    <div class="ms-3 text-end">
                        <p class="mb-1 text-muted small">Active Days Logged</p>
                        <h4 class="mb-0 fw-bold">{{ $activeDaysCount }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables Tabs -->
        <div class="bg-light rounded p-4 shadow-sm mb-4">
            <ul class="nav nav-pills mb-4 border-bottom pb-2" id="reportTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold" id="athlete-tab" data-bs-toggle="pill" data-bs-target="#athlete-pane" type="button" role="tab">
                        <i class="fa fa-user-ninja me-2"></i>Athlete Performance Summary ({{ $athleteStats->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold" id="log-tab" data-bs-toggle="pill" data-bs-target="#log-pane" type="button" role="tab">
                        <i class="fa fa-list-alt me-2"></i>Detailed Daily Log ({{ $submissions->count() }})
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="reportTabsContent">
                <!-- Tab 1: Athlete Summary -->
                <div class="tab-pane fade show active" id="athlete-pane" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead class="table-light">
                                <tr class="text-dark">
                                    <th>#</th>
                                    <th>Athlete Name</th>
                                    <th>Email</th>
                                    <th>Total Submissions</th>
                                    <th>Total Counts / Reps</th>
                                    <th>Active Days</th>
                                    <th>Last Activity</th>
                                    <th>Exercise Breakdown</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($athleteStats as $stat)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="fw-bold">{{ $stat['user']->name ?? 'N/A' }}</td>
                                        <td>{{ $stat['user']->email ?? 'N/A' }}</td>
                                        <td><span class="badge bg-primary">{{ $stat['submissions_count'] }}</span></td>
                                        <td><span class="fw-bold text-success">{{ number_format($stat['total_reps']) }}</span></td>
                                        <td>{{ $stat['active_days'] }} days</td>
                                        <td>{{ $stat['last_submission'] }}</td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($stat['exercises_breakdown'] as $exBreak)
                                                    <span class="badge bg-secondary" title="{{ $exBreak['submissions_count'] }} submissions">
                                                        {{ $exBreak['exercise']->name ?? 'Exercise' }}: {{ $exBreak['count_sum'] }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">No exercise submissions found matching the criteria.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab 2: Detailed Log -->
                <div class="tab-pane fade" id="log-pane" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead class="table-light">
                                <tr class="text-dark">
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Group</th>
                                    <th>Athlete</th>
                                    <th>Exercise</th>
                                    <th>Category</th>
                                    <th>Count / Reps</th>
                                    <th>Metric</th>
                                    <th>Recorded Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($submissions as $sub)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="fw-bold">{{ $sub->submitted_date }}</td>
                                        <td>{{ $sub->group->name ?? 'N/A' }}</td>
                                        <td>{{ $sub->user->name ?? 'N/A' }}</td>
                                        <td class="fw-bold text-primary">{{ $sub->exercise->name ?? 'N/A' }}</td>
                                        <td>{{ $sub->exercise->exercise_category->name ?? 'N/A' }}</td>
                                        <td><span class="badge bg-success fs-6">{{ $sub->count }}</span></td>
                                        <td>
                                            @php
                                                $unit = ($sub->exercise->exercise_type === 'sec' || $sub->exercise->exercise_type === 'seconds') ? 'per sec' : 'per count';
                                            @endphp
                                            <span class="text-muted small">{{ $unit }}</span>
                                        </td>
                                        <td>{{ $sub->created_at ? $sub->created_at->format('H:i:s') : 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">No submission logs found for the selected filters.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

            // Auto reload users when group changes
            $('#group_id').on('change', function () {
                $('#reportFilterForm').submit();
            });

            // Select all athletes shortcut
            $('#selectAllAthletes').on('click', function () {
                const userSelect = $('#user_ids');
                const allValues = [];
                userSelect.find('option').each(function () {
                    allValues.push($(this).val());
                });
                userSelect.val(allValues).trigger('change');
            });
        });
    </script>
@endsection
