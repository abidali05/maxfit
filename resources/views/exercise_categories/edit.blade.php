@extends('layouts.app')
@section('title', 'Edit Exercise Category')

@section('content')
    <div class="container-fluid pt-4 px-4" style="min-height: 82.5vh">
        <form action="{{ route('exercise-categories.update', $exercise_category->id) }}" method="post" enctype="multipart/form-data">
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
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" required
                                    value="{{ old('name', $exercise_category->name) }}">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tag</label>
                                <select name="tag" class="form-select @error('tag') is-invalid @enderror">
                                    <option value="">Select Tag</option>
                                    <option value="popular" {{ old('tag', $exercise_category->tag) == 'popular' ? 'selected' : '' }}>Popular</option>
                                    <option value="new_arrival" {{ old('tag', $exercise_category->tag) == 'new_arrival' ? 'selected' : '' }}>New Arrival</option>
                                    <option value="limited_edition" {{ old('tag', $exercise_category->tag) == 'limited_edition' ? 'selected' : '' }}>Limited Edition</option>
                                    <option value="featured" {{ old('tag', $exercise_category->tag) == 'featured' ? 'selected' : '' }}>Featured</option>
                                </select>
                                @error('tag')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Overall Time</label>
                                <input type="text" class="form-control" name="overall_time"
                                    value="{{ old('overall_time', $exercise_category->overall_time) }}">
                                @error('overall_time')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Over Kcal</label>
                                <input type="text" class="form-control" name="over_kcal"
                                    value="{{ old('over_kcal', $exercise_category->over_kcal) }}">
                                @error('over_kcal')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Exercise Level</label>
                                <input type="text" class="form-control" name="exerice_lvl"
                                    value="{{ old('exerice_lvl', $exercise_category->exerice_lvl) }}">
                                @error('exerice_lvl')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="4">{{ old('description', $exercise_category->description) }}</textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Image</label>
                                <input type="file" class="form-control" name="image" accept="image/*">
                                @if ($exercise_category->image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $exercise_category->image) }}" alt="Current Image" width="120">
                                    </div>
                                @endif
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <button class="btn btn-primary" type="submit">Save changes</button>
                            <a href="{{ route('exercise-categories.index') }}" class="btn btn-outline-danger">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
