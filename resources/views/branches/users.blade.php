@extends('layouts.app')
@section('title', 'Branch Users')
@section('content')
    <div class="px-4 pt-4 container-fluid" style="min-height: 82.5vh">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="p-4 text-center rounded bg-light">
                    <div class="mb-4 d-flex align-items-center justify-content-between">
                        <h4>Users for Branch: {{ $branch->name }}</h4>
                        <a href="{{ route('branches.index') }}" class="btn btn-secondary">Back to Branches</a>
                    </div>
                    <div class="table-responsive">
                        <table class="datatable table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $i => $user)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->number ?? '-' }}</td>
                                        <td>
                                            <span class="badge {{ $user->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ ucfirst($user->status ?? 'active') }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary" title="Edit User">
                                                <i class="fa fa-eye"></i> View
                                            </a>
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
