@extends('layouts.app')
@section('title', 'Edit ' . $exerciseGroup->name)
@section('content')
    <div class="container-fluid pt-4 px-4" style="min-height: 82.5vh">
        <form action="{{ route('exercise-groups.update', $exerciseGroup->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row g-4 justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="bg-light rounded p-4 shadow-sm">
                        <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-2">
                            <div>
                                <h5 class="mb-0 fw-bold"><i class="fa fa-edit text-primary me-2"></i>Edit Group: {{ $exerciseGroup->name }}</h5>
                            </div>
                            <div class="d-flex gap-1">
                                <a href="{{ route('exercise-groups.show', $exerciseGroup->id) }}" class="btn btn-outline-info btn-sm"><i class="fa fa-sitemap me-1"></i>Sub-Groups</a>
                                <a href="{{ route('exercise-groups.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fa fa-arrow-left me-1"></i>Back</a>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Group / Sport Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $exerciseGroup->name) }}" placeholder="e.g. Cricket, Football" required>
                            @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="sub_title" class="form-label fw-bold">Subtitle / Tagline</label>
                            <input type="text" name="sub_title" id="sub_title" class="form-control" value="{{ old('sub_title', $exerciseGroup->sub_title) }}" placeholder="e.g. Popular by Sport">
                            @error('sub_title')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label fw-bold">Group Banner / Image</label>
                            @if($exerciseGroup->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $exerciseGroup->image) }}" alt="" class="rounded img-thumbnail" style="max-height: 80px;">
                                </div>
                            @endif
                            <input type="file" name="image" id="image" class="form-control" accept="image/*">
                            <small class="text-muted">Upload new image to replace current image.</small>
                            @error('image')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label fw-bold">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="active" {{ old('status', $exerciseGroup->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $exerciseGroup->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="pt-2 border-top d-flex gap-2 justify-content-between">
                            <a href="{{ route('exercise-groups.show', $exerciseGroup->id) }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fa fa-sitemap me-1"></i>Manage Sub-Groups ({{ $exerciseGroup->subGroups()->count() }})
                            </a>
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save me-1"></i>Update Group</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
