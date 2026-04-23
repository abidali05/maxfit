@extends('layouts.app')
@section('title', 'Update Medical Assessment Question')
@section('content')
    <div class="px-4 pt-4 container-fluid" style="min-height: 82.5vh">
        <form action="{{ route('medical-assessment-questions.update', $medical_assessment_question->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-4">
                <div class="col-lg-12">
                    <div class="p-4 rounded bg-light">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Assessment Type</label>
                                <select class="form-select" name="assessment_type" required>
                                    <option value="" disabled selected>Select Type</option>
                                    <option value="Assessment"
                                        {{ $medical_assessment_question->assessment_type == 'Assessment' ? 'selected' : '' }}>
                                        Assessment</option>
                                    <option value="Medical"
                                        {{ $medical_assessment_question->assessment_type == 'Medical' ? 'selected' : '' }}>
                                        Medical (Optional)</option>
                                </select>
                                @error('assessment_type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Question</label>
                                <input type="text" class="form-control" name="question"
                                    value="{{ $medical_assessment_question->question }}" required>
                                @error('question')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Type</label>
                                <select class="form-select" name="type" id="questionType" required>
                                    <option value="" disabled selected>Select Input Type</option>
                                    <option value="input"
                                        {{ $medical_assessment_question->type === 'input' ? 'selected' : '' }}>Input
                                    </option>
                                    <option value="textarea"
                                        {{ $medical_assessment_question->type === 'textarea' ? 'selected' : '' }}>Textarea
                                    </option>
                                    <option value="selection"
                                        {{ $medical_assessment_question->type === 'selection' ? 'selected' : '' }}>Dropdown
                                    </option>
                                </select>
                                @error('type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mt-4 col-md-6 d-flex align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_required" id="is_required"
                                        value="1" {{ $medical_assessment_question->is_required ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_required">Required?</label>
                                </div>
                            </div>

                            <div class="col-md-12" id="answerOptionsField" style="display: none;">
                                <label class="form-label">Answer Options <small>(comma separated)</small></label>
                                <input type="text" class="form-control" name="answer_options"
                                    value="{{ $medical_assessment_question->answer_options }}"
                                    placeholder="e.g. Option1, Option2">
                                @error('answer_options')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <button class="btn btn-primary" type="submit">Update Question</button>
                            <a href="{{ route('medical-assessment-questions.index') }}"
                                class="btn btn-outline-danger">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script src="{{ asset('assets/customjs/medical_assessment_questions/edit.js') }}"></script>
@endsection
