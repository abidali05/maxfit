@extends('layouts.app')
@section('title', 'App Version')
@section('content')
    <div class="px-4 pt-4 container-fluid" style="min-height: 82.5vh">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="p-4 rounded bg-light">
                    <div class="mb-4 d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">App Version Settings</h6>
                    </div>

                    <form action="{{ route('app-version.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="android_version" class="form-label">Android Version</label>
                                    <input type="text" class="form-control @error('android_version') is-invalid @enderror"
                                        id="android_version" name="android_version"
                                        value="{{ old('android_version', $appVersion->android_version) }}" required>
                                    @error('android_version')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="playstore_link" class="form-label">Play Store Link</label>
                                    <input type="text" class="form-control @error('playstore_link') is-invalid @enderror"
                                        id="playstore_link" name="playstore_link"
                                        value="{{ old('playstore_link', $appVersion->playstore_link) }}" required>
                                    @error('playstore_link')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ios_version" class="form-label">iOS Version</label>
                                    <input type="text" class="form-control @error('ios_version') is-invalid @enderror"
                                        id="ios_version" name="ios_version"
                                        value="{{ old('ios_version', $appVersion->ios_version) }}" required>
                                    @error('ios_version')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="app_store_link" class="form-label">App Store Link</label>
                                    <input type="text" class="form-control @error('app_store_link') is-invalid @enderror"
                                        id="app_store_link" name="app_store_link"
                                        value="{{ old('app_store_link', $appVersion->app_store_link) }}" required>
                                    @error('app_store_link')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Save Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

