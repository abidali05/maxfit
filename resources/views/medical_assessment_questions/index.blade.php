@extends('layouts.app')
@section('title', 'Medical Assessment Questions')
@section('content')
    <div class="px-4 pt-4 container-fluid" style="min-height: 82.5vh">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="p-4 text-center rounded bg-light">
                    <div class="mb-4 d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">Medical Assessment Questions</h6>
                        <a href="{{ route('medical-assessment-questions.create') }}" class="btn btn-primary">Add New
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle text-start table-bordered datatable">
                            <thead>
                                <tr class="text-dark">
                                    <th scope="col">S.No</th>
                                    <th scope="col">Question</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Is Required</th>
                                    <th scope="col" class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($medical_assessment_questions as $i => $medical_assessment_question)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $medical_assessment_question->question }}</td>
                                        <td>{{ $medical_assessment_question->type }}</td>
                                        <td>{{ $medical_assessment_question->is_required ? 'Yes' : 'No' }}</td>
                                        <td class="d-flex align-items-end justify-content-end vertical-align-middle">

                                            <a href="{{ route('medical-assessment-questions.edit', $medical_assessment_question->id) }}"
                                                class="me-2"> <i class="fa fa-edit"></i> </a>
                                            <a href="#" class="" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $medical_assessment_question->id }}"><i
                                                    class="fa fa-trash text-danger"></i></a>
                                        </td>
                                    </tr>

                                    {{-- confirm delete modal  --}}
                                    <div class="modal fade" id="deleteModal{{ $medical_assessment_question->id }}"
                                        tabindex="-1" role="dialog"
                                        aria-labelledby="deleteModalLabel{{ $medical_assessment_question->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="deleteModalLabel{{ $medical_assessment_question->id }}">Delete
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
                                                        action="{{ route('medical-assessment-questions.destroy', $medical_assessment_question->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="id"
                                                            value="{{ $medical_assessment_question->id }}">
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
