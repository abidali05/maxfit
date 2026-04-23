@extends('layouts.app')
@section('title', 'Add Branch')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-12">
                <div class="bg-light rounded p-4">
                    <h4>Add Branch</h4>
                    <form action="{{ route('branches.store') }}" method="POST" enctype="multipart/form-data" id="branchForm">
                        @csrf
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required maxlength="255">
                            <div class="text-danger error-message" id="name-error"></div>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                            <div class="text-danger error-message" id="email-error"></div>
                        </div>
                        <div class="mb-3">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control" maxlength="20">
                            <div class="text-danger error-message" id="phone-error"></div>
                        </div>
                        <div class="mb-3">
                            <label>Image</label>
                            <input type="file" name="image" class="form-control"
                                accept="image/jpeg,image/png,image/jpg,image/gif">
                            <div class="text-danger error-message" id="image-error"></div>
                        </div>
                        <div class="mb-3">
                            <label>Bio</label>
                            <textarea name="bio" class="form-control"></textarea>
                            <div class="text-danger error-message" id="bio-error"></div>
                        </div>

                        {{-- New Fields --}}
                        <div class="mb-3">
                            <label>Percentage</label>
                            <input type="number" name="percentage" class="form-control" min="0" max="100"
                                step="0.01">
                            <div class="text-danger error-message" id="percentage-error"></div>
                        </div>

                        <div class="mb-3">
                            <label>Address</label>
                            <input type="text" name="address" class="form-control" maxlength="255"
                                placeholder="Enter branch address">
                            <div class="text-danger error-message" id="address-error"></div>
                        </div>

                        <div class="mb-3">
                            <label>Country</label>
                            <select name="country_id" class="form-control select2" required>
                                <option value="">Select Country</option>
                                @foreach ($country as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger error-message" id="country_id-error"></div>
                        </div>

                        <div class="mb-3">
                            <label>City</label>
                            <select name="city_id" class="form-control select2" required>
                                <option value="">Select City</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger error-message" id="city_id-error"></div>
                        </div>

                        <button type="button" class="btn btn-primary" id="submitBranch">Save</button>
                        <a href="{{ route('branches.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            // Client-side validation
            $('#submitBranch').on('click', function(e) {
                e.preventDefault();
                let isValid = true;
                const errorElements = document.getElementsByClassName('error-message');

                // Clear previous errors
                Array.from(errorElements).forEach(element => {
                    element.textContent = '';
                });

                // Validate name
                const name = document.querySelector('input[name="name"]');
                if (!name.value.trim()) {
                    document.getElementById('name-error').textContent = 'Name is required.';
                    isValid = false;
                } else if (name.value.length > 255) {
                    document.getElementById('name-error').textContent =
                        'Name must not exceed 255 characters.';
                    isValid = false;
                }

                // Validate email
                const email = document.querySelector('input[name="email"]');
                if (!email.value.trim()) {
                    document.getElementById('email-error').textContent = 'Email is required.';
                    isValid = false;
                } else if (!isValidEmail(email.value)) {
                    document.getElementById('email-error').textContent =
                        'Please enter a valid email address.';
                    isValid = false;
                }

                // Validate phone
                const phone = document.querySelector('input[name="phone"]');
                if (!phone.value.trim()) {
                    document.getElementById('phone-error').textContent = 'Phone is required.';
                    isValid = false;
                } else if (phone.value.length > 20) {
                    document.getElementById('phone-error').textContent =
                        'Phone must not exceed 20 characters.';
                    isValid = false;
                }

                // Validate image
                const image = document.querySelector('input[name="image"]');
                if (image.files.length > 0) {
                    const file = image.files[0];
                    const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                    if (!validTypes.includes(file.type)) {
                        document.getElementById('image-error').textContent =
                            'Invalid image format. Use jpeg, png, jpg, or gif.';
                        isValid = false;
                    } else if (file.size > 2048 * 1024) { // 2048 KB = 2MB
                        document.getElementById('image-error').textContent =
                            'Image size must not exceed 2MB.';
                        isValid = false;
                    }
                }

                // Validate bio
                const bio = document.querySelector('textarea[name="bio"]');
                // No specific validation for bio as it is nullable

                // Validate percentage
                const percentage = document.querySelector('input[name="percentage"]');
                if (!percentage.value.trim()) {
                    document.getElementById('percentage-error').textContent = 'Percentage is required.';
                    isValid = false;
                } else if (parseFloat(percentage.value) < 0 || parseFloat(percentage.value) > 100) {
                    document.getElementById('percentage-error').textContent =
                        'Percentage must be between 0 and 100.';
                    isValid = false;
                }

                // Validate address
                const address = document.querySelector('input[name="address"]');
                if (!address.value.trim()) {
                    document.getElementById('address-error').textContent = 'Address is required.';
                    isValid = false;
                } else if (address.value.length > 255) {
                    document.getElementById('address-error').textContent =
                        'Address must not exceed 255 characters.';
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

                if (isValid) {
                    $('#branchForm').submit();
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
