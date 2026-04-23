@extends('layouts.app')
@section('title', 'Plans')
@section('content')
    <div class="px-4 pt-4 container-fluid" style="min-height: 82.5vh">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="p-4 text-center rounded bg-light">
                    <div class="mb-4 d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">Plans</h6>
                        <a href="{{ route('plans.create') }}" class="btn btn-primary">Add New</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle text-start table-bordered datatable">
                            <thead>
                                <tr class="text-dark">
                                    <th scope="col">S.No</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Duration</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($plans as $i => $plan)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $plan->name }}</td>
                                        <td>{{ number_format($plan->price, 2) }} PKR</td>
                                        <td>{{ ucfirst($plan->duration) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $plan->status == 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($plan->status) }}
                                            </span>
                                        </td>
                                        <td class="d-flex align-items-end justify-content-end vertical-align-middle">
                                            <a href="{{ route('plans.edit', $plan->id) }}" class="me-2">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="#" data-bs-toggle="modal"
                                               data-bs-target="#deleteModal{{ $plan->id }}">
                                                <i class="fa fa-trash text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $plan->id }}" tabindex="-1"
                                         role="dialog" aria-labelledby="deleteModalLabel{{ $plan->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $plan->id }}">
                                                        Delete Confirmation
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this plan?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('plans.destroy', $plan->id) }}" method="POST">
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
