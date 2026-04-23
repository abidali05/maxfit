@extends('layouts.app')
@section('title', 'Users')
@section('content')
    <div class="container-fluid pt-4 px-4 users-page" style="min-height: 82.5vh">
        <style>
            .users-filter-card,
            .users-table-card {
                background: #ffffff;
                border: 1px solid rgba(15, 23, 42, 0.08);
                box-shadow: 0 18px 45px rgba(15, 23, 42, 0.06);
                border-radius: 1.5rem;
            }

            .users-filter-card {
                position: relative;
                overflow: hidden;
            }

            .users-filter-card::before {
                content: "";
                position: absolute;
                inset: 0 auto 0 0;
                width: 0.35rem;
                background: linear-gradient(180deg, #0d6efd, #20c997);
            }

            .users-filter-header,
            .users-table-header {
                padding: 1.35rem 1.4rem 1rem;
            }

            .users-filter-header {
                gap: 1rem;
            }

            .users-filter-title,
            .users-table-title {
                font-size: 1rem;
                font-weight: 800;
                color: #0f172a;
                margin: 0;
            }

            .users-filter-subtitle,
            .users-table-subtitle {
                color: #64748b;
                font-size: 0.88rem;
                margin-top: 0.25rem;
            }

            .users-filter-body {
                padding: 0 1.4rem 1.4rem;
            }

            .users-filter-grid label {
                font-size: 0.72rem;
                font-weight: 800;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                color: #64748b;
                margin-bottom: 0.45rem;
            }

            .users-filter-grid .form-control,
            .users-filter-grid .form-select {
                min-height: 48px;
                border-radius: 14px;
                border-color: #dbe3ef;
                background: #f8fbff;
                box-shadow: none;
            }

            .users-filter-grid .form-control:focus,
            .users-filter-grid .form-select:focus {
                border-color: #0d6efd;
                box-shadow: 0 0 0 0.18rem rgba(13, 110, 253, 0.14);
            }

            .users-filter-actions .btn {
                min-height: 42px;
                border-radius: 12px;
                font-weight: 700;
                font-size: 0.82rem;
                padding: 0.42rem 0.95rem;
            }

            .users-filter-trigger {
                border-radius: 999px;
                padding: 0.38rem 0.85rem;
                min-width: 138px;
                font-size: 0.82rem;
            }

            .users-top-actions {
                display: flex;
                align-items: center;
                gap: 0.65rem;
                flex-wrap: wrap;
            }

            .users-top-actions .btn,
            .users-filter-actions .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                line-height: 1;
            }

            .users-top-action-btn {
                min-height: 40px;
                min-width: 126px;
                border-radius: 12px;
                padding: 0.45rem 0.95rem;
                font-size: 0.82rem;
                font-weight: 700;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                line-height: 1;
            }

            .users-table-wrapper {
                padding: 0 1.4rem 1.4rem;
            }

            .users-table {
                margin-bottom: 0;
                border-collapse: separate;
                border-spacing: 0;
            }

            .users-table thead th {
                background: #0f172a;
                color: #ffffff;
                border: 0 !important;
                font-size: 0.72rem;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                padding: 1rem 1rem;
                white-space: nowrap;
            }

            .users-table thead th:first-child {
                border-top-left-radius: 1rem;
            }

            .users-table thead th:last-child {
                border-top-right-radius: 1rem;
            }

            .users-table tbody td {
                padding: 1rem 1rem;
                vertical-align: middle;
                border-color: #eef2f7;
            }

            .users-table tbody tr {
                transition: background-color 0.18s ease, transform 0.18s ease;
            }

            .users-table tbody tr:hover {
                background: #f8fbff;
            }

            .users-avatar {
                width: 48px;
                height: 48px;
                object-fit: cover;
                border-radius: 14px;
                border: 2px solid #e2e8f0;
            }

            .users-name {
                font-weight: 800;
                color: #0f172a;
            }

            .users-meta {
                font-size: 0.82rem;
                color: #64748b;
            }

            .user-action-btn {
                width: 36px;
                height: 36px;
                border-radius: 12px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border: 1px solid rgba(15, 23, 42, 0.08);
                background: #ffffff;
                color: #475569;
            }

            .user-action-btn:hover {
                background: #f8fbff;
                color: #0f172a;
            }

            .user-action-toggle {
                border-radius: 10px;
                padding: 0.3rem 0.7rem;
                font-weight: 700;
                font-size: 0.78rem;
            }

            .dt-footer {
                padding: 0.95rem 1.4rem 1.2rem;
            }

            .dataTables_info {
                color: #64748b !important;
                font-size: 0.9rem;
            }

            .dataTables_paginate .paginate_button {
                border-radius: 10px !important;
                border: 1px solid #dbe3ef !important;
                background: #ffffff !important;
                color: #334155 !important;
                margin: 0 0.15rem !important;
                padding: 0.35rem 0.7rem !important;
            }

            .dataTables_paginate .paginate_button.current {
                background: #0d6efd !important;
                border-color: #0d6efd !important;
                color: #ffffff !important;
                font-weight: 700;
                box-shadow: 0 8px 18px rgba(13, 110, 253, 0.18);
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button.current,
            .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
                color: #ffffff !important;
            }
        </style>
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="users-filter-card mb-4">
                    <div class="users-filter-header d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                        <div>
                            <p class="users-filter-title">Filters</p>
                            <div class="users-filter-subtitle">Refine the user list by account, location, and profile data.</div>
                        </div>
                        <div class="users-top-actions">
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary users-top-action-btn">Clear Filters</a>
                            <a href="{{ route('users.create') }}" class="btn btn-primary users-top-action-btn">Add New User</a>
                        </div>
                    </div>
                    <div class="users-filter-body">
                        <form method="GET" action="{{ route('users.index') }}">
                            <div class="row g-3 users-filter-grid">
                                <div class="col-12 col-md-6 col-xl-3">
                                    <label class="form-label">Email</label>
                                    <input type="text" name="email" class="form-control"
                                        value="{{ request('email') }}" placeholder="Search email">
                                </div>
                                <div class="col-12 col-md-6 col-xl-3">
                                    <label class="form-label">Age</label>
                                    <input type="number" name="age" class="form-control"
                                        value="{{ request('age') }}" placeholder="Enter age">
                                </div>
                                <div class="col-12 col-md-6 col-xl-3">
                                    <label class="form-label">Organization</label>
                                    <select name="organization" class="form-select">
                                        <option value="">All Organizations</option>
                                        @foreach ($data['organizations'] as $organization)
                                            <option value="{{ $organization->id }}"
                                                @selected(request('organization') == $organization->id)>
                                                {{ $organization->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 col-xl-3">
                                    <label class="form-label">City</label>
                                    <select name="city" class="form-select">
                                        <option value="">All Cities</option>
                                        @foreach ($data['cities'] as $city)
                                            <option value="{{ $city->id }}" @selected(request('city') == $city->id)>
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 col-xl-3">
                                    <label class="form-label">Province</label>
                                    <select name="province" class="form-select">
                                        <option value="">All Provinces</option>
                                        @foreach ($data['provinces'] as $province)
                                            <option value="{{ $province->id }}" @selected(request('province') == $province->id)>
                                                {{ $province->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 col-xl-3">
                                    <label class="form-label">Class</label>
                                    <select name="class" class="form-select">
                                        <option value="">All Classes</option>
                                        @foreach ($data['classes'] as $className)
                                            <option value="{{ $className }}" @selected(request('class') == $className)>
                                                {{ $className }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 col-xl-3">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-select">
                                        <option value="">All Genders</option>
                                        @foreach ($data['genders'] as $gender)
                                            <option value="{{ $gender }}" @selected(request('gender') == $gender)>
                                                {{ $gender }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 col-xl-3 d-flex align-items-end users-filter-actions gap-2">
                                    <button type="submit" class="btn btn-primary w-100">Apply</button>
                                    <a href="{{ route('users.index') }}" class="btn btn-outline-danger w-100">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="users-table-card">
                    <div class="users-table-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <div>
                            <p class="users-table-title">Users Table</p>
                            <div class="users-table-subtitle">{{ $users->count() }} record(s) matched your current filters.</div>
                        </div>
                        {{-- <a href="{{ route('users.create') }}" class="btn btn-dark">Add New User</a> --}}
                    </div>
                    <div class="users-table-wrapper table-responsive">
                        <table class="table align-middle datatable users-table">
                            <thead>
                                <tr>
                                    <th scope="col">S.No</th>
                                    <th scope="col">User</th>
                                    <th scope="col">Company</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Number</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Role</th>
                                    <th scope="col" class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $i=> $user)
                                    @php
                                        $status = $user->status ?? 'inactive';
                                        $age = 'N/A';
                                        if (!empty($user->dob)) {
                                            try {
                                                $age = \Illuminate\Support\Carbon::parse($user->dob)->age;
                                            } catch (\Throwable $e) {
                                                $age = 'N/A';
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="{{ $user->image ?? asset('assets/images/user.jpg') }}" alt="User Image" class="users-avatar">
                                                <div>
                                                    <div class="users-name">{{ $user->name }}</div>
                                                    <div class="users-meta">Age: {{ $age }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $user->branch->name ?? 'N/A' }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->number }}</td>
                                        <td>
                                            <span class="badge rounded-pill bg-{{ $status === 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($status) }}
                                            </span>
                                        </td>
                                        <td class="text-capitalize">{{ $user->role }}</td>
                                        <td class="text-end">
                                            <div class="d-inline-flex align-items-center justify-content-end gap-2">
                                                <form action="{{ route('users.toggle-status', $user->id) }}" method="POST" class="mb-0">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-outline-{{ $status === 'active' ? 'warning' : 'success' }} user-action-toggle">
                                                        {{ $status === 'active' ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                </form>
                                                <a href="{{ route('users.edit', $user->id) }}" class="user-action-btn" title="Edit">
                                                    <i class="fa fa-pen"></i>
                                                </a>
                                                <button type="button" class="user-action-btn text-danger" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $user->id }}" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($users->isEmpty())
                        <div class="px-4 pb-4">
                            <div class="text-center py-5 text-muted border rounded-4 bg-light">
                                No users found for the selected filters.
                            </div>
                        </div>
                    @endif
                </div>

                @foreach ($users as $user)
                    <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $user->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel{{ $user->id }}">Delete Confirmation</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete this record?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="id" value="{{ $user->id }}">
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
