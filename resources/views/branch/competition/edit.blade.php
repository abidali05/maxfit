@extends('layouts.branch.app')
@section('title', 'Edit Competitions')
@section('content')
    <div class="px-4 pt-4 container-fluid" style="min-height: 82.5vh">
        <form action="{{ route('branch.updateCompetition', $competition->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row g-4">
                <div class="col-lg-12">
                    <div class="p-4 rounded bg-light">

                        {{-- Success/Error Alerts --}}
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
                                <input type="text" class="form-control" name="name"
                                    value="{{ old('name', $competition->name) }}" required>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Age Group</label>
                                <input type="number" class="form-control" name="age_group" id="age_group"
                                    value="{{ old('age_group', $competition->age_group) }}" required>
                                @error('age_group')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select class="form-control" name="status">
                                    <option value="active"
                                        {{ old('status', $competition->status) === 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive"
                                        {{ old('status', $competition->status) === 'inactive' ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
                                @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Country</label>
                                <input type="text" class="form-control" name="country"
                                    value="{{ old('country', $competition->country) }}" required>
                                @error('country')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Time Allowed (minutes)</label>
                                <input type="number" class="form-control" name="time_allowed" min="1"
                                    value="{{ old('time_allowed', $competition->time_allowed) }}">
                                @error('time_allowed')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Select Genz</label>
                                <select class="form-control" id="genz" disabled>
                                    <option value="">Select</option>
                                    <option value="motherfits"
                                        {{ old('genz', $competition->genz) === 'motherfits' ? 'selected' : '' }}>Motherfits
                                    </option>
                                    <option value="fatherfits"
                                        {{ old('genz', $competition->genz) === 'fatherfits' ? 'selected' : '' }}>Fatherfits
                                    </option>
                                </select>
                                <input type="hidden" name="genz" id="genz_hidden"
                                    value="{{ old('genz', $competition->genz) }}">
                                @error('genz')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Organization Type</label>
                                <select class="form-control" name="org_type" id="org_type">
                                    <option value="">Select</option>
                                    @foreach ($organizationTypes as $organizationType)
                                        <option value="{{ $organizationType->id }}"
                                            {{ old('org_type', $competition->org_type) == $organizationType->id ? 'selected' : '' }}>
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
                                <select class="form-control" name="org" id="org">
                                    <option value="">Select</option>
                                    <!-- Options populated by AJAX -->
                                </select>
                                @error('org')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">YouTube Video Links</label>
                                <div id="youtube-links-wrapper">
                                    @if (old('youtube_links', $competition->videos->pluck('video_file')->toArray()))
                                        @foreach (old('youtube_links', $competition->videos->pluck('video_file')->toArray()) as $index => $link)
                                            <div class="d-flex mb-2 youtube-link-row">
                                                <input type="url" class="form-control me-2" name="youtube_links[]"
                                                    value="{{ $link }}"
                                                    placeholder="https://www.youtube.com/watch?v=..." required>
                                                <button type="button"
                                                    class="btn btn-danger btn-sm remove-link">&times;</button>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="d-flex mb-2 youtube-link-row">
                                            <input type="url" class="form-control me-2" name="youtube_links[]"
                                                placeholder="https://www.youtube.com/watch?v=..." required>
                                            <button type="button"
                                                class="btn btn-danger btn-sm remove-link">&times;</button>
                                        </div>
                                    @endif
                                </div>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="add-link">+ Add
                                    another link</button>
                                @error('youtube_links.*')
                                    <span class="text-danger d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <button class="btn btn-primary" type="submit">Update Competition</button>
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

        // Set initial Genz value on page load
        window.addEventListener('load', function() {
            const ageGroupInput = document.getElementById('age_group');
            const ageGroup = parseInt(ageGroupInput.value);
            if (!isNaN(ageGroup)) {
                updateGenzValue(ageGroup);
            }
        });

        // Update Genz value on age_group input change
        document.getElementById('age_group').addEventListener('input', function() {
            const ageGroup = parseInt(this.value);
            if (!isNaN(ageGroup)) {
                updateGenzValue(ageGroup);
            }
        });

        // Filter organizations based on organization type
        document.getElementById('org_type').addEventListener('change', function() {
            const orgTypeId = this.value;
            const orgSelect = document.getElementById('org');

            // Clear current options
            orgSelect.innerHTML = '<option value="">Select</option>';

            if (orgTypeId) {
                // Make AJAX request to fetch organizations
                fetch(`/get-organizations/${orgTypeId}`, {
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
                            if (org.id == '{{ old('org', $competition->org) }}') {
                                option.selected = true;
                            }
                            orgSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching organizations:', error));
            }
        });

        // Trigger org_type change on page load to populate org select
        window.addEventListener('load', function() {
            const orgTypeSelect = document.getElementById('org_type');
            if (orgTypeSelect.value) {
                orgTypeSelect.dispatchEvent(new Event('change'));
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            const wrapper = document.getElementById("youtube-links-wrapper");
            const addLinkBtn = document.getElementById("add-link");

            addLinkBtn.addEventListener("click", function() {
                const div = document.createElement("div");
                div.classList.add("d-flex", "mb-2", "youtube-link-row");

                div.innerHTML = `
                <input type="url" class="form-control me-2" name="youtube_links[]" placeholder="https://www.youtube.com/watch?v=..." required>
                <button type="button" class="btn btn-danger btn-sm remove-link">&times;</button>
            `;

                wrapper.appendChild(div);
            });

            wrapper.addEventListener("click", function(e) {
                if (e.target.classList.contains("remove-link")) {
                    e.target.closest(".youtube-link-row").remove();
                }
            });
        });
    </script>
@endsection
