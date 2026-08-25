@extends('layouts.app')
@section('title', 'Create Exercise Group')
@section('content')
    <div class="container-fluid pt-4 px-4" style="min-height: 82.5vh">
        <form action="{{ route('exercise-groups.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-4 justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="bg-light rounded p-4 shadow-sm">
                        <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-2">
                            <h5 class="mb-0 fw-bold"><i class="fa fa-plus-circle text-primary me-2"></i>Create Main Exercise Group</h5>
                            <a href="{{ route('exercise-groups.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fa fa-arrow-left me-1"></i>Back</a>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Group / Sport Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. Cricket, Football, Cardio Core" required>
                            @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="sub_title" class="form-label fw-bold">Subtitle / Tagline</label>
                            <input type="text" name="sub_title" id="sub_title" class="form-control" value="{{ old('sub_title', 'Popular by Sport') }}" placeholder="e.g. Popular by Sport, Sport-Specific Training">
                            @error('sub_title')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label fw-bold">Group Banner / Image</label>
                            <input type="file" name="image" id="image" class="form-control" accept="image/*">
                            <small class="text-muted">Displays on the mobile top category card.</small>
                            @error('image')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label fw-bold">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="alert alert-info py-2 px-3 small d-flex align-items-center gap-2 mb-4">
                            <i class="fa fa-info-circle fa-lg"></i>
                            <span>After saving this main group, you will add Sub-Groups (e.g. <em>Batsman, Bowler</em>) and assign exercises.</span>
                        </div>

                        <div class="pt-2 border-top d-grid">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save me-2"></i>Save Group & Add Sub-Groups</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
