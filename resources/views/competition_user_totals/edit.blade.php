@extends('layouts.app')
@section('title', 'Competitions')
@section('content')
    <div class="px-4 pt-4 container-fluid" style="min-height: 82.5vh">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="p-4 text-center rounded bg-light">
                    <div class="card border">
                        <div class="card-header">
                            Update Rank and Status
                            <br>
                            <small class="text-muted">
                                for <strong>{{ $competitionUser->user->name }}</strong>
                                in <strong>{{ $competitionUser->competitionDetail->title ?? 'Competition' }}</strong>
                            </small>
                        </div>

                        <div class="card-body">
                            <form method="POST"
                                action="{{ route('competition-user-totals.update', $competitionUser->id) }}">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="Passed"
                                            {{ $competitionUser->total->status == 'Passed' ? 'selected' : '' }}>Passed
                                        </option>
                                        <option value="Failed"
                                            {{ $competitionUser->total->status == 'Failed' ? 'selected' : '' }}>Failed
                                        </option>
                                        <option value="Pending"
                                            {{ $competitionUser->total->status == 'Pending' ? 'selected' : '' }}>Pending
                                        </option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="rank" class="form-label">Rank</label>
                                    <input type="number" name="rank" id="rank" class="form-control"
                                        value="{{ $competitionUser->total->rank }}">
                                </div>

                                <div class="d-flex justify-content-center text-center">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        #competitions-table th,
        #competitions-table td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
        }
    </style>
@endpush

@push('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#competitions-table').DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: 10,
                order: [],
                language: {
                    search: "",
                    searchPlaceholder: "Search competitions..."
                }
            });
        });
    </script>
@endpush
