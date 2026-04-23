@extends('layouts.app')
@section('title', 'Competitions')
@section('content')
    <div class="px-4 pt-4 container-fluid" style="min-height: 82.5vh">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="p-4 text-center rounded bg-light">
                    
                    <div class="table-responsive">
                        <table id="competitions-table" class="table mb-0 align-middle text-start table-bordered datatable"
                            style="table-layout: auto;">
<thead>
    <tr class="text-dark">
        <th>S.No</th>
        <th>Status</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Challenger</th>
        <th>Challenge To</th>
        <th>Total Exercises</th>
        <th class="text-end">Action</th>
    </tr>
</thead>

<tbody>
@foreach ($challenges as $index => $challenge)
    <tr>
        {{-- Serial No --}}
        <td>{{ $index + 1 }}</td>

        {{-- Status --}}
        <td>{{ ucfirst($challenge->status) }}</td>

        {{-- Dates --}}
        <td>{{ $challenge->start_date }}</td>
        <td>{{ $challenge->end_date }}</td>

        {{-- Times --}}
        <td>{{ $challenge->start_time ? date('h:i A', strtotime($challenge->start_time)) : 'N/A' }}</td>
        <td>{{ $challenge->end_time ? date('h:i A', strtotime($challenge->end_time)) : 'N/A' }}</td>

        {{-- Users --}}
        <td>{{ $challenge->challenger->name ?? 'N/A' }}</td>
        <td>{{ $challenge->challengeTo->name ?? 'N/A' }}</td>

        {{-- Exercise Count --}}
        <td>{{ $challenge->challengeExercises->count() }}</td>

        {{-- Action Buttons --}}
        <td class="text-end">
            <a href="{{route('challenge.show',$challenge->id)}}" class="btn btn-sm btn-info">View</a>
            
            </form>
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
