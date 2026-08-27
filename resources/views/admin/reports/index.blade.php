@extends('layouts.app')
@section('title', 'Group Exercise Reports')
@section('content')
    <div class="container-fluid pt-4 px-4" style="min-height: 82.5vh">
        <!-- Header & Action Buttons -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="mb-1 text-dark fw-bold"><i class="fa fa-chart-line text-primary me-2"></i>Group Exercise Reports</h4>
                <p class="text-muted small mb-0">System-wide athlete exercise submissions, performance counts, and values across coaches and groups.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.reports.download-receipt', request()->all()) }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-file-pdf me-1"></i>Download Receipt (PDF)
                </a>
                <a href="{{ route('admin.reports.export-csv', request()->all()) }}" class="btn btn-outline-success btn-sm">
                    <i class="fa fa-file-csv me-1"></i>Export CSV
                </a>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card border-0 shadow-sm rounded mb-4">
            <div class="card-body p-4 bg-light rounded">
                <form action="{{ route('admin.reports.index') }}" method="GET" id="adminReportFilterForm">
                    <div class="row g-3 align-items-end">
                        <!-- Coach Filter -->
                        <div class="col-md-6 col-lg-3">
                            <label for="coach_id" class="form-label fw-bold small text-muted">Coach</label>
                            <select name="coach_id" id="coach_id" class="form-select select2">
                                <option value="">All Coaches</option>
                                @foreach($coaches as $c)
                                    <option value="{{ $c->id }}" {{ $selectedCoachId == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Group Filter -->
                        <div class="col-md-6 col-lg-3">
                            <label for="group_id" class="form-label fw-bold small text-muted">Group</label>
                            <select name="group_id" id="group_id" class="form-select select2">
                                <option value="">All Groups</option>
                                @foreach($groups as $grp)
                                    <option value="{{ $grp->id }}" {{ ($selectedGroupId == $grp->id) ? 'selected' : '' }}>
                                        {{ $grp->name }} (Coach: {{ $grp->coach->name ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Exercise Filter -->
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

                        <!-- From Date -->
                        <div class="col-md-3 col-lg-2">
                            <label for="start_date" class="form-label fw-bold small text-muted">From Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate }}">
                        </div>

                        <!-- To Date -->
                        <div class="col-md-3 col-lg-2">
                            <label for="end_date" class="form-label fw-bold small text-muted">To Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate }}">
                        </div>

                        <div class="col-12 d-flex justify-content-end gap-2 mt-3 pt-2 border-top">
                            <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
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
                    <i class="fa fa-layer-group fa-3x text-info"></i>
                    <div class="ms-3 text-end">
                        <p class="mb-1 text-muted small">Active Groups</p>
                        <h4 class="mb-0 fw-bold">{{ $uniqueGroupsCount }}</h4>
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
                    <i class="fa fa-dumbbell fa-3x text-warning"></i>
                    <div class="ms-3 text-end">
                        <p class="mb-1 text-muted small">Total Reps / Units</p>
                        <h4 class="mb-0 fw-bold">{{ number_format($totalCountSum) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs: Logged Submissions & Athlete Aggregates -->
        <div class="bg-light rounded p-4 shadow-sm">
            <ul class="nav nav-tabs mb-4" id="reportTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold" id="submissions-tab" data-bs-toggle="tab" data-bs-target="#submissions-pane" type="button" role="tab">
                        <i class="fa fa-list me-2"></i>Detailed Submissions Log ({{ $submissions->total() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold" id="athletes-tab" data-bs-toggle="tab" data-bs-target="#athletes-pane" type="button" role="tab">
                        <i class="fa fa-user-check me-2"></i>Athlete Aggregates ({{ count($athleteStats) }})
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="reportTabContent">
                <!-- Tab 1: Detailed Submissions Log -->
                <div class="tab-pane fade show active" id="submissions-pane" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-dark bg-white">
                                    <th scope="col">#</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Athlete</th>
                                    <th scope="col">Coach / Group</th>
                                    <th scope="col">Exercise</th>
                                    <th scope="col">Category</th>
                                    <th scope="col" class="text-center">Count / Reps</th>
                                    <th scope="col">Recorded At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($submissions as $sub)
                                    <tr>
                                        <td>{{ $loop->iteration + ($submissions->currentPage() - 1) * $submissions->perPage() }}</td>
                                        <td class="fw-bold">{{ \Carbon\Carbon::parse($sub->submitted_date)->format('M d, Y') }}</td>
                                        <td>
                                            <span class="fw-bold text-dark">{{ $sub->user->name ?? 'N/A' }}</span>
                                            <div class="text-muted small">{{ $sub->user->email ?? '' }}</div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary text-white">{{ $sub->group->name ?? 'N/A' }}</span>
                                            <div class="text-muted small mt-1">Coach: {{ $sub->group->coach->name ?? 'N/A' }}</div>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $sub->exercise->name ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ $sub->exercise->exercise_category->name ?? 'N/A' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success fs-6">{{ $sub->count }}</span>
                                            <span class="text-muted small d-block">{{ ($sub->exercise->exercise_type === 'sec' || $sub->exercise->exercise_type === 'seconds') ? 'seconds' : 'reps' }}</span>
                                        </td>
                                        <td class="small text-muted">
                                            {{ $sub->created_at ? $sub->created_at->format('d M, Y h:i A') : 'N/A' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-5">
                                            <i class="fa fa-chart-bar fa-2x mb-2 text-muted"></i>
                                            <p class="mb-0">No exercise submissions found matching the selected filters.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($submissions->hasPages())
                        <div class="mt-4 d-flex justify-content-end">
                            {{ $submissions->links() }}
                        </div>
                    @endif
                </div>

                <!-- Tab 2: Athlete Aggregates -->
                <div class="tab-pane fade" id="athletes-pane" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-dark bg-white">
                                    <th scope="col">#</th>
                                    <th scope="col">Athlete Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Group</th>
                                    <th scope="col" class="text-center">Total Exercises Done</th>
                                    <th scope="col" class="text-center">Total Reps / Units</th>
                                    <th scope="col" class="text-center">Active Days</th>
                                    <th scope="col">Last Submission</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($athleteStats as $stat)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="fw-bold text-dark">{{ $stat['user']->name ?? 'N/A' }}</td>
                                        <td>{{ $stat['user']->email ?? 'N/A' }}</td>
                                        <td><span class="badge bg-primary">{{ $stat['group']->name ?? 'N/A' }}</span></td>
                                        <td class="text-center fw-bold text-primary">{{ $stat['submissions_count'] }}</td>
                                        <td class="text-center fw-bold text-success fs-6">{{ number_format($stat['total_reps']) }}</td>
                                        <td class="text-center"><span class="badge bg-info text-dark">{{ $stat['active_days'] }} days</span></td>
                                        <td>{{ $stat['last_submission'] ? \Carbon\Carbon::parse($stat['last_submission'])->format('M d, Y') : 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-5">
                                            <p class="mb-0">No athlete aggregate data available.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
