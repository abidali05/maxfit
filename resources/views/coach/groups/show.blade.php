@extends('layouts.coach.app')
@section('title', 'Group Details - ' . $group->name)
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
                            <p class="text-muted fst-italic mb-0">No instructions set for this group yet.</p>
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
                    <div class="table-responsive" style="max-height: 480px; overflow-y: auto;">
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

            <!-- Right: Date Range & Day-Wise Routine Schedule -->
            <div class="col-lg-8 col-md-7">
                <div class="bg-light rounded p-4 h-100 shadow-sm">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 border-bottom pb-2">
                        <div>
                            <h5 class="mb-0 fw-bold text-dark"><i class="fa fa-calendar-alt text-primary me-2"></i>Date Range & Day-Wise Exercise Routine</h5>
                            <small class="text-muted">Set a Date Range (e.g. Aug 27 to Sep 05), then assign exercises to specific days (e.g. Thursday, Friday). Exercises repeat on that day within the range.</small>
                        </div>
                        {{-- <button type="button" id="add-schedule-block" class="btn btn-outline-primary btn-sm">
                            <i class="fa fa-plus me-1"></i>Add Date Range
                        </button> --}}
                    </div>

                    <form action="{{ route('coach.groups.assign-exercises', $group->id) }}" method="POST" id="scheduleForm">
                        @csrf
                        <div id="schedule-blocks-container" class="mb-3">
                            <!-- Populated dynamically via JS -->
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center pt-3 border-top gap-2">
                            {{-- <button type="button" id="add-schedule-block-bottom" class="btn btn-outline-secondary btn-sm">
                                <i class="fa fa-plus me-1"></i>Add Another Date Range
                            </button> --}}
                            <button type="submit" class="btn btn-success px-4">
                                <i class="fa fa-save me-1"></i>Save All Exercises Schedule
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .flatpickr-day.flatpickr-disabled,
        .flatpickr-day.flatpickr-disabled:hover,
        .flatpickr-day.prevMonthDay,
        .flatpickr-day.nextMonthDay {
            color: #b0b5bc !important;
            background: #f1f3f5 !important;
            border-color: transparent !important;
            cursor: not-allowed !important;
            text-decoration: line-through !important;
            opacity: 0.45 !important;
        }
        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange {
            background: #0d6efd !important;
            border-color: #0d6efd !important;
            color: #fff !important;
        }
        .start-date-input, .end-date-input {
            background-color: #ffffff !important;
            cursor: pointer !important;
        }
    </style>

    <!-- Day Options Data for JS -->
    <script>
        const exercisesList = @json($exercises);
        const todayStr = @json(now()->toDateString());

        $(document).ready(function () {
            let scheduleIndex = 0;

            function getNextDayStr(dateStr) {
                if (!dateStr) return todayStr;
                const d = new Date(dateStr + 'T00:00:00');
                d.setDate(d.getDate() + 1);
                const year = d.getFullYear();
                const month = String(d.getMonth() + 1).padStart(2, '0');
                const day = String(d.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            function generateDayOptions(startDate, endDate, selectedVal) {
                let html = '<optgroup label="Specific Dates in this Range">';
                if (startDate && endDate && startDate <= endDate) {
                    const start = new Date(startDate + 'T00:00:00');
                    const end = new Date(endDate + 'T00:00:00');
                    const curr = new Date(start);

                    while (curr <= end) {
                        const year = curr.getFullYear();
                        const month = String(curr.getMonth() + 1).padStart(2, '0');
                        const day = String(curr.getDate()).padStart(2, '0');
                        const dateStr = `${year}-${month}-${day}`;
                        const dayName = curr.toLocaleDateString('en-US', { weekday: 'long' });
                        const monthName = curr.toLocaleDateString('en-US', { month: 'short' });
                        const label = `${dayName} (${monthName} ${curr.getDate()}, ${year})`;

                        const isSel = (selectedVal === dateStr) ? 'selected' : '';
                        html += `<option value="${dateStr}" ${isSel}>${label}</option>`;
                        curr.setDate(curr.getDate() + 1);
                    }
                }
                html += '</optgroup>';

                html += '<optgroup label="Recurring Weekly Days / Everyday">';
                const genericDays = ['Everyday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                genericDays.forEach(function(d) {
                    const isSel = (selectedVal && selectedVal.toLowerCase() === d.toLowerCase()) ? 'selected' : '';
                    const display = (d === 'Everyday') ? 'Everyday (All 7 Days)' : `Every ${d} (Recurring)`;
                    html += `<option value="${d}" ${isSel}>${display}</option>`;
                });
                html += '</optgroup>';

                return html;
            }

            // Load existing assignments
            const existingAssignments = @json($group->groupExercises);
            const groupedSchedules = {};

            existingAssignments.forEach(function(item) {
                const scheduleKey = item.start_date + '_' + item.end_date;
                if (!groupedSchedules[scheduleKey]) {
                    groupedSchedules[scheduleKey] = {
                        start_date: item.start_date,
                        end_date: item.end_date,
                        routines: []
                    };
                }
                let routine = groupedSchedules[scheduleKey].routines.find(r => r.day === item.day);
                if (!routine) {
                    routine = { day: item.day || 'Everyday', exercise_ids: [] };
                    groupedSchedules[scheduleKey].routines.push(routine);
                }
                routine.exercise_ids.push(item.exercise_id);
            });

            const scheduleEntries = Object.values(groupedSchedules);
            if (scheduleEntries.length > 0) {
                scheduleEntries.forEach(function(sch) {
                    addScheduleBlock(sch.start_date, sch.end_date, sch.routines);
                });
            } else {
                const defaultStart = todayStr;
                const defaultEnd = getNextDayStr(todayStr);
                const defaultRoutines = [{ day: defaultStart, exercise_ids: [] }];
                addScheduleBlock(defaultStart, defaultEnd, defaultRoutines);
            }

            $('#add-schedule-block, #add-schedule-block-bottom').click(function() {
                const defaultStart = todayStr;
                const defaultEnd = getNextDayStr(todayStr);
                const defaultRoutines = [{ day: defaultStart, exercise_ids: [] }];
                addScheduleBlock(defaultStart, defaultEnd, defaultRoutines);
            });

            $(document).on('click', '.add-day-routine-btn', function() {
                const scheduleCard = $(this).closest('.schedule-card');
                const sIdx = scheduleCard.data('s-index');
                const container = scheduleCard.find('.day-routines-container');
                const startDate = scheduleCard.find('.start-date-input').val() || todayStr;
                const endDate = scheduleCard.find('.end-date-input').val() || startDate;
                addDayRoutineRow(container, sIdx, startDate, endDate, startDate, []);
            });

            $(document).on('click', '.remove-day-row-btn', function() {
                const row = $(this).closest('.day-routine-row');
                const container = row.closest('.day-routines-container');
                row.remove();
                if (container.find('.day-routine-row').length === 0) {
                    const scheduleCard = container.closest('.schedule-card');
                    const sIdx = scheduleCard.data('s-index');
                    const startDate = scheduleCard.find('.start-date-input').val() || todayStr;
                    const endDate = scheduleCard.find('.end-date-input').val() || startDate;
                    addDayRoutineRow(container, sIdx, startDate, endDate, startDate, []);
                }
            });

            $(document).on('click', '.remove-schedule-btn', function() {
                $(this).closest('.schedule-card').remove();
            });

            function addScheduleBlock(startDate, endDate, routinesList = []) {
                const sIdx = scheduleIndex++;
                const minStart = (startDate && startDate < todayStr) ? startDate : todayStr;
                const minEnd = startDate ? startDate : todayStr;
                const initialEndDate = endDate || getNextDayStr(startDate || todayStr);

                const scheduleHtml = `
                    <div class="schedule-card bg-white border rounded-3 p-3 mb-4 shadow-sm" data-s-index="${sIdx}">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pb-3 mb-3 border-bottom bg-light p-3 rounded-2">
                            <div class="d-flex flex-wrap align-items-center gap-3">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary fs-6 px-2 py-1"><i class="fa fa-calendar-range me-1"></i>Date Range</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <label class="form-label small fw-bold mb-0 text-muted">From:</label>
                                    <input type="text" name="schedules[${sIdx}][start_date]" class="form-control form-control-sm start-date-input" value="${startDate || todayStr}" required style="max-width: 150px;" placeholder="YYYY-MM-DD" readonly>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <label class="form-label small fw-bold mb-0 text-muted">To:</label>
                                    <input type="text" name="schedules[${sIdx}][end_date]" class="form-control form-control-sm end-date-input" value="${initialEndDate}" required style="max-width: 150px;" placeholder="YYYY-MM-DD" readonly>
                                </div>
                            </div>
                            <div>
                                <button type="button" class="btn btn-outline-danger btn-sm remove-schedule-btn" title="Remove this entire date range">
                                    <i class="fa fa-trash me-1"></i>Remove Range
                                </button>
                            </div>
                        </div>

                        <div class="mb-2">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="fw-bold small text-dark"><i class="fa fa-tasks text-primary me-1"></i>Day-Wise Assigned Exercises:</span>
                                <button type="button" class="btn btn-sm btn-outline-primary add-day-routine-btn">
                                    <i class="fa fa-plus me-1"></i>Add Day Routine
                                </button>
                            </div>
                            <div class="day-routines-container">
                            </div>
                        </div>
                    </div>
                `;

                $('#schedule-blocks-container').append(scheduleHtml);
                const scheduleCard = $(`#schedule-blocks-container .schedule-card[data-s-index="${sIdx}"]`);
                const container = scheduleCard.find('.day-routines-container');

                const startEl = scheduleCard.find('.start-date-input')[0];
                const endEl = scheduleCard.find('.end-date-input')[0];

                function updateDayDropdowns() {
                    const curStart = $(startEl).val() || todayStr;
                    const curEnd = $(endEl).val() || curStart;
                    scheduleCard.find('.day-routine-row').each(function() {
                        const daySelect = $(this).find('.day-select');
                        const curVal = daySelect.val();
                        daySelect.html(generateDayOptions(curStart, curEnd, curVal));
                    });
                }

                let endPicker;
                const startPicker = flatpickr(startEl, {
                    dateFormat: "Y-m-d",
                    minDate: minStart,
                    defaultDate: startDate || todayStr,
                    disableMobile: true,
                    onChange: function(selectedDates, dateStr) {
                        if (endPicker && selectedDates.length > 0) {
                            endPicker.set('minDate', dateStr);
                            const nextDayVal = getNextDayStr(dateStr);
                            if (!endPicker.selectedDates.length || endPicker.selectedDates[0] < selectedDates[0]) {
                                endPicker.setDate(nextDayVal, false);
                            }
                            endPicker.jumpToDate(dateStr);
                        }
                        updateDayDropdowns();
                    }
                });

                endPicker = flatpickr(endEl, {
                    dateFormat: "Y-m-d",
                    minDate: minEnd,
                    defaultDate: initialEndDate,
                    disableMobile: true,
                    onChange: function() {
                        updateDayDropdowns();
                    },
                    onOpen: function(selectedDates, dateStr, instance) {
                        if (startPicker && startPicker.selectedDates.length > 0) {
                            instance.jumpToDate(startPicker.selectedDates[0]);
                        }
                    }
                });

                if (routinesList.length > 0) {
                    routinesList.forEach(function(r) {
                        addDayRoutineRow(container, sIdx, startDate || todayStr, initialEndDate, r.day, r.exercise_ids || []);
                    });
                } else {
                    addDayRoutineRow(container, sIdx, startDate || todayStr, initialEndDate, startDate || todayStr, []);
                }
            }

            // Ensure Select2 preserves order of newly selected options
            $(document).on('select2:select', '.select2-day-exercises', function (e) {
                if (e.params && e.params.data && e.params.data.element) {
                    var element = e.params.data.element;
                    var $element = $(element);
                    $element.detach();
                    $(this).append($element);
                    $(this).trigger("change");
                }
            });

            function addDayRoutineRow(container, sIdx, startDate, endDate, selectedDay = 'Monday', selectedExerciseIds = []) {
                const dayRowIndex = container.find('.day-routine-row').length;
                const dayOptionsHtml = generateDayOptions(startDate, endDate, selectedDay);

                let exerciseOptionsHtml = '';
                // 1. First append the selected exercises in their EXACT ordered sequence
                selectedExerciseIds.forEach(function(selectedId) {
                    const ex = exercisesList.find(e => Number(e.id) === Number(selectedId));
                    if (ex) {
                        const catName = ex.exercise_category ? ex.exercise_category.name : 'Exercise';
                        const exType = ex.exercise_type || 'count';
                        exerciseOptionsHtml += `<option value="${ex.id}" selected>${ex.name} (${catName}) &bull; ${exType}</option>`;
                    }
                });

                // 2. Then append all remaining unselected exercises
                exercisesList.forEach(function(ex) {
                    const isAlreadySelected = selectedExerciseIds.some(id => Number(id) === Number(ex.id));
                    if (!isAlreadySelected) {
                        const catName = ex.exercise_category ? ex.exercise_category.name : 'Exercise';
                        const exType = ex.exercise_type || 'count';
                        exerciseOptionsHtml += `<option value="${ex.id}">${ex.name} (${catName}) &bull; ${exType}</option>`;
                    }
                });

                const rowHtml = `
                    <div class="day-routine-row bg-light border rounded p-2 mb-2">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-3">
                                <label class="form-label small fw-bold mb-1 text-muted"><i class="fa fa-clock me-1 text-primary"></i>Day / Date:</label>
                                <select name="schedules[${sIdx}][days][${dayRowIndex}][day]" class="form-select form-select-sm fw-bold text-dark day-select" required>
                                    ${dayOptionsHtml}
                                </select>
                            </div>

                            <div class="col-md-8">
                                <label class="form-label small fw-bold mb-1 text-muted"><i class="fa fa-dumbbell me-1 text-primary"></i>Exercises for this Day:</label>
                                <select name="schedules[${sIdx}][days][${dayRowIndex}][exercise_ids][]" class="form-select select2-day-exercises" multiple required>
                                    ${exerciseOptionsHtml}
                                </select>
                            </div>

                            <!-- Delete Day Row -->
                            <div class="col-md-1 text-end">
                                <button type="button" class="btn btn-outline-danger btn-sm remove-day-row-btn mt-3" title="Remove this day routine">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;

                container.append(rowHtml);

                // Initialize select2 on the newly created dropdown
                container.find('.select2-day-exercises').last().select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: 'Search and pick exercises for this day...'
                });
            }
        });
    </script>
@endsection
