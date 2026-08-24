@extends('layouts.coach.app')
@section('title', 'Create Athlete Group')
@section('content')
    <div class="container-fluid pt-4 px-4" style="min-height: 82.5vh">
        <form action="{{ route('coach.groups.store') }}" method="POST" id="groupForm">
            @csrf
            <div class="row g-4">
                <div class="col-12">
                    <div class="bg-light rounded p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-2">
                            <div>
                                <h5 class="mb-0">Create Athlete Group</h5>
                                <small class="text-muted">Define group criteria and select athletes to invite.</small>
                            </div>
                            <a href="{{ route('coach.groups.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fa fa-arrow-left me-2"></i>Back to List</a>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6 col-lg-4">
                                <label for="name" class="form-label fw-bold">Group Name</label>
                                <input type="text" class="form-control" name="name" id="name" required placeholder="e.g. Pro Sprinters">
                                @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="age_group" class="form-label fw-bold">Age Group Filter</label>
                                <input type="text" class="form-control filter-input" name="age_group" id="age_group" placeholder="e.g. 14 or 14-30">
                                @error('age_group')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="country" class="form-label fw-bold">Country Filter</label>
                                <select class="form-select select2 filter-input" name="country" id="country">
                                    <option value="">Select Country</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                                @error('country')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="org_type" class="form-label fw-bold">Organization Type Filter</label>
                                <select class="form-select select2 filter-input" name="org_types[]" id="org_type" multiple>
                                    @foreach($organizationTypes as $orgType)
                                        <option value="{{ $orgType->id }}">{{ $orgType->name }}</option>
                                    @endforeach
                                </select>
                                @error('org_types')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="org" class="form-label fw-bold">Organization Filter</label>
                                <select class="form-select select2 filter-input" name="orgs[]" id="org" multiple>
                                    <!-- Populated dynamically via org_type change -->
                                </select>
                                @error('orgs')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="genz" class="form-label fw-bold">GenZ Category Filter</label>
                                <select class="form-select filter-input" name="genz" id="genz">
                                    <option value="">Select Category</option>
                                    <option value="fatherfits">Father Fit</option>
                                    <option value="motherfits">Mother Fit</option>
                                    <option value="both">Both</option>
                                </select>
                                @error('genz')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="gender" class="form-label fw-bold">Gender Filter</label>
                                <select class="form-select filter-input" name="gender" id="gender">
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="both">Both</option>
                                </select>
                                @error('gender')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <!-- Instructions -->
                        <div class="mb-4">
                            <label for="instructions" class="form-label fw-bold">Group Instructions</label>
                            <textarea name="instructions" id="instructions" class="form-control" rows="8">{{ old('instructions') }}</textarea>
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
                                <small class="text-muted">You can search and select multiple athletes from this filtered list.</small>
                                @error('user_ids')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('coach.groups.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save me-2"></i>Create Group</button>
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

            // Whenever organization types change, update organizations dropdown
            $('#org_type').on('change', function () {
                const orgTypes = $(this).val() || [];
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
                        const opt = new Option(item.name || item.text, item.id, false, false);
                        orgSelect.append(opt);
                    });
                    orgSelect.trigger('change');
                })
                .catch(err => console.error('Failed loading organizations:', err));
            });

            // Listen to any filter changes to load matching athletes
            $('.filter-input, #org').on('change input', function () {
                updateEligibleUsers();
            });

            // Trigger initial loading
            updateEligibleUsers();

            function updateEligibleUsers() {
                const ageGroup = $('#age_group').val().trim();
                const country = $('#country').val();
                const orgTypes = $('#org_type').val() || [];
                const orgs = $('#org').val() || [];
                const genz = $('#genz').val();
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
                    const previouslySelected = select.val() || [];

                    select.html('');

                    users.forEach(user => {
                        const isSelected = previouslySelected.includes(String(user.id));
                        const opt = new Option(user.name + ' (' + user.email + ')', user.id, isSelected, isSelected);
                        select.append(opt);
                    });

                    // Maintain previously selected values if they are still present
                    select.val(previouslySelected.filter(id => users.some(u => String(u.id) === String(id)))).trigger('change');

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
