@extends('layouts.coach.app')
@section('title', 'Competitions')
@section('content')
    <div class="px-4 pt-4 container-fluid" style="min-height: 82.5vh">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="p-4 text-center rounded bg-light">
                    <div class="mb-4 d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">Competition Details</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>City</th>
                                    <th>Score</th>
                                    <th>Status</th>
                                    <th>Rank</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($competition->competitionUsers as $i => $cu)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $cu->user->name }}</td>
                                        <td>{{ $competition->city }}</td>
                                        <td>{{ $cu->total->total_score ?? '-' }}</td>
                                        <td>{{ $cu->status ?? 'Pending' }}</td>
                                        <td>{{ $cu->total->rank ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('coach.competition-users-update', $cu->id) }}"
                                                class="btn btn-sm btn-primary">Update</a>
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
