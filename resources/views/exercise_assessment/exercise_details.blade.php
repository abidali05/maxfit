@extends('layouts.app')
@section('title', 'Exercise Assessments - ' . $exercise->name)
@section('content')
    <div class="px-4 pt-4 container-fluid" style="min-height: 82.5vh">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="p-4 text-center rounded bg-light">
                    <div class="mb-4 d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">Exercise: {{ $exercise->name }} - User Assessments</h6>
                        <a href="javascript:history.back()" class="btn btn-secondary">Back</a>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body text-start">
                                    <h6 class="card-title">Exercise Details</h6>
                                    <p><strong>Name:</strong> {{ $exercise->name }}</p>
                                    <p><strong>Category:</strong> {{ $exercise->exercise_category->name ?? 'N/A' }}</p>
                                    <p><strong>Genz:</strong> {{ ucfirst($exercise->genz) }}</p>
                                    @if ($exercise->description)
                                        <p><strong>Description:</strong> {{ $exercise->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table mb-0 align-middle text-start table-bordered datatable">
                            <thead>
                                <tr class="text-dark">
                                    <th scope="col">S.No</th>
                                    <th scope="col">User Name</th>
                                    <th scope="col">Assessment Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assessments as $userId => $userAssessments)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $userAssessments->first()->user->name ?? 'N/A' }}</td>
                                        <td>
                                            @foreach ($userAssessments as $assessment)
                                                <div>
                                                    <span class="badge bg-primary">
                                                        {{ $assessment->value ?? 'No data' }}
                                                    </span>
                                                    <small class="text-muted">
                                                        ({{ $assessment->created_at->format('Y-m-d H:i') }})
                                                    </small>
                                                </div>
                                            @endforeach
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            No assessments found for this exercise
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
