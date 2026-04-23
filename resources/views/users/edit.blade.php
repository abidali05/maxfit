@extends('layouts.app')
@section('title', 'Edit User')
@section('content')
    <div class="container-fluid pt-4 px-4" style="min-height: 82.5vh">
        <form action="{{ route('users.update', $user->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row g-4">

                <div class="col-lg-3">
                    <div class="bg-light rounded p-3">
                        <div class="col-md-6 text-center m-auto">
                            <img id="profile-preview" src="{{ $user->image ?? asset('assets/images/user.jpg') }}"
                                class="rounded-circle mb-2" width="100" height="100" alt="Profile">
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
                                    name="name" required>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" placeholder="Email" value="{{ $user->email }}"
                                    name="email" required>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" placeholder="Phone" value="{{ $user->number }}"
                                    name="number" required>
                                @error('number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Role</label>
                                <select name="role" id="role" class="form-select">
                                    <option value="admin" {{$user->role == 'admin' ? 'selected' : ''}}>Admin</option>
                                    <option value="user" {{$user->role == 'user' ? 'selected' : ''}}>User</option>
                                </select>
                                @error('number')
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

                        <div class="row mt-4 g-3">
                            <div class="col-md-6">
                                <label class="form-label">Province</label>
                                <select name="province" id="province"
                                    class="form-select select2 @error('province') is-invalid @enderror" required>
                                    <option value="">Select Province</option>
                                    @foreach ($data['provinces'] as $province)
                                        <option value="{{ $province->id }}" {{$user->state_province == $province->id ? 'selected' : ''}}>{{ $province->name }}</option>
                                    @endforeach
                                </select>

                                @error('province')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">City</label>
                                <select name="city" id="city"
                                    class="form-select select2 @error('city') is-invalid @enderror" required>
                                    @foreach ($data['cities'] as $city)
                                    @if ($user->city == $city->id)
                                        <option value="{{ $city->id }}" selected>{{ $city->name }}</option> 
                                    @endif
                                        
                                    @endforeach

                                </select>

                                @error('city')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mt-4 g-3">
                            <div class="col-md-6">
                                <label class="form-label">Organisation Type</label>
                                <select name="organisation_type" id="organisation_type"
                                    class="form-select select2 @error('organisation_type') is-invalid @enderror" required>
                                    <option value="">Select Organisation Type</option>
                                    @foreach ($data['organisation_types'] as $organisation_type)
                                        <option value="{{ $organisation_type->id }}" {{$user->organisation_type == $organisation_type->id ? 'selected' : ''}}>{{ $organisation_type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('organisation_type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Organisation</label>
                                <select name="organisation" id="organisation"
                                    class="form-select select2 @error('organisation') is-invalid @enderror" required>
                                    @foreach ($data['organizations'] as $organisation)
                                    @if ($user->organisation_id == $organisation->id)
                                        
                                    <option value="{{ $organisation->id }}" {{$user->organisation_id == $organisation->id ? 'selected' : ''}}>{{ $organisation->name }}
                                    </option>
                                    @endif
                                    @endforeach
                                </select>
                                @error('organisation')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-4 g-3">
                            <div class="col-md-6">
                                <label class="form-label">CNIC</label>
                                <input type="number" class="form-control" placeholder="CNIC"
                                    value="{{ $user->cnic }}" name="cnic" required>
                                @error('cnic')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">DOB</label>
                                <input type="text" class="form-control datepicker @error('dob') is-invalid @enderror "
                                    placeholder="Date Of Birth" value="{{ $user->dob }}" name="dob"
                                    id="dob" required>
                                @error('dob')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-4 g-3">
                            <div class="col-md-6">
                                <label class="form-label">Class</label>
                                <input type="text" name="class" id="class"
                                    class="form-control @error('class') is-invalid @enderror" placeholder="Class"
                                    value="{{ $user->class }}" required>
                                @error('class')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Gender</label>
                                <select name="gender" id="gender"
                                    class="form-select @error('gender') is-invalid @enderror" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ $user->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{$user->gender == 'Female' ? 'selected' : ''}}>Female</option>
                                </select>

                                @error('gender')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mt-4 g-3">
                            <div class="col-md-6">
                                <label class="form-label">Hobbies</label>
                                <textarea name="hobbies" id="hobbies" class="form-control @error('hobbies') is-invalid @enderror" cols="30"
                                    rows="3" required>{{ $user->hobbies }}</textarea>
                                @error('hobbies')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sports Played</label>
                                <textarea name="sports_played" id="sports_played" class="form-control @error('sports_played') is-invalid @enderror"
                                    cols="30" rows="3" required>{{ $user->sports_played }}</textarea>
                                @error('sports_played')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                        </div>

                        <div class="row mt-4 g-3">
                            <div class="col-md-6">
                                <label class="form-label">Guardian Name</label>
                                <input type="text" name="guardian_name" id="guardian_name"
                                    class="form-control @error('guardian_name') is-invalid @enderror"
                                    placeholder="Guardian Name" value="{{ $user->guardian_name }}" required>
                                @error('guardian_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Guardian Email</label>
                                <input type="email" name="guardian_email" id="guardian_email"
                                    class="form-control @error('guardian_email') is-invalid @enderror"
                                    placeholder="Guardian Email" value="{{ $user->guardian_email }}" required>
                                @error('guardian_email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-4 g-3">
                            <div class="col-md-6">
                                <label class="form-label">Guardian Phone</label>
                                <input type="text" name="guardian_phone" id="guardian_phone"
                                    class="form-control @error('guardian_phone') is-invalid @enderror"
                                    placeholder="Guardian Phone" value="{{ $user->guardian_number }}" required>
                                @error('guardian_phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Guardian DOB</label>
                                <input type="text"
                                    class="form-control datepicker @error('guardian_dob') is-invalid @enderror "
                                    placeholder="Guardian Date Of Birth" value="{{ $user->guardian_dob }}"
                                    name="guardian_dob" id="guardian_dob" required>
                                @error('guardian_dob')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>



                        <div class="mt-4">
                            <button class="btn btn-primary" type="submit">Save changes</button>
                            <a href="{{ route('users.index') }}" class="btn btn-outline-danger">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="{{ asset('assets/customjs/users/create.js') }}"></script>
@endsection
