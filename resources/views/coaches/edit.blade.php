@extends('layouts.app')
@section('title', 'Edit Coach')
@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-12">
                <div class="bg-light rounded p-4">
                    <h4>Edit Coach</h4>
                    <form action="{{ route('coaches.update', $coach->id) }}" method="POST" enctype="multipart/form-data" id="coachForm">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $coach->name }}" required maxlength="255">
                            <div class="text-danger error-message" id="name-error"></div>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $coach->email }}" required>
                            <div class="text-danger error-message" id="email-error"></div>
                        </div>
                        <div class="mb-3">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ $coach->phone }}" required maxlength="20">
                            <div class="text-danger error-message" id="phone-error"></div>
                        </div>
                        <div class="mb-3">
                            <label>Image</label>
                            <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif">
                            @if ($coach->image)
                                <img src="{{ asset('storage/' . $coach->image) }}" width="80" class="mt-2">
                            @endif
                            <div class="text-danger error-message" id="image-error"></div>
                        </div>
                        <div class="mb-3">
                            <label>Address</label>
                            <input type="text" name="address" class="form-control" value="{{ $coach->address }}" required maxlength="255">
                            <div class="text-danger error-message" id="address-error"></div>
                        </div>
                        <div class="mb-3">
                            <label>Country</label>
                            <select name="country_id" class="form-control" required>
                                <option value="">Select Country</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" {{ $coach->country_id == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="text-danger error-message" id="country_id-error"></div>
                        </div>
                        <div class="mb-3">
                            <label>City</label>
                            <select name="city_id" class="form-control" required>
                                <option value="">Select City</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}" {{ $coach->city_id == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="text-danger error-message" id="city_id-error"></div>
                        </div>
                        <div class="mb-3">
                            <label>Bio</label>
                            <textarea name="bio" class="form-control">{{ $coach->bio }}</textarea>
                            <div class="text-danger error-message" id="bio-error"></div>
                        </div>
                        <button type="submit" class="btn btn-primary" id="submitBranch">Update</button>
                        <a href="{{ route('coaches.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('coachForm');

            if (!form) {
                console.error('Form with ID "coachForm" not found.');
                return;
            }

            $('#submitBranch').on('click', function(e) {
                e.preventDefault();
                let isValid = true;
                const errorElements = document.getElementsByClassName('error-message');

                // Clear previous errors
                Array.from(errorElements).forEach(element => {
                    element.textContent = '';
                });

                console.log('Validation started...');

                // Validate name
                const name = document.querySelector('input[name="name"]');
                if (!name.value.trim()) {
                    document.getElementById('name-error').textContent = 'Name is required.';
                    isValid = false;
                } else if (name.value.length > 255) {
                    document.getElementById('name-error').textContent = 'Name must not exceed 255 characters.';
                    isValid = false;
                }

                // Validate email
                const email = document.querySelector('input[name="email"]');
                if (!email.value.trim()) {
                    document.getElementById('email-error').textContent = 'Email is required.';
                    isValid = false;
                } else if (!isValidEmail(email.value)) {
                    document.getElementById('email-error').textContent = 'Please enter a valid email address.';
                    isValid = false;
                }

                // Validate phone
                const phone = document.querySelector('input[name="phone"]');
                if (!phone.value.trim()) {
                    document.getElementById('phone-error').textContent = 'Phone is required.';
                    isValid = false;
                } else if (phone.value.length > 20) {
                    document.getElementById('phone-error').textContent = 'Phone must not exceed 20 characters.';
                    isValid = false;
                }

                // Validate image
                const image = document.querySelector('input[name="image"]');
                if (image.files.length > 0) {
                    const file = image.files[0];
                    const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                    if (!validTypes.includes(file.type)) {
                        document.getElementById('image-error').textContent = 'Invalid image format. Use jpeg, png, jpg, or gif.';
                        isValid = false;
                    } else if (file.size > 2048 * 1024) { // 2048 KB = 2MB
                        document.getElementById('image-error').textContent = 'Image size must not exceed 2MB.';
                        isValid = false;
                    }
                }

                // Validate address
                const address = document.querySelector('input[name="address"]');
                if (!address.value.trim()) {
                    document.getElementById('address-error').textContent = 'Address is required.';
                    isValid = false;
                } else if (address.value.length > 255) {
                    document.getElementById('address-error').textContent = 'Address must not exceed 255 characters.';
                    isValid = false;
                }

                // Validate country
                const country = document.querySelector('select[name="country_id"]');
                if (!country.value) {
                    document.getElementById('country_id-error').textContent = 'Country is required.';
                    isValid = false;
                }

                // Validate city
                const city = document.querySelector('select[name="city_id"]');
                if (!city.value) {
                    document.getElementById('city_id-error').textContent = 'City is required.';
                    isValid = false;
                }

                // Validate bio (no strict validation as it is nullable)
                const bio = document.querySelector('textarea[name="bio"]');

                console.log('Validation complete. Is valid:', isValid);

                if (isValid) {
                    form.submit();
                } else {
                    console.log('Form submission prevented due to validation errors.');
                }
            });

            // Simple email validation function
            function isValidEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }
        });
    </script>
@endsection
