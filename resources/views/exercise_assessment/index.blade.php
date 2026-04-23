@extends('layouts.app')
@section('title', 'Exercise Assessment - Sets')
@section('content')
    <div class="px-4 pt-4 container-fluid" style="min-height: 82.5vh">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="p-4 text-center rounded bg-light">
                    <div class="mb-4 d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">Exercise Assessment - Sets</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle text-start table-bordered datatable">
                            <thead>
                                <tr class="text-dark">
                                    <th scope="col">S.No</th>
                                    <th scope="col">Set Name</th>
                                    <th scope="col">Genz</th>
                                    <th scope="col">Total Exercises</th>
                                    <th scope="col" class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sets as $index => $set)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $set->name }}</td>
                                    <td>{{ ucfirst($set->genz) }}</td>
                                    <td>{{ $set->exercises->count() }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('exercise-assessment.set-details', $set->id) }}" class="btn btn-sm btn-primary">View Exercises</a>
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
