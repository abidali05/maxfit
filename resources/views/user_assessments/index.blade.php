@extends('layouts.app')
@section('title', 'User Assessments')
@section('content')
    <div class="px-4 pt-4 container-fluid" style="min-height: 82.5vh">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="p-4 rounded bg-light">
                    <div class="mb-4 d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">User Daily Assessments</h6>
                        <form method="GET" class="d-flex align-items-center">
                            <label for="date" class="me-2">Date:</label>
                            <input type="date" name="date" id="date" class="form-control me-2" value="{{ $date }}" style="width: auto;">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </form>
                    </div>

                    @if($assessments->count() > 0)
                        @foreach($assessments as $userId => $userAssessments)
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0">{{ $userAssessments->first()->user->name ?? 'Unknown User' }}</h6>
                                    <small class="text-muted">Total Assessments: {{ $userAssessments->count() }}</small>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Set</th>
                                                    <th>Exercise</th>
                                                    <th>Value</th>
                                                    <th>Time</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($userAssessments as $assessment)
                                                <tr>
                                                    <td>{{ $assessment->set->name ?? 'N/A' }}</td>
                                                    <td>{{ $assessment->exercise->name ?? 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge bg-primary">{{ $assessment->count }}</span>
                                                        <small class="text-muted d-block">{{ $assessment->exercise->exercise_type ?? 'Per Count' }}</small>
                                                    </td>
                                                    <td>{{ $assessment->created_at->format('H:i:s') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <h5 class="text-muted">No assessments found for {{ $date }}</h5>
                            <p class="text-muted">Try selecting a different date.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
