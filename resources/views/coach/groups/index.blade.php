@extends('layouts.coach.app')
@section('title', 'Athlete Groups')
@section('content')
    <div class="container-fluid pt-4 px-4" style="min-height: 82.5vh">
        <div class="bg-light rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h5 class="mb-0">Athlete Groups</h5>
                    <small class="text-muted">Manage your custom athlete groups and view their invitation statuses.</small>
                </div>
                <a href="{{ route('coach.groups.create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus me-2"></i>Create Group</a>
            </div>

            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                        <tr class="text-dark">
                            <th scope="col">#</th>
                            <th scope="col">Group Name</th>
                            <th scope="col">Total Athletes Invited</th>
                            <th scope="col">Status</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($groups as $group)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $group->name }}</td>
                                <td>{{ $group->group_users_count }}</td>
                                <td>
                                    @if($group->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Suspended</span>
                                    @endif
                                </td>
                                <td>{{ $group->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a class="btn btn-sm btn-primary" href="{{ route('coach.groups.show', $group->id) }}">
                                        <i class="fa fa-eye me-1"></i>View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No athlete groups created yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
