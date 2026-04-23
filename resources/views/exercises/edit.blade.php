@extends('layouts.app')
@section('title', 'Edit Exercise')
@section('content')
    <div class="container-fluid pt-4 px-4" style="min-height: 82.5vh">
        <form action="{{ route('exercises.update', $exercise->id) }}" method="post" enctype="multipart/form-data" id="exerciseForm">
            @csrf
            @method('PUT')
            <div class="row g-4">
                <div class="col-lg-12">
                    <div class="bg-light rounded p-4">

                        {{-- Flash messages --}}
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" id="name" class="form-control" placeholder="Name" value="{{ old('name', $exercise->name) }}" name="name" required maxlength="255">
                                <div class="text-danger error-message" id="name-error"></div>
                            </div>

                            <div class="col-md-6">
                                <label for="exercise_category_id" class="form-label">Exercise Category</label>
                                <select name="exercise_category_id" id="exercise_category_id" class="form-select" required>
                                    <option value="" disabled>Select Category</option>
                                    @foreach ($exercises as $category)
                                        <option value="{{ $category->id }}" {{ old('exercise_category_id', $exercise->exercise_category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="text-danger error-message" id="exercise_category_id-error"></div>
                            </div>

                            <div class="col-md-6">
                                <label for="exercise_type" class="form-label">Exercise Type</label>
                                <select name="exercise_type" id="exercise_type" class="form-select" required>
                                    <option value="" disabled>Select Exercise Type</option>
                                    <option value="Per Second" {{ old('exercise_type', $exercise->exercise_type) == 'Per Second' ? 'selected' : '' }}>Per Second</option>
                                    <option value="Per Count" {{ old('exercise_type', $exercise->exercise_type) == 'Per Count' ? 'selected' : '' }}>Per Count</option>
                                </select>
                                <div class="text-danger error-message" id="exercise_type-error"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Genz</label>
                                <select name="genz" class="form-select" required>
                                    <option value="" disabled {{ old('genz', $exercise->genz) ? '' : 'selected' }}>Select Genz</option>
                                    <option value="both" {{ old('genz', $exercise->genz) == 'both' ? 'selected' : '' }}>Both</option>
                                    <option value="motherfits" {{ old('genz', $exercise->genz) == 'motherfits' ? 'selected' : '' }}>Mother Fit</option>
                                    <option value="fatherfits" {{ old('genz', $exercise->genz) == 'fatherfits' ? 'selected' : '' }}>Father Fit</option>
                                </select>
                                <div class="text-danger error-message" id="genz-error"></div>
                            </div>

                            <div class="col-md-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Optional description...">{{ old('description', $exercise->description) }}</textarea>
                                <div class="text-danger error-message" id="description-error"></div>
                            </div>

                            <div class="col-md-6">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" name="image" id="image" accept="image/*">
                                @if ($exercise->image)
                                    <img src="{{ asset('storage/' . $exercise->image) }}" class="img-thumbnail mt-2" width="150" alt="Current Image">
                                @endif
                                <div class="text-danger error-message" id="image-error"></div>
                            </div>

                            <div class="col-md-6">
                                <label for="video_file" class="form-label">YouTube Video Link</label>
                                <input type="url" class="form-control" name="youtube_link" value="{{ $exercise->youtube_link }}" id="video_file" required>
                                <div class="text-danger error-message" id="video_file-error"></div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button class="btn btn-primary" type="submit" id="submitExercise">Update changes</button>
                            <a href="{{ route('exercises.index') }}" class="btn btn-outline-danger">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('exerciseForm');
            const submitButton = document.getElementById('submitExercise');

            if (!form || !submitButton) {
                console.error('Form or submit button with ID "exerciseForm" or "submitExercise" not found.');
                return;
            }

            submitButton.addEventListener('click', function (e) {
                e.preventDefault();
                let isValid = true;
                const errorElements = document.getElementsByClassName('error-message');

                // Clear previous errors
                Array.from(errorElements).forEach(element => {
                    element.textContent = '';
                });

                console.log('Validation started...');

                // Validate name
                const name = document.querySelector('input[name="name"]');
                if (!name.value.trim()) {
                    document.getElementById('name-error').textContent = 'Name is required.';
                    isValid = false;
                } else if (name.value.length > 255) {
                    document.getElementById('name-error').textContent = 'Name must not exceed 255 characters.';
                    isValid = false;
                }

                // Validate exercise category
                const exerciseCategory = document.querySelector('select[name="exercise_category_id"]');
                if (!exerciseCategory.value) {
                    document.getElementById('exercise_category_id-error').textContent = 'Exercise category is required.';
                    isValid = false;
                }

                // Validate exercise type
                const exerciseType = document.querySelector('select[name="exercise_type"]');
                if (!exerciseType.value) {
                    document.getElementById('exercise_type-error').textContent = 'Exercise type is required.';
                    isValid = false;
                }

                // Validate genz
                const genz = document.querySelector('select[name="genz"]');
                if (!genz.value) {
                    document.getElementById('genz-error').textContent = 'Genz is required.';
                    isValid = false;
                }

                // Validate description (optional, no strict validation needed)
                const description = document.querySelector('textarea[name="description"]');
                // No specific validation for description as it is nullable

                // Validate image
                const image = document.querySelector('input[name="image"]');
                if (image.files.length > 0) {
                    const file = image.files[0];
                    const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg+xml'];
                    if (!validTypes.includes(file.type)) {
                        document.getElementById('image-error').textContent = 'Invalid image format. Use jpeg, png, jpg, gif, or svg.';
                        isValid = false;
                    } else if (file.size > 2048 * 1024) { // 2048 KB = 2MB
                        document.getElementById('image-error').textContent = 'Image size must not exceed 2MB.';
                        isValid = false;
                    }
                }


                if (isValid) {
                    form.submit();
                } else {
                    console.log('Form submission prevented due to validation errors.');
                }
            });

            // Simple URL validation function
            function isValidUrl(string) {
                try {
                    new URL(string);
                    return string.startsWith('http://') || string.startsWith('https://');
                } catch (_) {
                    return false;
                }
            }
        });
    </script>
@endsection
