@extends('layouts.app')
@section('title', 'Group Details')
@section('content')
    <div class="container-fluid pt-4 px-4" style="min-height: 82.5vh">
        <!-- Row 1: Details & Athletes -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="bg-light rounded p-4 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-2">
                        <h5 class="mb-0">Group Details</h5>
                        <a href="{{ route('groups.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fa fa-arrow-left me-2"></i>Back</a>
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
                        <label class="text-muted d-block small">Created By (Coach)</label>
                        <span class="fw-bold">{{ $group->coach->name ?? 'N/A' }}</span>
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
                            <span class="badge bg-secondary">Country: {{ $group->country }}</span>
                        </div>
                    @endif

                    <div class="mt-4 pt-3 border-top">
                        @if($group->status === 'active')
                            <form action="{{ route('groups.suspend', $group->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to suspend this group?');">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fa fa-ban me-2"></i>Suspend Group
                                </button>
                            </form>
                        @else
                            <form action="{{ route('groups.unsuspend', $group->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fa fa-check me-2"></i>Activate Group
                                </button>
                            </form>
                        @endif
                    </div>
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

        <!-- Row 2: Instructions & Assigned Exercises -->
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
                    <h5 class="mb-4 border-bottom pb-2"><i class="fa fa-running text-primary me-2"></i>Assigned Exercises</h5>
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-dark">
                                    <th scope="col">#</th>
                                    <th scope="col">Exercise Name</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Start Date</th>
                                    <th scope="col">End Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($group->groupExercises as $ge)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $ge->exercise->name ?? 'N/A' }}</td>
                                        <td>{{ $ge->exercise->exercise_category->name ?? 'N/A' }}</td>
                                        <td>{{ $ge->start_date }}</td>
                                        <td>{{ $ge->end_date }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No exercises assigned to this group.</td>
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
