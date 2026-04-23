@extends('layouts.branch.app')
@section('title', 'Competitions')
@section('content')
    <div class="px-4 pt-4 container-fluid" style="min-height: 82.5vh">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="p-4 text-center rounded bg-light">
                    <div class="mb-4 d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">Competitions</h6>
                        <a href="{{ route('branch.createCompetitions') }}" class="btn btn-primary">Add New</a>
                    </div>
                    <div class="table-responsive">
                        <table id="competitions-table" class="table mb-0 align-middle text-start table-bordered datatable"
                            style="table-layout: auto;">
                            <thead>
                                <tr class="text-dark">
                                    <th>S.No</th>
                                    <th>Name</th>
                                    <th>Age Group</th>
                                    <th>Genz</th>
                                    <th>Org Type</th>
                                    <th>Organization</th>
                                    <th>Country</th>
                                    <th>Time Allowed (mins)</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($competitions as $i => $competition)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $competition->name }}</td>
                                        <td>{{ $competition->age_group }}</td>
                                        <td>{{ $competition->genz }}</td>
                                        <td>{{ $competition->organisationType->name ?? 'N/A' }}</td>
                                        <td>{{ $competition->organisation->name ?? 'N/A' }}</td>
                                        <td>{{ $competition->country }}</td>
                                        <td>{{ $competition->time_allowed }} mins</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $competition->status == 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($competition->status) }}
                                            </span>
                                        </td>
                                        <td class="d-flex align-items-end justify-content-end">
                                            <a href="{{ route('branch.editCompetition', $competition->id) }}" class="me-2"
                                                title="Edit">
                                                <i class="fa fa-edit text-primary"></i>
                                            </a>
                                            <a href="{{ route('branch.showCompetition', $competition->id) }}" class="me-2"
                                                title="View">
                                                <i class="fa fa-eye text-info"></i>
                                            </a>
                                            <a href="#" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $competition->id }}" title="Delete">
                                                <i class="fa fa-trash text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $competition->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="deleteModalLabel{{ $competition->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $competition->id }}">
                                                        Delete Confirmation
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this competition?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('branch.deleteCompetition', $competition->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                </div>
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
