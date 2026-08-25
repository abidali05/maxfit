@extends('layouts.app')
@section('title', $group->name . ' - Sub-Groups')
@section('content')
    <div class="container-fluid pt-4 px-4" style="min-height: 82.5vh">
        <!-- Top Bar -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 border-bottom pb-3">
            <div>
                <a href="{{ route('exercise-groups.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
                    <i class="fa fa-arrow-left me-1"></i>Back to Exercise Groups
                </a>
                <div class="d-flex align-items-center gap-3">
                    @if($group->image)
                        <img src="{{ asset('storage/' . $group->image) }}" alt="{{ $group->name }}" class="rounded shadow-sm" width="56" height="56" style="object-fit: cover;">
                    @else
                        <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                            <i class="{{ $group->icon ?? 'fa fa-dumbbell' }} fa-2x"></i>
                        </div>
                    @endif
                    <div>
                        <h4 class="mb-0 fw-bold text-dark">{{ $group->name }}</h4>
                        <small class="text-muted">{{ $group->sub_title ?? 'Popular by Sport' }} &bull; 
                            @if($group->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </small>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('exercise-groups.edit', $group->id) }}" class="btn btn-outline-primary btn-sm">
                    <i class="fa fa-edit me-1"></i>Edit Group Info
                </a>
                <a href="{{ route('exercise-sub-groups.create', ['group_id' => $group->id]) }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus me-1"></i>Add Sub-Group
                </a>
            </div>
        </div>

        <!-- Sub-Groups Section -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="bg-light rounded p-4 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-2">
                        <h5 class="mb-0 fw-bold text-dark">
                            <i class="fa fa-sitemap text-primary me-2"></i>Sub-Groups in {{ $group->name }} ({{ $group->subGroups->count() }})
                        </h5>
                        <small class="text-muted">Exercises are grouped under these sub-groups (e.g. Batsman, Bowler) and loaded in mobile app.</small>
                    </div>

                    @if($group->subGroups->isEmpty())
                        <div class="text-center py-5 bg-white rounded border">
                            <i class="fa fa-folder-open fa-3x text-muted mb-3"></i>
                            <h5 class="fw-bold text-dark">No Sub-Groups Created Yet</h5>
                            <p class="text-muted small mb-3">Create sub-groups (e.g., Batsman, Bowler, All Rounder) and assign exercises to each.</p>
                            <a href="{{ route('exercise-sub-groups.create', ['group_id' => $group->id]) }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-plus me-1"></i>Create First Sub-Group
                            </a>
                        </div>
                    @else
                        <div class="row g-4">
                            @foreach($group->subGroups as $subGroup)
                                <div class="col-lg-6">
                                    <div class="card border bg-white shadow-sm rounded h-100">
                                        <div class="card-header bg-light d-flex align-items-center justify-content-between py-3 border-bottom">
                                            <div class="d-flex align-items-center gap-2">
                                                @if($subGroup->image)
                                                    <img src="{{ asset('storage/' . $subGroup->image) }}" alt="" class="rounded" width="36" height="36" style="object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                                        <i class="fa fa-tags"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-0 fw-bold text-dark">{{ $subGroup->name }}</h6>
                                                    <small class="text-muted">{{ $subGroup->sub_title ?? 'Sub-Group' }} &bull; <span class="badge bg-primary">{{ $subGroup->exercises->count() }} exercises</span></small>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('exercise-sub-groups.edit', $subGroup->id) }}" class="btn btn-sm btn-outline-primary" title="Edit Sub-Group & Exercises">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteSubModal{{ $subGroup->id }}" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="card-body p-3" style="max-height: 320px; overflow-y: auto;">
                                            <label class="form-label small fw-bold text-muted mb-2">Assigned Exercises (in exact sequence):</label>
                                            <ol class="list-group list-group-numbered mb-0">
                                                @forelse($subGroup->exercises as $ex)
                                                    <li class="list-group-item d-flex align-items-center justify-content-between p-2 border-0 border-bottom bg-transparent">
                                                        <div class="ms-2 me-auto d-flex align-items-center gap-2 text-truncate">
                                                            <img src="{{ $ex->image ? asset('storage/' . $ex->image) : asset('assets/images/user.jpg') }}" class="rounded" width="28" height="28" style="object-fit: cover;">
                                                            <div class="text-truncate">
                                                                <div class="fw-bold small text-dark text-truncate">{{ $ex->name }}</div>
                                                                <small class="text-muted" style="font-size: 11px;">{{ $ex->exercise_category->name ?? 'Exercise' }} &bull; {{ $ex->fitness_level ?? 'Level' }} &bull; {{ $ex->exercise_type ?? 'count' }}</small>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @empty
                                                    <li class="list-group-item text-muted small border-0 py-3 text-center">No exercises assigned to this sub-group.</li>
                                                @endforelse
                                            </ol>
                                        </div>
                                    </div>

                                    <!-- Delete Sub-Group Modal -->
                                    <div class="modal fade" id="deleteSubModal{{ $subGroup->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete Sub-Group</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete sub-group <strong>"{{ $subGroup->name }}"</strong>?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('exercise-sub-groups.destroy', $subGroup->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
