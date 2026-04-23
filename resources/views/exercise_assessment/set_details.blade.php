@extends('layouts.app')
@section('title', 'Set Exercises - ' . $set->name)
@section('content')
    <div class="px-4 pt-4 container-fluid" style="min-height: 82.5vh">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="p-4 text-center rounded bg-light">
                    <div class="mb-4 d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">Set: {{ $set->name }} - Exercises</h6>
                        <a href="{{ route('exercise-assessment.index') }}" class="btn btn-secondary">Back to Sets</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle text-start table-bordered datatable">
                            <thead>
                                <tr class="text-dark">
                                    <th scope="col">S.No</th>
                                    <th scope="col">Exercise Name</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Genz</th>
                                    <th scope="col" class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($set->exercises as $index => $exercise)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $exercise->name }}</td>
                                    <td>{{ $exercise->exercise_category->name ?? 'N/A' }}</td>
                                    <td>{{ ucfirst($exercise->genz) }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('exercise-assessment.exercise-details', $exercise->id) }}" class="btn btn-sm btn-info">View Assessments</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection