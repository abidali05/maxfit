@extends('layouts.app')

@section('title', 'Create Exercise Category')

@section('content')
    <div class="container-fluid pt-4 px-4" style="min-height: 82.5vh">
        <form action="{{ route('exercise-categories.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="row g-4">
                <div class="col-lg-12">
                    <div class="bg-light rounded p-4">
                        <!-- Flash messages -->
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
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
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" placeholder="Name" value="{{ old('name') }}"
                                    name="name" required>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="tag" class="form-label">Tag</label>
                                <select name="tag" id="tag"
                                    class="form-select @error('tag') is-invalid @enderror">
                                    <option value="">Select Tag</option>
                                    <option value="popular" {{ old('tag') == 'popular' ? 'selected' : '' }}>Popular</option>
                                    <option value="new_arrival" {{ old('tag') == 'new_arrival' ? 'selected' : '' }}>New
                                        Arrival</option>
                                    <option value="limited_edition" {{ old('tag') == 'limited_edition' ? 'selected' : '' }}>
                                        Limited Edition</option>
                                    <option value="featured" {{ old('tag') == 'featured' ? 'selected' : '' }}>Featured
                                    </option>
                                </select>
                                @error('tag')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Overall Time (e.g., 15 mins)</label>
                                <input type="text" class="form-control" name="overall_time" placeholder="Overall Time"
                                    value="{{ old('overall_time') }}">
                                @error('overall_time')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Calories Burned (Kcal)</label>
                                <input type="text" class="form-control" name="over_kcal" placeholder="Calories burned"
                                    value="{{ old('over_kcal') }}">
                                @error('over_kcal')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Exercise Level</label>
                                <select name="exerice_lvl" class="form-select @error('exerice_lvl') is-invalid @enderror">
                                    <option value="">Select Level</option>
                                    <option value="beginner" {{ old('exerice_lvl') == 'beginner' ? 'selected' : '' }}>
                                        Beginner</option>
                                    <option value="intermediate"
                                        {{ old('exerice_lvl') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                    <option value="advanced" {{ old('exerice_lvl') == 'advanced' ? 'selected' : '' }}>
                                        Advanced</option>
                                </select>
                                @error('exerice_lvl')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="col-md-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" placeholder="Enter description" rows="4">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Image</label>
                                <input type="file" class="form-control" name="image" id="image" accept="image/*">
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
