@extends('layouts.branch.app')
@section('title', 'Competitions')
@section('content')
    <div class="px-4 pt-4 container-fluid" style="min-height: 82.5vh">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="p-4 text-center rounded bg-light">
                    <div class="mb-4 d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">Competition Details</h6>
                    </div>
                    <form action="{{ route('branch.competition-result-update', $competitionUser->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @foreach ($exercises as $exercise)
                            <div class="mb-3">
                                <label for="score_{{ $exercise->id }}" class="form-label">{{ $exercise->name }}</label>
                                <input type="number" step="0.01" name="scores[{{ $exercise->id }}]" class="form-control"
                                    value="{{ $results[$exercise->id]->score ?? '' }}" required>
                            </div>
                        @endforeach

                        <button type="submit" class="btn btn-success">Save Scores</button>
                    </form>

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
