@extends('layouts.app')
@section('title', 'Add Rule of Counting')
@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-12">
                <div class="bg-light rounded p-4">
                    <h4>Add Rule of Counting</h4>
                    <form action="{{ route('rulesof-counting.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label>Select Competition</label>
                            <select name="competition_id" id="competition_id"
                                class="form-select @error('competition_id') is-invalid @enderror" required>
                                <option value="">Select Competition</option>
                                @foreach ($competitions as $competition)
                                    <option value="{{ $competition->id }}">{{ $competition->name }}</option>
                                @endforeach
                            </select>

                            @error('competition')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label>Exercise Name</label>
                            <input type="text" name="custom_exercise_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Image</label>
                            <input type="file" name="image_file" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Video</label>
                            <input type="file" name="video_file" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('rulesof-counting.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
