@extends('layouts.app')
@section('title', 'Athlete Groups')
@section('content')
    <div class="container-fluid pt-4 px-4" style="min-height: 82.5vh">
        <div class="row g-4">
            <div class="col-12">
                <div class="bg-light rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h5 class="mb-0">Athlete Groups (Admin Control)</h5>
                            <small class="text-muted">Monitor and suspend athlete groups created by coaches.</small>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-dark">
                                    <th scope="col">#</th>
                                    <th scope="col">Group Name</th>
                                    <th scope="col">Coach Name</th>
                                    <th scope="col">Total Athletes</th>
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
                                        <td>{{ $group->coach->name ?? 'N/A' }}</td>
                                        <td>{{ $group->group_users_count }}</td>
                                        <td>
                                            @if($group->status === 'active')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Suspended</span>
                                            @endif
                                        </td>
                                        <td>{{ $group->created_at->format('Y-m-d H:i') }}</td>
                                        <td class="d-flex gap-2">
                                            <a class="btn btn-sm btn-primary" href="{{ route('groups.show', $group->id) }}">
                                                <i class="fa fa-eye me-1"></i>View Details
                                            </a>
                                            @if($group->status === 'active')
                                                <form action="{{ route('groups.suspend', $group->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to suspend this group?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fa fa-ban me-1"></i>Suspend
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('groups.unsuspend', $group->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fa fa-check me-1"></i>Activate
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">No athlete groups found.</td>
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
