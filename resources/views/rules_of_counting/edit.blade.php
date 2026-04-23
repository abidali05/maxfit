@extends('layouts.app')
@section('title', 'Edit Rule of Counting')
@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-12">
                <div class="bg-light rounded p-4">
                    <h4>Edit Rule of Counting</h4>
                    <form action="{{ route('rulesof-counting.update', $rule->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label>Select Competition</label>
                            <select name="competition_id" id="competition_id"
                                class="form-select @error('competition_id') is-invalid @enderror" required>
                                <option value="">Select Competition</option>
                                @foreach ($competitions as $competition)
                                    <option value="{{ $competition->id }}"
                                        {{ $rule->competition_id == $competition->id ? 'selected' : '' }}>
                                        {{ $competition->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('competition_id')
                                <!-- Corrected error variable to match validation field -->
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label>Exercise Name</label>
                            <input type="text" name="custom_exercise_name" class="form-control"
                                value="{{ $rule->custom_exercise_name }}" required>
                        </div>
                        <div class="mb-3">
                            <label>Image</label>
                            <input type="file" name="image_file" class="form-control">
                            @if ($rule->image_file)
                                <img src="{{ asset('storage/' . $rule->image_file) }}" width="80" class="mt-2">
                            @endif
                        </div>
                        <div class="mb-3">
                            <label>Video</label>
                            <input type="file" name="video_file" class="form-control">
                            @if ($rule->video_file)
                                <a href="{{ asset('storage/' . $rule->video_file) }}" target="_blank">View Video</a>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control">{{ $rule->description }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('rulesof-counting.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
