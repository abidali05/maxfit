@extends('layouts.app')
@section('title', 'Athlete Groups')
@section('content')
    <div class="container-fluid pt-4 px-4" style="min-height: 82.5vh">
        <div class="bg-light rounded p-4 shadow-sm">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                <div>
                    <h4 class="mb-1 text-dark fw-bold"><i class="fa fa-users text-primary me-2"></i>Athlete Groups</h4>
                    <small class="text-muted">Manage all athlete groups across all coaches, assign routines, and monitor participation.</small>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fa fa-chart-line me-1"></i>All Group Reports
                    </a>
                    <a href="{{ route('groups.create') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus me-2"></i>Create Group
                    </a>
                </div>
            </div>

            <!-- Filter by Coach -->
            <div class="card border-0 mb-4 bg-white rounded p-3">
                <form action="{{ route('groups.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted mb-1">Filter by Coach:</label>
                        <select name="coach_id" class="form-select form-select-sm select2" onchange="this.form.submit()">
                            <option value="">All Coaches</option>
                            @foreach($coaches as $c)
                                <option value="{{ $c->id }}" {{ $selectedCoachId == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if($selectedCoachId)
                        <div class="col-md-2 mt-4">
                            <a href="{{ route('groups.index') }}" class="btn btn-outline-secondary btn-sm">Clear Filter</a>
                        </div>
                    @endif
                </form>
            </div>

            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                        <tr class="text-dark bg-white">
                            <th scope="col">#</th>
                            <th scope="col">Group Name</th>
                            <th scope="col">Assigned Coach</th>
                            <th scope="col" class="text-center">Total Athletes</th>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col">Created At</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($groups as $group)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="fw-bold text-dark fs-6">{{ $group->name }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-primary border">{{ $group->coach->name ?? 'N/A' }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info text-dark">{{ $group->group_users_count }} athletes</span>
                                </td>
                                <td class="text-center">
                                    @if($group->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Suspended</span>
                                    @endif
                                </td>
                                <td>{{ $group->created_at->format('M d, Y') }}</td>
                                <td class="text-center">
                                    <div class="btn-group gap-1" role="group">
                                        <a class="btn btn-sm btn-primary" href="{{ route('groups.show', $group->id) }}" title="View Group Details & Routines">
                                            <i class="fa fa-eye me-1"></i>View
                                        </a>
                                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('groups.edit', $group->id) }}" title="Edit Group">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        @if($group->status === 'active')
                                            <form action="{{ route('groups.suspend', $group->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Suspend this group?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-warning" title="Suspend Group">
                                                    <i class="fa fa-ban"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('groups.unsuspend', $group->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Activate Group">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('groups.destroy', $group->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to completely delete this group?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Group">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="fa fa-users fa-2x mb-2 text-muted"></i>
                                    <p class="mb-0">No athlete groups found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
