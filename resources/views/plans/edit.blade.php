@extends('layouts.app')
@section('title', 'Edit Plan')
@section('content')
    <div class="px-4 pt-4 container-fluid" style="min-height: 82.5vh">
        <form action="{{ route('plans.update', $plan->id) }}" method="POST">
            @csrf
            @method('PUT')
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
                                <label class="form-label">Plan Name</label>
                                <input type="text" class="form-control" name="name"
                                    value="{{ old('name', $plan->name) }}" required>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Price (PKR)</label>
                                <input type="number" class="form-control" name="price" step="0.01" min="0"
                                    value="{{ old('price', $plan->price) }}" required>
                                @error('price')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="3">{{ old('description', $plan->description) }}</textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            @php
                                $features = is_array($plan->features)
                                    ? $plan->features
                                    : json_decode($plan->features, true);

                                $featuresString = is_array($features) ? implode(', ', $features) : '';
                            @endphp

                            <div class="col-md-12">
                                <label class="form-label">Features <small>(comma separated)</small></label>
                                <input type="text" class="form-control" name="features"
                                    value="{{ old('features', $featuresString) }}" placeholder="e.g. Feature1, Feature2">
                                @error('features')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Duration</label>
                                <select class="form-select" name="duration" required>
                                    <option value="monthly"
                                        {{ old('duration', $plan->duration) == 'monthly' ? 'selected' : '' }}>Monthly
                                    </option>
                                    <option value="quarterly"
                                        {{ old('duration', $plan->duration) == 'quarterly' ? 'selected' : '' }}>Quarterly
                                    </option>
                                    <option value="yearly"
                                        {{ old('duration', $plan->duration) == 'yearly' ? 'selected' : '' }}>Yearly
                                    </option>
                                </select>
                                @error('duration')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status" required>
                                    <option value="active"
                                        {{ old('status', $plan->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive"
                                        {{ old('status', $plan->status) == 'inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                                @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <div class="mt-4">
                            <button class="btn btn-primary" type="submit">Update Plan</button>
                            <a href="{{ route('plans.index') }}" class="btn btn-outline-danger">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
