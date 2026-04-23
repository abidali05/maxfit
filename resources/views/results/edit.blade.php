@extends('layouts.app')
@section('title', 'Edit Competitions')
@section('content')
    <div class="px-4 pt-4 container-fluid" style="min-height: 82.5vh">
        <form action="{{ route('results.update', $result->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row g-4">
                <div class="col-lg-12">
                    <div class="p-4 rounded bg-light">

                        {{-- Alerts --}}
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="row g-3">
                            {{-- User Name (readonly or dropdown) --}}
                            <div class="col-md-6">
                                <label class="form-label">User Name</label>
                                <input type="text" class="form-control" value="{{ $result->user->name ?? '-' }}"
                                    disabled>
                            </div>

                            {{-- Competition Name (readonly) --}}
                            <div class="col-md-6">
                                <label class="form-label">Competition</label>
                                <input type="text" class="form-control" value="{{ $result->competition->name ?? '-' }}"
                                    disabled>
                            </div>

                            {{-- Percentage --}}
                            <div class="col-md-6">
                                <label class="form-label">Percentage</label>
                                <input type="number" step="0.01" name="percentage" class="form-control"
                                    value="{{ old('percentage', $result->competitionResult->percentage ?? '') }}">
                                @error('percentage')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Pushups Per Min --}}
                            <div class="col-md-6">
                                <label class="form-label">PushUps Per Min</label>
                                <input type="number" name="pushups_per_min" class="form-control"
                                    value="{{ old('pushups_per_min', $result->competitionResult->pushups_per_min ?? '') }}">
                                @error('pushups_per_min')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Position --}}
                            <div class="col-md-6">
                                <label class="form-label">Position</label>
                                <input type="number" name="position" class="form-control"
                                    value="{{ old('position', $result->competitionResult->position ?? '') }}">
                                @error('position')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <button class="btn btn-primary" type="submit">Update Result</button>
                                <a href="{{ route('results.index') }}" class="btn btn-outline-danger">Back</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
@endsection
