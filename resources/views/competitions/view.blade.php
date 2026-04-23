@extends('layouts.app')
@section('title', 'Competitions')
@section('content')
    <div class="px-4 pt-4 container-fluid" style="min-height: 82.5vh">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="p-4 text-center rounded bg-light">
                    <div class="mb-4 d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">Competition Details</h6>
                        <form action="{{ route('competitions.generate-results', $id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to generate results and ranks?');">
                            @csrf
                            <button type="submit" class="btn btn-success">Generate Final Results</button>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table id="competitions-table" class="table mb-0 align-middle text-start table-bordered datatable"
                            style="table-layout: auto;">
                            <thead>
                                <tr class="text-dark">
                                    <th>S.No</th>
                                    <th>City</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th>Coach</th>
                                    <th>Image</th>
                                    <th>Description</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($competitionDetail as $detail)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        <td>{{ $detail->city }}</td>

                                        <td>
                                            @if ($detail->start_date)
                                                {{ \Carbon\Carbon::parse($detail->start_date)->format('d M Y') }}
                                            @endif

                                            @if ($detail->start_time)
                                                <br>
                                                <small>
                                                    {{ \Carbon\Carbon::parse($detail->start_time)->format('h:i A') }}
                                                </small>
                                            @endif

                                        </td>

                                        <td>
                                            @if ($detail->end_date)
                                                {{ \Carbon\Carbon::parse($detail->end_date)->format('d M Y') }}
                                            @endif

                                            @if ($detail->end_time)
                                                <br>
                                                <small>
                                                    {{ \Carbon\Carbon::parse($detail->end_time)->format('h:i A') }}
                                                </small>
                                            @endif

                                        </td>

                                        <td>{{ $detail->coach?->name ?? '-' }}</td>

                                        <td>
                                            @if ($detail->image)
                                                <img src="{{ asset('storage/' . $detail->image) }}" alt="Competition Image"
                                                    width="60" class="img-thumbnail">
                                            @else
                                                <small class="text-muted">No image</small>
                                            @endif
                                        </td>

                                        <td>
                                            {{ \Illuminate\Support\Str::limit(strip_tags($detail->description), 50) }}
                                        </td>

                                        <td class="text-end">
                                            <a href="{{ route('competition-users.index', $detail->id) }}" class="me-2"
                                                title="View Users">
                                                <i class="fa fa-users text-success"></i>
                                            </a>

                                            <a href="#" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $detail->id }}" title="Delete">
                                                <i class="fa fa-trash text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $detail->id }}" tabindex="-1"
                                        aria-labelledby="deleteModalLabel{{ $detail->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $detail->id }}">
                                                        Delete Confirmation
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>

                                                <div class="modal-body">
                                                    Are you sure you want to delete this competition detail?
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">
                                                        Cancel
                                                    </button>

                                                    <form action="{{ route('competition-details.destroy', $detail->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')

                                                        <input type="hidden" name="competition_detail_id"
                                                            value="{{ $detail->id }}">

                                                        <button type="submit" class="btn btn-danger">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">
                                            No competition details found.
                                        </td>
                                    </tr>
                                @endforelse
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
