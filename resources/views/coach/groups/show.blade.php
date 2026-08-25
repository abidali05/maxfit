@extends('layouts.coach.app')
@section('title', 'Group Details')
@section('content')
    <div class="container-fluid pt-4 px-4" style="min-height: 82.5vh">
        <!-- Top Bar: Header & Actions -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 border-bottom pb-3">
            <div>
                <a href="{{ route('coach.groups.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
                    <i class="fa fa-arrow-left me-1"></i>Back to Groups
                </a>
                <div class="d-flex align-items-center gap-2">
                    <h4 class="mb-0 fw-bold text-dark">{{ $group->name }}</h4>
                    @if($group->status === 'active')
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-danger">Suspended</span>
                    @endif
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('coach.reports.index', ['group_id' => $group->id]) }}" class="btn btn-outline-primary btn-sm">
                    <i class="fa fa-chart-line me-1"></i>View Report
                </a>
                <a href="{{ route('coach.groups.edit', $group->id) }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-edit me-1"></i>Edit Group
                </a>
            </div>
        </div>

        <!-- Row 1: Details & Instructions -->
        <div class="row g-4 mb-4">
            <!-- Left: Group Info & Criteria -->
            <div class="col-lg-4 col-md-5">
                <div class="bg-light rounded p-4 h-100 shadow-sm">
                    <h5 class="mb-3 border-bottom pb-2 fw-bold text-dark"><i class="fa fa-info-circle text-primary me-2"></i>Group Information</h5>
                    
                    <div class="mb-2">
                        <label class="text-muted d-block small">Coach</label>
                        <span class="fw-bold text-dark">{{ $group->coach->name ?? 'N/A' }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted d-block small">Created At</label>
                        <span>{{ $group->created_at->format('M d, Y h:i A') }}</span>
                    </div>

                    <h6 class="mt-4 mb-2 border-bottom pb-1 fw-bold text-dark"><i class="fa fa-filter text-primary me-2"></i>Criteria Applied</h6>
                    <div class="d-flex flex-wrap gap-1 mb-2">
                        @if($group->age_group)
                            <span class="badge bg-secondary">Age: {{ $group->age_group }}</span>
                        @endif
                        @if($group->gender)
                            <span class="badge bg-secondary">Gender: {{ $group->gender }}</span>
                        @endif
                        @if($group->genz)
                            <span class="badge bg-secondary">GenZ: {{ $group->genz }}</span>
                        @endif
                        @if($group->country)
                            <span class="badge bg-secondary">Country: {{ $group->countryRelation->name ?? $group->country }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right: Group Instructions -->
            <div class="col-lg-8 col-md-7">
                <div class="bg-light rounded p-4 h-100 shadow-sm">
                    <h5 class="mb-3 border-bottom pb-2 fw-bold text-dark"><i class="fa fa-book-open text-primary me-2"></i>Group Instructions</h5>
                    <div class="p-3 bg-white border rounded" style="min-height: 160px; max-height: 250px; overflow-y: auto;">
                        @if($group->instructions)
                            {!! $group->instructions !!}
                        @else
                            <p class="text-muted fst-italic mb-0">No instructions written for this group yet. You can add instructions by editing the group.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: Athletes & Exercises Assignment -->
        <div class="row g-4 mb-4">
            <!-- Left: Invited Athletes -->
            <div class="col-lg-4 col-md-5">
                <div class="bg-light rounded p-4 h-100 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                        <h5 class="mb-0 fw-bold text-dark"><i class="fa fa-users text-primary me-2"></i>Athletes ({{ $group->groupUsers->count() }})</h5>
                    </div>
                    <div class="table-responsive" style="max-height: 380px; overflow-y: auto;">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead class="table-light">
                                <tr class="text-dark small">
                                    <th>#</th>
                                    <th>Athlete</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($group->groupUsers as $gu)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="fw-bold small text-truncate" style="max-width: 140px;" title="{{ $gu->user->name ?? 'N/A' }}">{{ $gu->user->name ?? 'N/A' }}</div>
                                            <small class="text-muted text-truncate d-block" style="max-width: 140px;" title="{{ $gu->user->email ?? '' }}">{{ $gu->user->email ?? '' }}</small>
                                        </td>
                                        <td>
                                            @if($gu->status === 'accepted')
                                                <span class="badge bg-success">Accepted</span>
                                            @elseif($gu->status === 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3 small">No athletes assigned.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right: Exercises Assignment Panel -->
            <div class="col-lg-8 col-md-7">
                <div class="bg-light rounded p-4 h-100 shadow-sm">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 border-bottom pb-2">
                        <div>
                            <h5 class="mb-0 fw-bold text-dark"><i class="fa fa-dumbbell text-primary me-2"></i>Daily & Range Exercise Schedule</h5>
                            <small class="text-muted">Assign multiple exercises to specific single dates or multi-day date ranges.</small>
                        </div>
                        <button type="button" id="add-exercise-row" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus me-1"></i>Add Date / Schedule
                        </button>
                    </div>

                    <form action="{{ route('coach.groups.assign-exercises', $group->id) }}" method="POST">
                        @csrf
                        <div id="exercise-rows" class="mb-4">
                            <!-- Populated dynamically via JS -->
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <small class="text-muted">Athletes will only see exercises matching today's date in their mobile app.</small>
                            <button type="submit" class="btn btn-success btn-sm px-4">
                                <i class="fa fa-save me-1"></i>Save All Exercises
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            let rowIndex = 0;

            // Load initial/existing assignments, grouping them by date configuration
            const existingAssignments = @json($group->groupExercises);
            const grouped = {};
            
            existingAssignments.forEach(function(item) {
                const isSingle = (item.start_date === item.end_date);
                const key = item.start_date + '_' + item.end_date;
                if (!grouped[key]) {
                    grouped[key] = {
                        is_single: isSingle,
                        start_date: item.start_date,
                        end_date: item.end_date,
                        exercise_ids: []
                    };
                }
                grouped[key].exercise_ids.push(item.exercise_id);
            });

            const groupedList = Object.values(grouped);
            if (groupedList.length > 0) {
                groupedList.forEach(function(item) {
                    addExerciseRow(item.exercise_ids, item.start_date, item.end_date, item.is_single);
                });
            } else {
                // Add one empty single day row by default for today
                const todayStr = new Date().toISOString().split('T')[0];
                addExerciseRow([], todayStr, todayStr, true);
            }

            $('#add-exercise-row').click(function() {
                const todayStr = new Date().toISOString().split('T')[0];
                addExerciseRow([], todayStr, todayStr, true);
            });

            $(document).on('click', '.remove-exercise-row', function() {
                $(this).closest('.exercise-row-card').remove();
            });

            // Toggle date mode
            $(document).on('change', '.date-mode-select', function() {
                const card = $(this).closest('.exercise-row-card');
                const isRange = ($(this).val() === 'range');
                const endDateContainer = card.find('.end-date-container');
                const startDateLabel = card.find('.start-date-label');
                const startDateInput = card.find('.start-date-input');
                const endDateInput = card.find('.end-date-input');

                if (isRange) {
                    endDateContainer.removeClass('d-none');
                    startDateLabel.text('Start Date');
                    if (!endDateInput.val()) {
                        endDateInput.val(startDateInput.val());
                    }
                } else {
                    endDateContainer.addClass('d-none');
                    startDateLabel.text('Assignment Date');
                    endDateInput.val(startDateInput.val());
                }
            });

            // Keep endDate in sync if single day
            $(document).on('change', '.start-date-input', function() {
                const card = $(this).closest('.exercise-row-card');
                const isRange = (card.find('.date-mode-select').val() === 'range');
                if (!isRange) {
                    card.find('.end-date-input').val($(this).val());
                }
            });

            function addExerciseRow(exerciseIds = [], startDate = '', endDate = '', isSingle = true) {
                const index = rowIndex++;
                if (!startDate) {
                    startDate = new Date().toISOString().split('T')[0];
                }
                if (!endDate) {
                    endDate = startDate;
                }

                const rowHtml = `
                    <div class="exercise-row-card bg-white border rounded p-3 mb-3 shadow-sm">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label small fw-bold mb-1 text-muted">Schedule Type</label>
                                <select class="form-select form-select-sm date-mode-select">
                                    <option value="single" ${isSingle ? 'selected' : ''}>Single Day</option>
                                    <option value="range" ${!isSingle ? 'selected' : ''}>Date Range</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small fw-bold mb-1 text-muted start-date-label">${isSingle ? 'Assignment Date' : 'Start Date'}</label>
                                <input type="date" name="assignments[${index}][start_date]" class="form-control form-control-sm start-date-input" value="${startDate}" required>
                            </div>

                            <div class="col-md-3 end-date-container ${isSingle ? 'd-none' : ''}">
                                <label class="form-label small fw-bold mb-1 text-muted">End Date</label>
                                <input type="date" name="assignments[${index}][end_date]" class="form-control form-control-sm end-date-input" value="${endDate}">
                            </div>

                            <div class="${isSingle ? 'col-md-5' : 'col-md-2'} text-end">
                                <button type="button" class="btn btn-outline-danger btn-sm remove-exercise-row" title="Remove this day schedule">
                                    <i class="fa fa-trash me-1"></i>Remove
                                </button>
                            </div>

                            <div class="col-12 mt-2 pt-2 border-top">
                                <label class="form-label small fw-bold mb-1 text-dark">Select Multiple Exercises for this Date / Range</label>
                                <select name="assignments[${index}][exercise_ids][]" class="form-select select2-exercise" multiple required>
                                    @foreach($exercises as $exercise)
                                        <option value="{{ $exercise->id }}" ${exerciseIds.includes({{ $exercise->id }}) ? 'selected' : ''}>
                                            {{ $exercise->name }} ({{ $exercise->exercise_category->name ?? 'N/A' }}) - {{ $exercise->exercise_type ?? 'count' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                `;

                $('#exercise-rows').append(rowHtml);

                // Initialize select2 on the newly created dropdown
                $('#exercise-rows').find('.select2-exercise').last().select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: 'Search and choose exercises...'
                });
            }
        });
    </script>
@endsection
