@extends('layouts.app')
@section('title', 'Profile')
@section('content')
    <div class="container-fluid pt-4 px-4" style="min-height: 82.5vh">
        @php
            if (Auth::guard('coach')->check()) {
                $actionRoute = route('coach.profile.update');
            } elseif (Auth::guard('branch')->check()) {
                $actionRoute = route('branch.profile.update');
            } else {
                $actionRoute = route('profile.update');
            }
        @endphp

        <form action="{{ $actionRoute }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="row g-4">

                <div class="col-lg-3">
                    <div class="bg-light rounded p-3">
                        <div class="col-md-6 text-center m-auto">
                            <img id="profile-preview" src="{{ $user->image }}" class="rounded-circle mb-2" width="100"
                                height="100" alt="Profile">
                            <div>
                                <button type="button" class="btn btn-outline-primary btn-sm"
                                    onclick="document.getElementById('image').click()">Change</button>
                            </div>
                            @error('image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <input type="file" name="image" id="image" class="d-none" accept="image/*"
                    onchange="previewImage(event)">
                <div class="col-lg-9">
                    <div class="bg-light rounded p-4">
                        {{-- Flash messages --}}
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
                                <input type="text" class="form-control" placeholder="Name" value="{{ $user->name }}"
                                    name="name">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" placeholder="Email" value="{{ $user->email }}"
                                    name="email">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>

                                <input type="text" class="form-control" placeholder="Phone"
                                    value="{{ auth('branch')->check() ? $user->phone : (auth('coach')->check() ? $user->phone : $user->number) }}"
                                    name="number">

                                @error(auth('branch')->check() || auth('coach')->check() ? 'phone' : 'number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <div class="row mt-4 g-3">
                            <div class="col-md-6 position-relative">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Password">
                                <i class="fa fa-eye position-absolute top-50 end-0 translate-middle-y pe-3 cursor-pointer mt-3"
                                    onclick="togglePassword('password', this)"></i>
                            </div>


                            <div class="col-md-6 position-relative">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm-password"
                                    placeholder="Confirm Password" name="password_confirmation">
                                <i class="fa fa-eye position-absolute top-50 end-0 translate-middle-y pe-3 cursor-pointer mt-3"
                                    onclick="togglePassword('confirm-password', this)"></i>
                            </div>
                        </div>
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                        <div class="mt-4">
                            <button class="btn btn-primary" type="submit">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>


    <script src="{{ asset('assets/customjs/profile.js') }}"></script>

@endsection
