@extends('layouts.app')
@section('title', 'Venues')
@section('content')
    <style>
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
    <div class="container-fluid pt-4 px-4 venues-page" style="min-height: 82.5vh">
        <div class="row g-4">
            <div class="col-12">
                <div class="app-filter-card mb-4">
                    <div
                        class="app-filter-header d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                        <div>
                            <p class="app-filter-title">Filters</p>
                            <div class="app-filter-subtitle">Search venues by name and city.</div>
                        </div>
                        <div class="app-top-actions">
                            <a href="{{ route('venues.index') }}" class="btn btn-outline-secondary app-top-action-btn">Clear
                                Filters</a>
                            <a href="{{ route('venues.create') }}" class="btn btn-primary app-top-action-btn">Add New
                                Venue</a>
                        </div>
                    </div>
                    <div class="app-filter-body">
                        <form method="GET" action="{{ route('venues.index') }}">
                            <div class="row g-3 app-filter-grid">
                                <div class="col-12 col-md-6 col-xl-4">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ request('name') }}"
                                        placeholder="Search venue name">
                                </div>
                                <div class="col-12 col-md-6 col-xl-4">
                                    <label class="form-label">City</label>
                                    <select name="city_id" class="form-select">
                                        <option value="">All Cities</option>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}" @selected(request('city_id') == $city->id)>
                                                {{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 col-xl-4 d-flex align-items-end app-filter-actions gap-2">
                                    <button type="submit" class="btn btn-primary w-100">Apply</button>
                                    <a href="{{ route('venues.index') }}" class="btn btn-outline-danger w-100">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="app-table-card">
                    <div
                        class="app-table-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <div>
                            <p class="app-table-title">Venues Table</p>
                            <div class="app-table-subtitle">{{ $venues->count() }} record(s) matched your current filters.
                            </div>
                        </div>
                    </div>
                    <div class="app-table-body table-responsive">
                        <table class="table align-middle datatable">
                            <thead>
                                <tr>
                                    <th scope="col">S.No</th>
                                    <th scope="col">Venue</th>
                                    <th scope="col">City</th>
                                    <th scope="col">Created At</th>
                                    <th scope="col" class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($venues as $i => $venue)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div
                                                    class="app-entity-avatar bg-light d-flex align-items-center justify-content-center text-primary">
                                                    <i class="fa fa-map-marker-alt"></i>
                                                </div>
                                                <div>
                                                    <div class="app-entity-name">{{ $venue->name }}</div>
                                                    <div class="app-entity-meta">Venue ID: {{ $venue->id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>{{ $venue->city?->name ?? 'N/A' }}</div>
                                        </td>
                                        <td>{{ optional($venue->created_at)->format('d M Y') }}</td>
                                        <td class="text-end">
                                            <div class="d-inline-flex align-items-center justify-content-end gap-2">
                                                <a href="{{ route('venues.edit', $venue) }}" class="app-action-btn"
                                                    title="Edit">
                                                    <i class="fa fa-pen"></i>
                                                </a>
                                                <button type="button" class="app-action-btn text-danger"
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal{{ $venue->id }}"
                                                    title="Delete">
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

                @foreach ($venues as $venue)
                    <div class="modal fade" id="deleteModal{{ $venue->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Delete Confirmation</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete this venue?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <form action="{{ route('venues.destroy', $venue) }}" method="POST" class="mb-0">
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
