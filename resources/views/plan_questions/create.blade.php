@extends('layouts.app')
@section('title', 'Create Medical Assessment Question')
@section('content')
    <div class="px-4 pt-4 container-fluid" style="min-height: 82.5vh">
        <form action="{{ route('plan-questions.store') }}" method="POST">
            @csrf
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
                                <label class="form-label">Question</label>
                                <input type="text" class="form-control" name="question" required>
                                @error('question')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Type</label>
                                <select class="form-select" name="type" id="questionType" required>
                                    <option value="" disabled selected>Select Input Type</option>
                                    <option value="input">Input</option>
                                    <option value="textarea">Textarea</option>
                                    <option value="selection">Dropdown</option>
                                </select>
                                @error('type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mt-4 col-md-6 d-flex align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_required" id="is_required"
                                        value="1">
                                    <label class="form-check-label" for="is_required">Required?</label>
                                </div>
                            </div>

                            <div class="col-md-12" id="answerOptionsField" style="display: none;">
                                <label class="form-label">Answer Options <small>(comma separated)</small></label>
                                <input type="text" class="form-control" name="answer_options"
                                    placeholder="e.g. Option1, Option2">
                                @error('answer_options')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <button class="btn btn-primary" type="submit">Save Question</button>
                            <a href="{{ route('plan-questions.index') }}"
                                class="btn btn-outline-danger">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script src="{{ asset('assets/customjs/medical_assessment_questions/create.js') }}"></script>
@endsection
