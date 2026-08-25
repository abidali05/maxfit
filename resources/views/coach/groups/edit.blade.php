@extends('layouts.coach.app')
@section('title', 'Edit Athlete Group')
@section('content')
    <div class="container-fluid pt-4 px-4" style="min-height: 82.5vh">
        <form action="{{ route('coach.groups.update', $group->id) }}" method="POST" id="groupForm">
            @csrf
            @method('PUT')
            <div class="row g-4">
                <div class="col-12">
                    <div class="bg-light rounded p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-2">
                            <div>
                                <h5 class="mb-0">Edit Athlete Group</h5>
                                <small class="text-muted">Modify group details, instructions, criteria, and athlete invitations.</small>
                            </div>
                            <a href="{{ route('coach.groups.show', $group->id) }}" class="btn btn-outline-secondary btn-sm"><i class="fa fa-arrow-left me-2"></i>Back to Group</a>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6 col-lg-4">
                                <label for="name" class="form-label fw-bold">Group Name</label>
                                <input type="text" class="form-control" name="name" id="name" required value="{{ old('name', $group->name) }}" placeholder="e.g. Pro Sprinters">
                                @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="age_group" class="form-label fw-bold">Age Group Filter</label>
                                <input type="text" class="form-control filter-input" name="age_group" id="age_group" value="{{ old('age_group', $group->age_group) }}" placeholder="e.g. 14 or 14-30">
                                @error('age_group')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="country" class="form-label fw-bold">Country Filter</label>
                                <select class="form-select select2 filter-input" name="country" id="country">
                                    <option value="">Select Country</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" {{ old('country', $group->country) == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                    @endforeach
                                </select>
                                @error('country')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="org_type" class="form-label fw-bold">Organization Type Filter</label>
                                <select class="form-select select2 filter-input" name="org_types[]" id="org_type" multiple>
                                    @php
                                        $selectedOrgTypes = old('org_types', $group->org_types ?? []);
                                    @endphp
                                    @foreach($organizationTypes as $orgType)
                                        <option value="{{ $orgType->id }}" {{ in_array($orgType->id, $selectedOrgTypes) ? 'selected' : '' }}>{{ $orgType->name }}</option>
                                    @endforeach
                                </select>
                                @error('org_types')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="org" class="form-label fw-bold">Organization Filter</label>
                                <select class="form-select select2 filter-input" name="orgs[]" id="org" multiple>
                                    <!-- Loaded dynamically via JS -->
                                </select>
                                @error('orgs')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="genz" class="form-label fw-bold">GenZ Category Filter</label>
                                <select class="form-select filter-input" id="genz" disabled style="background-color: #e9ecef; cursor: not-allowed;">
                                    <option value="">Auto-selected from Age Group</option>
                                    <option value="fatherfits" {{ old('genz', $group->genz) == 'fatherfits' ? 'selected' : '' }}>Father Fit</option>
                                    <option value="motherfits" {{ old('genz', $group->genz) == 'motherfits' ? 'selected' : '' }}>Mother Fit</option>
                                    <option value="both" {{ old('genz', $group->genz) == 'both' ? 'selected' : '' }}>Both</option>
                                </select>
                                <input type="hidden" name="genz" id="genz_hidden" value="{{ old('genz', $group->genz) }}">
                                @error('genz')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="gender" class="form-label fw-bold">Gender Filter</label>
                                <select class="form-select filter-input" name="gender" id="gender">
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('gender', $group->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender', $group->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="both" {{ old('gender', $group->gender) == 'both' ? 'selected' : '' }}>Both</option>
                                </select>
                                @error('gender')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <!-- Instructions -->
                        <div class="mb-4">
                            <label for="instructions" class="form-label fw-bold">Group Instructions</label>
                            <textarea name="instructions" id="instructions" class="form-control" rows="8">{{ old('instructions', $group->instructions) }}</textarea>
                            @error('instructions')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="border rounded p-3 mb-4 bg-white">
                            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                <h6 class="mb-0 fw-bold"><i class="fa fa-user-friends me-2 text-primary"></i>Matching Athletes</h6>
                                <span class="badge bg-primary" id="matchingCount">0 athletes found</span>
                            </div>

                            <div class="mb-3">
                                <label for="user_ids" class="form-label fw-bold">Select Athletes to Invite</label>
                                <select class="form-select select2" name="user_ids[]" id="user_ids" multiple required>
                                    <!-- Loaded dynamically by filters -->
                                </select>
                                <small class="text-muted">You can search and select multiple athletes. Existing members will keep their status, new members will get a pending invite, and deselected users will be removed.</small>
                                @error('user_ids')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('coach.groups.show', $group->id) }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save me-2"></i>Update Group</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Load CKEditor from CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize CKEditor on Instructions textarea
            ClassicEditor
                .create(document.querySelector('#instructions'), {
                    toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ]
                })
                .catch(error => {
                    console.error(error);
                });

            // Initialize standard select2 elements
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

            // Ajax helper URL paths
            const eligibleUsersUrl = "{{ route('coach.groups.eligible-users') }}";
            const orgByTypesUrl = "{{ route('coach.groups.organizations-by-types') }}";

            // Existing values to pre-populate
            const currentSelectedOrgs = @json(old('orgs', $group->orgs ?? []));
            const initialInvitedUserIds = @json(old('user_ids', $selectedUserIds));

            // Load initial organizations based on pre-selected organization types
            loadOrganizations(true);

            // Whenever organization types change, update organizations dropdown
            $('#org_type').on('change', function () {
                loadOrganizations(false);
            });

            function loadOrganizations(isInitial = false) {
                const orgTypes = $('#org_type').val() || [];
                const orgSelect = $('#org');

                orgSelect.val(null).trigger('change');

                if (orgTypes.length === 0) {
                    orgSelect.html('');
                    updateEligibleUsers();
                    return;
                }

                const params = new URLSearchParams();
                orgTypes.forEach(val => params.append('org_types[]', val));

                fetch(`${orgByTypesUrl}?${params.toString()}`, {
                    headers: { 'Accept': 'application/json' }
                })
                .then(response => response.json())
                .then(payload => {
                    const results = payload.results || payload || [];
                    orgSelect.html('');
                    results.forEach(item => {
                        const isSelected = isInitial && currentSelectedOrgs.includes(String(item.id));
                        const opt = new Option(item.name || item.text, item.id, isSelected, isSelected);
                        orgSelect.append(opt);
                    });
                    orgSelect.trigger('change');
                    if (isInitial) {
                        updateEligibleUsers(true);
                    }
                })
                .catch(err => console.error('Failed loading organizations:', err));
            }

            function parseAgeGroup(value) {
                const text = String(value || "").trim();
                if (!text) return null;
                const parts = text.split("-").map(p => p.trim()).filter(Boolean);
                if (parts.length === 1) {
                    const age = parseInt(parts[0], 10);
                    return Number.isFinite(age) && age > 0 ? { min: age, max: age } : null;
                }
                if (parts.length === 2) {
                    const min = parseInt(parts[0], 10);
                    const max = parseInt(parts[1], 10);
                    if (Number.isFinite(min) && Number.isFinite(max) && min > 0 && max > 0 && min <= max) {
                        return { min, max };
                    }
                }
                return null;
            }

            function autoSelectGenz() {
                const parsed = parseAgeGroup($('#age_group').val());
                let inferred = '';
                if (parsed) {
                    inferred = parsed.max < 14 ? 'motherfits' : 'fatherfits';
                }
                $('#genz').val(inferred);
                $('#genz_hidden').val(inferred);
            }

            $('#age_group').on('input change', function () {
                autoSelectGenz();
            });

            // Listen to any filter changes to load matching athletes
            $('.filter-input, #org').on('change input', function () {
                updateEligibleUsers(false);
            });

            function updateEligibleUsers(isInitial = false) {
                const ageGroup = $('#age_group').val().trim();
                const country = $('#country').val();
                const orgTypes = $('#org_type').val() || [];
                const orgs = $('#org').val() || [];
                const genz = $('#genz_hidden').val() || $('#genz').val();
                const gender = $('#gender').val();

                const params = new URLSearchParams();
                if (ageGroup) params.append('age_group', ageGroup);
                if (country) params.append('country', country);
                if (genz) params.append('genz', genz);
                if (gender) params.append('gender', gender);
                orgTypes.forEach(val => params.append('org_types[]', val));
                orgs.forEach(val => params.append('orgs[]', val));

                $('#matchingCount').text('Searching...');

                fetch(`${eligibleUsersUrl}?${params.toString()}`, {
                    headers: { 'Accept': 'application/json' }
                })
                .then(response => response.json())
                .then(users => {
                    const select = $('#user_ids');
                    
                    // On edit, we MUST include already invited athletes even if they no longer match the filter,
                    // so we don't accidentally remove or drop them unless the coach explicitly deselects them.
                    // But for Select2 options, we will render all matching users, plus make sure the currently selected users are included as options.
                    const userList = [...users];

                    // Merge in initial invited users if they aren't in the list
                    const initialList = @json($group->groupUsers);
                    initialList.forEach(item => {
                        if (item.user && !userList.some(u => String(u.id) === String(item.user.id))) {
                            userList.push({
                                id: item.user.id,
                                name: item.user.name,
                                email: item.user.email
                            });
                        }
                    });

                    select.html('');
                    userList.forEach(user => {
                        const isSelected = initialInvitedUserIds.includes(Number(user.id)) || initialInvitedUserIds.includes(String(user.id));
                        const opt = new Option(user.name + ' (' + user.email + ')', user.id, isSelected, isSelected);
                        select.append(opt);
                    });

                    select.trigger('change');
                    $('#matchingCount').text(users.length + ' athletes found');
                })
                .catch(err => {
                    console.error('Failed loading eligible athletes:', err);
                    $('#matchingCount').text('0 athletes found');
                });
            }
        });
    </script>
@endsection
