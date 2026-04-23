@extends('layouts.app')
@section('title', 'Exercise Categories')
@section('content')
    <div class="container-fluid pt-4 px-4" style="min-height: 82.5vh">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Exercise Categories</h6>
                        <a href="{{ route('exercise-categories.create') }}" class="btn btn-primary">Add New
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered  mb-0 datatable">
                            <thead>
                                <tr class="text-dark">
                                    <th scope="col">S.No</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Tag</th>
                                    <th scope="col" class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($exercise_categories as $i => $exercise_category)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $exercise_category->name }}</td>
                                        <td>
                                            @php
                                                $badgeColors = [
                                                    'popular' => 'primary',
                                                    'new_arrival' => 'success',
                                                    'limited_edition' => 'warning',
                                                    'featured' => 'info',
                                                ];
                                                $tag = $exercise_category->tag;
                                                $color = $badgeColors[$tag] ?? 'secondary';
                                            @endphp

                                            <span class="badge bg-{{ $color }} text-capitalize">
                                                {{ str_replace('_', ' ', $tag) }}
                                            </span>
                                        </td>

                                        <td class="d-flex align-items-end justify-content-end vertical-align-middle">
                                            <a href="{{ route('exercise-categories.edit', $exercise_category->id) }}"
                                                class="me-2"> <i class="fa fa-edit"></i> </a>
                                            <a href="#" class="" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $exercise_category->id }}"><i
                                                    class="fa fa-trash text-danger"></i></a>
                                        </td>
                                    </tr>

                                    {{-- confirm delete modal  --}}
                                    <div class="modal fade" id="deleteModal{{ $exercise_category->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="deleteModalLabel{{ $exercise_category->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="deleteModalLabel{{ $exercise_category->id }}">Delete
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
                                                        action="{{ route('exercise-categories.destroy', $exercise_category->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="id"
                                                            value="{{ $exercise_category->id }}">
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
