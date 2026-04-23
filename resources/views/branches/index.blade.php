@extends('layouts.app')
@section('title', 'Branches')
@section('content')
    <div class="container-fluid pt-4 px-4 branches-page" style="min-height: 82.5vh">
        <style>
            .branches-filter-card,
            .branches-table-card {
                background: #ffffff;
                border: 1px solid rgba(15, 23, 42, 0.08);
                box-shadow: 0 18px 45px rgba(15, 23, 42, 0.06);
                border-radius: 1.5rem;
            }

            .branches-filter-card {
                position: relative;
                overflow: hidden;
            }

            .branches-filter-card::before {
                content: "";
                position: absolute;
                inset: 0 auto 0 0;
                width: 0.35rem;
                background: linear-gradient(180deg, #20c997, #0d6efd);
            }

            .branches-filter-header,
            .branches-table-header {
                padding: 1.35rem 1.4rem 1rem;
            }

            .branches-filter-title,
            .branches-table-title {
                font-size: 1rem;
                font-weight: 800;
                color: #0f172a;
                margin: 0;
            }

            .branches-filter-subtitle,
            .branches-table-subtitle {
                color: #64748b;
                font-size: 0.88rem;
                margin-top: 0.25rem;
            }

            .branches-filter-body {
                padding: 0 1.4rem 1.4rem;
            }

            .branches-filter-grid label {
                font-size: 0.72rem;
                font-weight: 800;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                color: #64748b;
                margin-bottom: 0.45rem;
            }

            .branches-filter-grid .form-control,
            .branches-filter-grid .form-select {
                min-height: 48px;
                border-radius: 14px;
                border-color: #dbe3ef;
                background: #f8fbff;
                box-shadow: none;
            }

            .branches-filter-grid .form-control:focus,
            .branches-filter-grid .form-select:focus {
                border-color: #20c997;
                box-shadow: 0 0 0 0.18rem rgba(32, 201, 151, 0.14);
            }

            .branches-filter-actions .btn {
                min-height: 42px;
                border-radius: 12px;
                font-weight: 700;
                font-size: 0.82rem;
                padding: 0.42rem 0.95rem;
            }

            .branches-top-actions {
                display: flex;
                align-items: center;
                gap: 0.65rem;
                flex-wrap: wrap;
            }

            .branches-top-actions .btn,
            .branches-filter-actions .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                line-height: 1;
            }

            .branches-top-action-btn {
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

            .branches-table-wrapper {
                padding: 0 1.4rem 1.4rem;
            }

            .branches-table {
                margin-bottom: 0;
                border-collapse: separate;
                border-spacing: 0;
            }

            .branches-table thead th {
                background: #0f172a;
                color: #ffffff;
                border: 0 !important;
                font-size: 0.72rem;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                padding: 1rem 1rem;
                white-space: nowrap;
            }

            .branches-table thead th:first-child {
                border-top-left-radius: 1rem;
            }

            .branches-table thead th:last-child {
                border-top-right-radius: 1rem;
            }

            .branches-table tbody td {
                padding: 1rem 1rem;
                vertical-align: middle;
                border-color: #eef2f7;
            }

            .branches-table tbody tr:hover {
                background: #f8fbff;
            }

            .branch-avatar {
                width: 48px;
                height: 48px;
                object-fit: cover;
                border-radius: 14px;
                border: 2px solid #e2e8f0;
            }

            .branch-name {
                font-weight: 800;
                color: #0f172a;
            }

            .branch-meta {
                font-size: 0.82rem;
                color: #64748b;
            }

            .branch-action-btn {
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

            .branch-action-btn:hover {
                background: #f8fbff;
                color: #0f172a;
            }

            .branch-status-pill {
                border-radius: 999px;
                padding: 0.38rem 0.75rem;
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
                background: #20c997 !important;
                border-color: #20c997 !important;
                color: #ffffff !important;
                font-weight: 700;
                box-shadow: 0 8px 18px rgba(32, 201, 151, 0.18);
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button.current,
            .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
                color: #ffffff !important;
            }
        </style>

        <div class="row g-4">
            <div class="col-12">
                <div class="branches-filter-card mb-4">
                    <div class="branches-filter-header d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                        <div>
                            <p class="branches-filter-title">Filters</p>
                            <div class="branches-filter-subtitle">Search branches by name, email, location, and status.</div>
                        </div>
                        <div class="branches-top-actions">
                            <a href="{{ route('branches.index') }}" class="btn btn-outline-secondary branches-top-action-btn">Clear Filters</a>
                            <a href="{{ route('branches.create') }}" class="btn btn-primary branches-top-action-btn">Add New Branch</a>
                        </div>
                    </div>
                    <div class="branches-filter-body">
                        <form method="GET" action="{{ route('branches.index') }}">
                            <div class="row g-3 branches-filter-grid">
                                <div class="col-12 col-md-6 col-xl-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ request('name') }}" placeholder="Search branch name">
                                </div>
                                <div class="col-12 col-md-6 col-xl-3">
                                    <label class="form-label">Email</label>
                                    <input type="text" name="email" class="form-control" value="{{ request('email') }}" placeholder="Search email">
                                </div>
                                <div class="col-12 col-md-6 col-xl-3">
                                    <label class="form-label">Country</label>
                                    <select name="country_id" class="form-select">
                                        <option value="">All Countries</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}" @selected(request('country_id') == $country->id)>{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 col-xl-3">
                                    <label class="form-label">City</label>
                                    <select name="city_id" class="form-select">
                                        <option value="">All Cities</option>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}" @selected(request('city_id') == $city->id)>{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 col-xl-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="">All Statuses</option>
                                        <option value="active" @selected(request('status') === 'active')>Active</option>
                                        <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 col-xl-3 d-flex align-items-end branches-filter-actions gap-2">
                                    <button type="submit" class="btn btn-primary w-100">Apply</button>
                                    <a href="{{ route('branches.index') }}" class="btn btn-outline-danger w-100">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="branches-table-card">
                    <div class="branches-table-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <div>
                            <p class="branches-table-title">Branches Table</p>
                            <div class="branches-table-subtitle">{{ $branches->count() }} record(s) matched your current filters.</div>
                        </div>
                        {{-- <a href="{{ route('branches.create') }}" class="btn btn-dark">Add New Branch</a> --}}
                    </div>
                    <div class="branches-table-wrapper table-responsive">
                        <table class="table align-middle datatable branches-table">
                            <thead>
                                <tr>
                                    <th scope="col">S.No</th>
                                    <th scope="col">Branch</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Location</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Percentage</th>
                                    <th scope="col" class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($branches as $i => $branch)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div>
                                                    <div class="branch-name">{{ $branch->name }}</div>
                                                    <div class="branch-meta">Code: {{ $branch->code }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $branch->email }}</td>
                                        <td>{{ $branch->phone }}</td>
                                        <td>
                                            <div>{{ $branch->city?->name ?? 'N/A' }}</div>
                                            <div class="branch-meta">{{ $branch->country?->name ?? 'N/A' }}</div>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill {{ $branch->status === 'active' ? 'bg-success' : 'bg-secondary' }} branch-status-pill">
                                                {{ ucfirst($branch->status ?? 'inactive') }}
                                            </span>
                                        </td>
                                        <td>{{ $branch->percentage ? $branch->percentage . '%' : '-' }}</td>
                                        <td class="text-end">
                                            <div class="d-inline-flex align-items-center justify-content-end gap-2">
                                                <a href="{{ route('branches.users', $branch->id) }}" class="branch-action-btn" title="View Users">
                                                    <i class="fa fa-users"></i>
                                                </a>
                                                <a href="{{ route('branches.edit', $branch->id) }}" class="branch-action-btn" title="Edit">
                                                    <i class="fa fa-pen"></i>
                                                </a>
                                                <button type="button" class="branch-action-btn text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $branch->id }}" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                @foreach ($branches as $branch)
                    <div class="modal fade" id="deleteModal{{ $branch->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Delete Confirmation</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete this branch?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <form action="{{ route('branches.destroy', $branch->id) }}" method="POST" class="mb-0">
                                        @csrf
                                        @method('DELETE')
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
