@extends('layouts.app')
@section('title', 'Coaches')
@section('content')
    <div class="container-fluid pt-4 px-4 coaches-page" style="min-height: 82.5vh">
        <style>
            .coach-bio {
                display: -webkit-box;
                -webkit-box-orient: vertical;
                -webkit-line-clamp: 2;
                overflow: hidden;
                max-width: 340px;
                color: #64748b;
                line-height: 1.45;
            }

            .coach-country,
            .coach-city {
                color: #64748b;
                font-size: 0.82rem;
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
            <div class="col-12">
                <div class="app-filter-card mb-4">
                    <div class="app-filter-header d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                        <div>
                            <p class="app-filter-title">Filters</p>
                            <div class="app-filter-subtitle">Search coaches by name, email, phone, and location.</div>
                        </div>
                        <div class="app-top-actions">
                            <a href="{{ route('coaches.index') }}" class="btn btn-outline-secondary app-top-action-btn">Clear Filters</a>
                            <a href="{{ route('coaches.create') }}" class="btn btn-primary app-top-action-btn">Add New Coach</a>
                        </div>
                    </div>
                    <div class="app-filter-body">
                        <form method="GET" action="{{ route('coaches.index') }}">
                            <div class="row g-3 app-filter-grid">
                                <div class="col-12 col-md-6 col-xl-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ request('name') }}" placeholder="Search name">
                                </div>
                                <div class="col-12 col-md-6 col-xl-3">
                                    <label class="form-label">Email</label>
                                    <input type="text" name="email" class="form-control" value="{{ request('email') }}" placeholder="Search email">
                                </div>
                                <div class="col-12 col-md-6 col-xl-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" class="form-control" value="{{ request('phone') }}" placeholder="Search phone">
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
                                <div class="col-12 col-md-6 col-xl-3 d-flex align-items-end app-filter-actions gap-2">
                                    <button type="submit" class="btn btn-primary w-100">Apply</button>
                                    <a href="{{ route('coaches.index') }}" class="btn btn-outline-danger w-100">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="app-table-card">
                    <div class="app-table-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <div>
                            <p class="app-table-title">Coaches Table</p>
                            <div class="app-table-subtitle">{{ $coaches->count() }} record(s) matched your current filters.</div>
                        </div>
                        {{-- <a href="{{ route('coaches.create') }}" class="btn btn-dark">Add New Coach</a> --}}
                    </div>
                    <div class="app-table-body table-responsive">
                        <table class="table align-middle datatable">
                            <thead>
                                <tr>
                                    <th scope="col">S.No</th>
                                    <th scope="col">Coach</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Location</th>
                                    {{-- <th scope="col">Image</th> --}}
                                    <th scope="col">Bio</th>
                                    <th scope="col" class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($coaches as $i => $coach)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="{{ $coach->image ? asset('storage/' . $coach->image) : asset('assets/images/user.jpg') }}" alt="{{ $coach->name }}" class="app-entity-avatar">
                                                <div>
                                                    <div class="app-entity-name">{{ $coach->name }}</div>
                                                    <div class="app-entity-meta">Coach ID: {{ $coach->id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $coach->email }}</td>
                                        <td>{{ $coach->phone }}</td>
                                        <td>
                                            <div>{{ $coach->country?->name ?? '-' }}</div>
                                            <div class="coach-city">{{ $coach->city?->name ?? '-' }}</div>
                                        </td>
                                        {{-- <td>
                                            @if ($coach->image)
                                                <img src="{{ asset('storage/' . $coach->image) }}" class="rounded-4 border" alt="{{ $coach->name }}" style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td> --}}
                                        <td>
                                            <div class="coach-bio" title="{{ $coach->bio }}">{{ $coach->bio ?: 'N/A' }}</div>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-inline-flex align-items-center justify-content-end gap-2">
                                                <a href="{{ route('coaches.edit', $coach->id) }}" class="app-action-btn" title="Edit">
                                                    <i class="fa fa-pen"></i>
                                                </a>
                                                <button type="button" class="app-action-btn text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $coach->id }}" title="Delete">
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

                @foreach ($coaches as $coach)
                    <div class="modal fade" id="deleteModal{{ $coach->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Delete Confirmation</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete this coach?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <form action="{{ route('coaches.destroy', $coach->id) }}" method="POST" class="mb-0">
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
