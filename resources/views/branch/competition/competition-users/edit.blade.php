@extends('layouts.branch.app')
@section('title', 'Edit Competition Detail')
@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-12">
                <div class="bg-light rounded p-4">
                    <h4>Edit Competition Detail</h4>
                    <form action="{{ route('branch.competition-users.update', $competitionDetail->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="competition_id" value="{{ $competitionDetail->competition->id }}">
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" class="form-control" value="{{ $competitionDetail->competition->name }}"
                                required readonly>
                        </div>
                        <div class="mb-3">
                            <label for="coach_id" class="form-label">Coach Name</label>
                            <select name="coach_id" id="coach_id" class="form-control" required>
                                <option value="">-- Select Coach --</option>
                                @foreach ($coaches as $coach)
                                    <option value="{{ $coach->id }}"
                                        {{ $competitionDetail->coach_id == $coach->id ? 'selected' : '' }}>
                                        {{ $coach->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('coach_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>City</label>
                            <input type="text" name="city" class="form-control" value="{{ $competitionDetail->city }}"
                                required>
                        </div>
                        <div class="mb-3">
                            <label>Start Date</label>
                            <input type="date" name="start_date" class="form-control"
                                value="{{ $competitionDetail->start_date }}" required>
                        </div>
                        <div class="mb-3">
                            <label>End Date</label>
                            <input type="date" name="end_date" class="form-control"
                                value="{{ $competitionDetail->end_date }}" required>
                        </div>
                        <div class="mb-3">
                            <label>Start Time</label>
                            <input type="time" name="start_time" class="form-control"
                                value="{{ $competitionDetail->start_time }}" required>
                        </div>
                        <div class="mb-3">
                            <label>End Time</label>
                            <input type="time" name="end_time" class="form-control"
                                value="{{ $competitionDetail->end_time }}" required>
                        </div>
                        <div class="mb-3">
                            <label>Image</label>
                            <input type="file" name="image" class="form-control">
                            @if ($competitionDetail->image)
                                <img src="{{ asset('storage/' . $competitionDetail->image) }}" width="80"
                                    class="mt-2">
                            @endif
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control">{{ $competitionDetail->description }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                    {{-- <form action="{{ route('competition-details.destroy', $competitionDetail->id) }}" method="POST" class="mt-3">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this competition detail?')">Delete</button>
                </form> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
