@extends('layouts.coach.app')
@section('title', 'Group Details')
@section('content')
    <div class="container-fluid pt-4 px-4" style="min-height: 82.5vh">
        <!-- Row 1: Details & Athletes -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                    <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-2">
                        <h5 class="mb-0">Group Information</h5>
                        <div class="d-flex gap-1">
                            <a href="{{ route('coach.reports.index', ['group_id' => $group->id]) }}" class="btn btn-outline-primary btn-sm"><i class="fa fa-chart-line me-1"></i>Report</a>
                            <a href="{{ route('coach.groups.edit', $group->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit me-1"></i>Edit</a>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted d-block small">Group Name</label>
                        <span class="fw-bold fs-5 text-dark">{{ $group->name }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted d-block small">Status</label>
                        @if($group->status === 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Suspended</span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="text-muted d-block small">Coach</label>
                        <span>{{ $group->coach->name }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted d-block small">Created At</label>
                        <span>{{ $group->created_at->format('Y-m-d H:i') }}</span>
                    </div>

                    <h5 class="mt-4 mb-3 border-bottom pb-2">Criteria Filters Used</h5>
                    @if($group->age_group)
                        <div class="mb-2">
                            <span class="badge bg-secondary">Age: {{ $group->age_group }}</span>
                        </div>
                    @endif
                    @if($group->gender)
                        <div class="mb-2">
                            <span class="badge bg-secondary">Gender: {{ $group->gender }}</span>
                        </div>
                    @endif
                    @if($group->genz)
                        <div class="mb-2">
                            <span class="badge bg-secondary">GenZ: {{ $group->genz }}</span>
                        </div>
                    @endif
                    @if($group->country)
                        <div class="mb-2">
                            <span class="badge bg-secondary">Country: {{ $group->countryRelation->name ?? $group->country }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-md-8">
                <div class="bg-light rounded p-4 h-100">
                    <h5 class="mb-4 border-bottom pb-2">Invited Athletes</h5>
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-dark">
                                    <th scope="col">#</th>
                                    <th scope="col">Athlete Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Invitation Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($group->groupUsers as $gu)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $gu->user->name ?? 'N/A' }}</td>
                                        <td>{{ $gu->user->email ?? 'N/A' }}</td>
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
                                        <td colspan="4" class="text-center text-muted py-4">No athletes added to this group.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: Instructions & Exercises -->
        <div class="row g-4">
            <!-- Instructions Panel -->
            <div class="col-md-4">
                <div class="bg-light rounded p-4 h-100">
                    <h5 class="mb-4 border-bottom pb-2"><i class="fa fa-info-circle text-primary me-2"></i>Group Instructions</h5>
                    <div class="p-3 bg-white border rounded" style="min-height: 200px;">
                        @if($group->instructions)
                            {!! $group->instructions !!}
                        @else
                            <p class="text-muted italic">No instructions set for this group yet.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Exercises Panel -->
            <div class="col-md-8">
                <div class="bg-light rounded p-4 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-2">
                        <h5 class="mb-0"><i class="fa fa-running text-primary me-2"></i>Exercise Assignments</h5>
                        <button type="button" id="add-exercise-row" class="btn btn-primary btn-sm"><i class="fa fa-plus me-2"></i>Add Exercises</button>
                    </div>

                    <form action="{{ route('coach.groups.assign-exercises', $group->id) }}" method="POST">
                        @csrf
                        <div id="exercise-rows" class="mb-4">
                            <!-- Populated dynamically via JS -->
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save me-2"></i>Save Exercises</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            let rowIndex = 0;

            // Load initial/existing assignments, grouping them by date range
            const existingAssignments = @json($group->groupExercises);
            const grouped = {};
            
            existingAssignments.forEach(function(item) {
                const key = item.start_date + '_' + item.end_date;
                if (!grouped[key]) {
                    grouped[key] = {
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
                    addExerciseRow(item.exercise_ids, item.start_date, item.end_date);
                });
            } else {
                // Add one empty row by default
                addExerciseRow();
            }

            $('#add-exercise-row').click(function() {
                addExerciseRow();
            });

            $(document).on('click', '.remove-exercise-row', function() {
                $(this).closest('.exercise-row').remove();
            });

            function addExerciseRow(exerciseIds = [], startDate = '', endDate = '') {
                const index = rowIndex++;
                const rowHtml = `
                    <div class="row g-2 mb-2 exercise-row align-items-end border-bottom pb-2">
                        <div class="col-md-5">
                            <label class="form-label small fw-bold mb-1">Select Exercises</label>
                            <select name="assignments[${index}][exercise_ids][]" class="form-select select2-exercise" multiple required>
                                @foreach($exercises as $exercise)
                                    <option value="{{ $exercise->id }}" ${exerciseIds.includes({{ $exercise->id }}) ? 'selected' : ''}>{{ $exercise->name }} ({{ $exercise->exercise_category->name ?? 'N/A' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">Start Date</label>
                            <input type="date" name="assignments[${index}][start_date]" class="form-control" value="${startDate}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold mb-1">End Date</label>
                            <input type="date" name="assignments[${index}][end_date]" class="form-control" value="${endDate}" required>
                        </div>
                        <div class="col-md-1 text-end">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-exercise-row mb-1"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>
                `;
                $('#exercise-rows').append(rowHtml);

                // Initialize select2 on the newly created dropdown
                $('#exercise-rows').find('.select2-exercise').last().select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: 'Choose exercises...'
                });
            }
        });
    </script>
@endsection
