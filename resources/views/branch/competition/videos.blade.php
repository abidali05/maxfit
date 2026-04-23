@extends('layouts.branch.app')
@section('title', 'Videos')
@section('content')
    <div class="px-4 pt-4 container-fluid" style="min-height: 82.5vh">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="p-4 text-center rounded bg-light">
                    <div class="mb-4 d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">Videos</h6>
                    </div>
                    <div class="table-responsive">
                        <table id="competitions-table" class="table mb-0 align-middle text-start table-bordered datatable"
                            style="table-layout: auto;">
                            <thead>
                                <tr class="text-dark">
                                    <th>S.No</th>
                                    <th>Competition</th>
                                    <th>Competition Video</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($videos as $i => $video)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $video->competition->name ?? '-' }}</td>
                                        <td>
                                            @if($video->video_file)
                                                <a href="{{ $video->video_file }}" target="_blank">View Video</a>
                                            @else
                                                -
                                            @endif
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
