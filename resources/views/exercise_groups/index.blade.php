@extends('layouts.app')
@section('title', 'Exercise Groups')
@section('content')
    <div class="container-fluid pt-4 px-4" style="min-height: 82.5vh">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-2">
                        <div>
                            <h5 class="mb-1 text-dark fw-bold"><i class="fa fa-layer-group text-primary me-2"></i>Exercise Groups (Sports / Workouts)</h5>
                            <small class="text-muted">Manage exercise collections by sport, difficulty, or custom workouts for mobile discovery.</small>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('exercises.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fa fa-dumbbell me-1"></i>All Exercises</a>
                            <a href="{{ route('exercise-groups.create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus me-1"></i>Add Exercise Group</a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0 datatable">
                            <thead>
                                <tr class="text-dark">
                                    <th scope="col" style="width: 60px;">#</th>
                                    <th scope="col">Group Image / Icon</th>
                                    <th scope="col">Group Name</th>
                                    <th scope="col">Tagline / Subtitle</th>
                                    <th scope="col">Total Exercises</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" class="text-end" style="width: 100px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($exerciseGroups as $i => $group)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>
                                            @if($group->image)
                                                <img src="{{ asset('storage/' . $group->image) }}" alt="{{ $group->name }}" class="rounded" width="48" height="48" style="object-fit: cover;">
                                            @else
                                                <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                                    <i class="{{ $group->icon ?? 'fa fa-dumbbell' }} fa-lg"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="fw-bold fs-6 text-dark">{{ $group->name }}</td>
                                        <td class="text-muted">{{ $group->sub_title ?? 'N/A' }}</td>
                                        <td><span class="badge bg-info text-dark fs-6">{{ $group->exercises_count }} exercises</span></td>
                                        <td>
                                            @if($group->status === 'active')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('exercise-groups.edit', $group->id) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $group->id }}" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $group->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete Exercise Group</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete the group <strong>"{{ $group->name }}"</strong>?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('exercise-groups.destroy', $group->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
