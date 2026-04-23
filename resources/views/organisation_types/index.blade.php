@extends('layouts.app')
@section('title', 'Organisation Types')
@section('content')
    <div class="container-fluid pt-4 px-4" style="min-height: 82.5vh">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Organisation Types</h6>
                        <a href="{{ route('organisation-types.create') }}" class="btn btn-primary">Add New Organisation
                            Type</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered  mb-0 datatable">
                            <thead>
                                <tr class="text-dark">
                                    <th scope="col">S.No</th>
                                    <th scope="col">Name</th>
                                    <th scope="col" class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orgTypes as $i => $organisation_type)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $organisation_type->name }}</td>
                                        <td class="d-flex align-items-end justify-content-end vertical-align-middle">

                                            <a href="{{ route('organisation-types.edit', $organisation_type->id) }}" class="me-2"> <i
                                                    class="fa fa-edit"></i> </a>
                                            <a href="#" class="" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $organisation_type->id }}"><i
                                                    class="fa fa-trash text-danger"></i></a>


                                        </td>
                                    </tr>

                                    {{-- confirm delete modal  --}}
                                    <div class="modal fade" id="deleteModal{{ $organisation_type->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="deleteModalLabel{{ $organisation_type->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="deleteModalLabel{{ $organisation_type->id }}">Delete
                                                        Confirmation</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this record?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <form
                                                        action="{{ route('organisation-types.destroy', $organisation_type->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="id"
                                                            value="{{ $organisation_type->id }}">
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
