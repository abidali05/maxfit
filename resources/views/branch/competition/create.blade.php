@extends('layouts.branch.app')
@section('title', 'Create Competitions')
@section('content')
    <div class="px-4 pt-4 container-fluid" style="min-height: 82.5vh">
        <form action="{{ route('branch.storeCompetitions') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-4">
                <div class="col-lg-12">
                    <div class="p-4 rounded bg-light">
                        <div class="mb-4 d-flex align-items-center justify-content-between">
                            <h6 class="mb-0">Add Competition</h6>
                        </div>
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
                            <!-- Shared Fields -->
                            <div class="col-md-6">
                                <label class="form-label">Competition Name</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                    required>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Age Group</label>
                                <input type="number" class="form-control" name="age_group" id="age_group"
                                    value="{{ old('age_group') }}" required>
                                @error('age_group')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Select Genz</label>
                                <select class="form-control" id="genz" disabled required>
                                    <option value="">Select</option>
                                    <option value="motherfits" {{ old('genz') === 'motherfits' ? 'selected' : '' }}>
                                        Motherfits</option>
                                    <option value="fatherfits" {{ old('genz') === 'fatherfits' ? 'selected' : '' }}>
                                        Fatherfits</option>
                                </select>
                                <input type="hidden" name="genz" id="genz_hidden" value="{{ old('genz') }}">
                                @error('genz')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Country</label>
                                <input type="text" class="form-control" name="country" value="{{ old('country') }}"
                                    required>
                                @error('country')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Organization Type</label>
                                <select class="form-control org-type-select" name="org_type" required>
                                    <option value="">Select</option>
                                    @foreach ($organizationTypes as $organizationType)
                                        <option value="{{ $organizationType->id }}"
                                            {{ old('org_type') == $organizationType->id ? 'selected' : '' }}>
                                            {{ $organizationType->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('org_type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Organization</label>
                                <select class="form-control org-select" name="org" required>
                                    <option value="">Select</option>
                                    @if (old('org_type'))
                                        @foreach ($organizations->where('type', old('org_type')) as $organization)
                                            <option value="{{ $organization->id }}"
                                                {{ old('org') == $organization->id ? 'selected' : '' }}>
                                                {{ $organization->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('org')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Time Allowed (minutes)</label>
                                <input type="number" class="form-control" name="time_allowed" min="1"
                                    value="{{ old('time_allowed') }}" required>
                                @error('time_allowed')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Repeatable Competition Fields -->
                        <div id="competition-container" class="mt-4">
                            <div class="competition-field border p-3 mb-3 rounded">
                                <h6>Competition Details</h6>
                                <div class="row g-3">
                                    {{-- <div class="col-md-6">
                                        <label class="form-label">Coach Name</label>
                                        <input type="text" class="form-control" name="coach_name[]"
                                            value="{{ old('coach_name.0') }}" required>
                                        @error('coach_name.0')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div> --}}

                                    <div class="col-md-6">
                                        <label class="form-label">Coach</label>
                                        <select class="form-control org-type-select" name="coach_id[]" required>
                                            <option value="">Select</option>
                                            @foreach ($coaches as $coach)
                                                <option value="{{ $coach->id }}"
                                                    {{ old('coach_id') == $coach->id ? 'selected' : '' }}>
                                                    {{ $coach->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('coach_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">City</label>
                                        <input type="text" class="form-control" name="cities[]"
                                            value="{{ old('cities.0') }}" required>
                                        @error('cities.0')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Start Date</label>
                                        <input type="date" class="form-control" name="start_date[]"
                                            value="{{ old('start_date.0') }}" required>
                                        @error('start_date.0')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">End Date</label>
                                        <input type="date" class="form-control" name="end_date[]"
                                            value="{{ old('end_date.0') }}" required>
                                        @error('end_date.0')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Start Time</label>
                                        <input type="time" class="form-control" name="start_time[]"
                                            value="{{ old('start_time.0') }}" required>
                                        @error('start_time.0')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">End Time</label>
                                        <input type="time" class="form-control" name="end_time[]"
                                            value="{{ old('end_time.0') }}" required>
                                        @error('end_time.0')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Image</label>
                                        <input type="file" class="form-control" name="image[]" accept="image/*">
                                        @error('image.0')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" name="description[]" rows="4">{{ old('description.0') }}</textarea>
                                        @error('description.0')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-danger btn-sm remove-competition"
                                            style="display: none;">Remove Competition</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-secondary btn-sm mt-2" id="add-competition">Add Another
                            Competition</button>

                        <div class="mt-4">
                            <button class="btn btn-primary" type="submit">Save Competitions</button>
                            <a href="{{ route('competitions.index') }}" class="btn btn-outline-danger">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Function to update Genz value based on age group
        function updateGenzValue(ageGroup) {
            const genzSelect = document.getElementById('genz');
            const genzHidden = document.getElementById('genz_hidden');

            if (ageGroup < 14) {
                genzSelect.value = 'motherfits';
                genzHidden.value = 'motherfits';
            } else {
                genzSelect.value = 'fatherfits';
                genzHidden.value = 'fatherfits';
            }
        }

        // Set initial Genz value and org_type on page load
        window.addEventListener('load', function() {
            const ageGroupInput = document.getElementById('age_group');
            const ageGroup = parseInt(ageGroupInput.value) || 0;
            updateGenzValue(ageGroup);

            // Trigger org_type change for existing competition fields
            document.querySelectorAll('.org-type-select').forEach(select => {
                if (select.value) {
                    select.dispatchEvent(new Event('change'));
                }
            });
            updateRemoveButtons(); // Initialize remove button visibility
        });

        // Update Genz value on age_group input change
        document.getElementById('age_group').addEventListener('input', function() {
            const ageGroup = parseInt(this.value) || 0;
            updateGenzValue(ageGroup);
        });

        // Filter organizations based on organization type
        function attachOrgTypeListener(orgTypeSelect, orgSelect) {
            orgTypeSelect.addEventListener('change', function() {
                const orgTypeId = this.value;
                orgSelect.innerHTML = '<option value="">Select</option>';

                if (orgTypeId) {
                    fetch(`/branch/get-organizations/${orgTypeId}`, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(org => {
                                const option = document.createElement('option');
                                option.value = org.id;
                                option.text = org.name;
                                orgSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error fetching organizations:', error));
                }
            });
        }

        // Attach org_type listener to existing org_type select
        const initialOrgTypeSelect = document.querySelector('.org-type-select');
        const initialOrgSelect = document.querySelector('.org-select');
        if (initialOrgTypeSelect && initialOrgSelect) {
            attachOrgTypeListener(initialOrgTypeSelect, initialOrgSelect);
        }

        // Add new competition field set
        document.getElementById('add-competition').addEventListener('click', function() {
            const competitionContainer = document.getElementById('competition-container');
            const competitionField = document.createElement('div');
            competitionField.className = 'competition-field border p-3 mb-3 rounded';
            competitionField.innerHTML = `
                <h6>Competition Details</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Coach</label>
                        <select class="form-control org-type-select" name="coach_id[]" required>
                            <option value="">Select</option>
                            @foreach ($coaches as $coach)
                                <option value="{{ $coach->id }}"
                                    {{ old('coach_id') == $coach->id ? 'selected' : '' }}>
                                    {{ $coach->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('coach_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">City</label>
                        <input type="text" class="form-control" name="cities[]" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Start Date</label>
                        <input type="date" class="form-control" name="start_date[]" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">End Date</label>
                        <input type="date" class="form-control" name="end_date[]" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Start Time</label>
                        <input type="time" class="form-control" name="start_time[]" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">End Time</label>
                        <input type="time" class="form-control" name="end_time[]" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Image</label>
                        <input type="file" class="form-control" name="image[]" accept="image/*">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description[]" rows="4"></textarea>
                    </div>
                    <div class="col-md-12">
                        <button type="button" class="btn btn-danger btn-sm remove-competition">Remove Competition</button>
                    </div>
                </div>
            `;
            competitionContainer.appendChild(competitionField);

            // Attach org_type listener to new org_type select
            const newOrgTypeSelect = competitionField.querySelector('.org-type-select');
            const newOrgSelect = competitionField.querySelector('.org-select');
            if (newOrgTypeSelect && newOrgSelect) {
                attachOrgTypeListener(newOrgTypeSelect, newOrgSelect);
            }

            // Update remove button visibility
            updateRemoveButtons();
        });

        // Function to update visibility of remove buttons using event delegation
        function updateRemoveButtons() {
            const competitionContainer = document.getElementById('competition-container');
            const competitionFields = competitionContainer.getElementsByClassName('competition-field');

            if (competitionFields.length > 1) {
                Array.from(competitionFields).forEach((field, index) => {
                    const removeButton = field.querySelector('.remove-competition');
                    if (removeButton) {
                        removeButton.style.display = index === 0 ? 'none' : 'inline-block';
                    }
                });
            } else {
                const removeButton = competitionContainer.querySelector('.remove-competition');
                if (removeButton) {
                    removeButton.style.display = 'none';
                }
            }
        }

        // Event delegation for remove buttons
        document.getElementById('competition-container').addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-competition')) {
                e.target.closest('.competition-field').remove();
                updateRemoveButtons();
            }
        });
    </script>
@endsection
