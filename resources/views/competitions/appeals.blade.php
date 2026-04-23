@extends('layouts.app')
@section('title', 'Appeals')
@section('content')
    <div class="px-4 pt-4 container-fluid" style="min-height: 82.5vh">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="p-4 text-center rounded bg-light">
                    <div class="mb-4 d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">Appeals</h6>
                    </div>
                    <div class="table-responsive">
                        <table id="competitions-table" class="table mb-0 align-middle text-start table-bordered datatable"
                            style="table-layout: auto;">
                            <thead>
                                <tr class="text-dark">
                                    <th>S.No</th>
                                    <th>Competition</th>
                                    <th>Competition Video</th>
                                    <th>Competition User</th>
                                    <th>Apeal</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($appeals as $i => $appeal)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $appeal->competitionVideo->competition->name ?? '-' }}</td>
                                        <td>
                                            @if($appeal->competitionVideo)
                                                <a href="{{ asset('storage/' . $appeal->competitionVideo->video_file) }}" target="_blank">View Video</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $appeal->user->name ?? '-' }}</td>
                                        <td>{{ $appeal->appeal_text }}</td>
                                        <td>
                                            <span class="badge bg-{{ $appeal->status == 'pending' ? 'warning' : ($appeal->status == 'approved' ? 'success' : 'danger') }}">
                                                {{ ucfirst($appeal->status) }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <!-- Delete Button -->
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $appeal->id }}" title="Delete">
                                                <i class="fa fa-trash text-danger"></i>
                                            </a>
                                            <!-- Update Status Button -->
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#statusModal{{ $appeal->id }}" title="Update Status" class="ms-2">
                                                <i class="fa fa-edit text-primary"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $appeal->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="deleteModalLabel{{ $appeal->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $appeal->id }}">
                                                        Delete Confirmation
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this appeal?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('competitions.destroyAppeal', $appeal->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Status Update Modal -->
                                    <div class="modal fade" id="statusModal{{ $appeal->id }}" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel{{ $appeal->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="statusModalLabel{{ $appeal->id }}">Update Appeal Status</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('competitions.updateAppealStatus', $appeal->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="statusSelect{{ $appeal->id }}" class="form-label">Status</label>
                                                            <select class="form-select" id="statusSelect{{ $appeal->id }}" name="status" required>
                                                                <option value="pending" {{ $appeal->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                                <option value="approved" {{ $appeal->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                                                <option value="rejected" {{ $appeal->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
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
