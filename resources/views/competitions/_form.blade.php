@php
    $isEdit = ($formMode ?? 'create') === 'edit';
    $competition = $competition ?? new \App\Models\Competition();
    $selectedOrgTypeIds = collect(old('org_type', $selectedOrgTypeIds ?? []))->filter()->map(fn ($value) => (int) $value)->values()->all();
    $selectedCountryId = old('country', $selectedCountryId ?? null);
    if ($selectedCountryId && !is_numeric($selectedCountryId)) {
        $selectedCountryId = $countries->firstWhere('name', $selectedCountryId)?->id;
    }
    $selectedCountryName = old('country_name', $selectedCountryName ?? ($selectedCountryId ? $countries->firstWhere('id', (int) $selectedCountryId)?->name : null));
    $selectedOrgIds = collect(\Illuminate\Support\Arr::wrap(old('org', $selectedOrgIds ?? [])))
        ->filter()
        ->map(fn ($value) => (int) $value)
        ->values()
        ->all();
    $selectedOrgs = $selectedOrgs ?? [];
    $competitionAgeGroup = old('age_group', $competition->age_group ?? '');
    $competitionGenz = old('genz', $competition->getRawOriginal('genz') ?? '');
    $competitionDescription = old('description', $competition->description ?? '');
    $videoLinks = old('youtube_links', $competition->videos?->pluck('video_file')->all() ?? ['']);
    $detailSource = old('coach_id', []);
    $detailCount = max(is_array($detailSource) ? count($detailSource) : 0, $isEdit ? max($competition->details->count(), 1) : 1);
@endphp

<script>
    window.competitionFormConfig = {
        countUrl: @json(route('competitions.available-users-count')),
        orgUrl: @json(route('competitions.organizations-by-types')),
        countryUrl: @json(route('competitions.countries-search')),
        venueUrlBase: @json(url('competitions/venues-by-city')),
        acceptedUsersByCityUrlBase: @json($isEdit ? url('competitions/' . $competition->id . '/accepted-users-by-city') : null),
    };
    window.competitionCoachOptions = @json($coaches->map(fn ($coach) => ['id' => $coach->id, 'name' => $coach->name])->values());
    window.competitionCityOptions = @json($cities->map(fn ($city) => ['id' => $city->id, 'name' => $city->name])->values());
</script>

<div class="px-4 pt-4 container-fluid" style="min-height: 82.5vh">
    <form action="{{ $isEdit ? route('competitions.update', $competition->id) : route('competitions.store') }}" method="POST" enctype="multipart/form-data" id="competitionForm">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="row g-4">
            <div class="col-12">
                <div class="app-filter-card">
                    <div class="app-filter-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <p class="app-filter-title mb-1">{{ $isEdit ? 'Edit Competition' : 'Add Competition' }}</p>
                            <div class="app-filter-subtitle">Define eligibility, description, and competition detail blocks in one place.</div>
                        </div>
                    </div>

                    <div class="app-filter-body">
                        <div class="row g-3 app-filter-grid">
                            <div class="col-md-6 col-xl-4">
                                <label for="name">Competition Name</label>
                                <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $competition->name) }}">
                                @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <label for="age_group">Age Group</label>
                                <input type="text" class="form-control" name="age_group" id="age_group" value="{{ old('age_group', $competitionAgeGroup) }}" placeholder="e.g: 14 or 14-30">
                                @error('age_group')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <label for="country">Country</label>
                                <select
                                    class="form-select select2"
                                    name="country"
                                    id="country"
                                    data-selected-country="{{ $selectedCountryId }}"
                                    data-selected-country-name="{{ $selectedCountryName }}"
                                >
                                    <option value="">Select Country</option>
                                    @if ($selectedCountryId && $selectedCountryName)
                                        <option value="{{ $selectedCountryId }}" selected>{{ $selectedCountryName }}</option>
                                    @endif
                                </select>
                                @error('country')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <label for="org_type">Organization Type</label>
                                <select class="form-select select2" name="org_type[]" id="org_type" multiple>
                                    @foreach ($organizationTypes as $organizationType)
                                        <option value="{{ $organizationType->id }}" {{ in_array($organizationType->id, $selectedOrgTypeIds, true) ? 'selected' : '' }}>{{ $organizationType->name }}</option>
                                    @endforeach
                                </select>
                                @error('org_type')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                @error('org_type.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <label for="org">Organization</label>
                                <select class="form-select select2" name="org[]" id="org" multiple>
                                    @foreach ($selectedOrgs as $org)
                                        <option value="{{ $org['id'] }}" {{ in_array((int) $org['id'], $selectedOrgIds, true) ? 'selected' : '' }}>{{ $org['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('org')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                @error('org.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <label for="time_allowed">Time Allowed (minutes)</label>
                                <input type="number" class="form-control" name="time_allowed" id="time_allowed" min="1" value="{{ old('time_allowed', $competition->time_allowed) }}">
                                @error('time_allowed')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <label for="last_date">Last Date</label>
                                <input type="date" class="form-control" name="last_date" id="last_date" value="{{ old('last_date', optional($competition->last_date)->format('Y-m-d')) }}">
                                @error('last_date')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <label for="has_entry_fee">Entry Fee Required?</label>
                                <select name="has_entry_fee" id="has_entry_fee" class="form-select">
                                    <option value="0" {{ old('has_entry_fee', $competition->has_entry_fee ?? 0) == 0 ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ old('has_entry_fee', $competition->has_entry_fee ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                                </select>
                                @error('has_entry_fee')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 col-xl-4" id="entry_fee_field" style="display: {{ old('has_entry_fee', $competition->has_entry_fee ?? 0) == 1 ? 'block' : 'none' }};">
                                <label for="entry_fee">Entry Fee Amount</label>
                                <input type="number" name="entry_fee" id="entry_fee" class="form-control" step="0.01" min="0" value="{{ old('entry_fee', $competition->entry_fee) }}">
                                @error('entry_fee')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <label for="competition_image">Competition Image</label>
                                <input type="file" class="form-control" name="competition_image" id="competition_image" accept="image/*">
                                @error('competition_image')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-12">
                                <label for="description">Description</label>
                                <textarea class="form-control" name="description" id="description" rows="4">{{ $competitionDescription }}</textarea>
                                @error('description')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <label>Select Genz</label>
                                <select class="form-select" id="genz" disabled>
                                    <option value="">Select</option>
                                    <option value="motherfits" {{ $competitionGenz === 'motherfits' ? 'selected' : '' }}>Mother Fit</option>
                                    <option value="fatherfits" {{ $competitionGenz === 'fatherfits' ? 'selected' : '' }}>Father Fit</option>
                                </select>
                                <input type="hidden" name="genz" id="genz_hidden" value="{{ $competitionGenz }}">
                                @error('genz')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="app-filter-card mt-4">
                            <div class="app-filter-header d-flex align-items-center justify-content-between gap-3">
                                <div>
                                    <p class="app-filter-title mb-1">Eligible Users</p>
                                    <div class="app-filter-subtitle">Live count updates as filters change.</div>
                                </div>
                                <div class="text-end">
                                    <div class="display-6 fw-bold text-primary mb-0" id="eligibleUsersCount">0</div>
                                    <small class="text-muted">available users</small>
                                </div>
                            </div>
                        </div>

                        <div class="app-table-card mt-4">
                            <div class="app-table-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <div>
                                    <p class="app-table-title mb-1">YouTube Video Links</p>
                                    <div class="app-table-subtitle">Optional video links for the competition.</div>
                                </div>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="add-link">Add link</button>
                            </div>
                            <div class="app-table-body">
                                <div id="youtube-links-wrapper">
                                    @foreach ($videoLinks as $link)
                                        <div class="d-flex gap-2 mb-2 youtube-link-row">
                                            <input type="url" class="form-control" name="youtube_links[]" value="{{ $link }}" placeholder="https://www.youtube.com/watch?v=...">
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-link">Remove</button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        @if ($isEdit)
                        <div class="app-table-card mt-4">
                            <div class="app-table-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <div>
                                    <p class="app-table-title mb-1">Competition Details</p>
                                    <div class="app-table-subtitle">Coach, city, venue, schedule and image per block.</div>
                                </div>
                                <button type="button" class="btn btn-primary btn-sm" id="add-competition">Add Detail Block</button>
                            </div>
                            <div class="app-table-body" id="competition-container">
                                @for ($index = 0; $index < $detailCount; $index++)
                                    @php
                                        $detail = $competition->details[$index] ?? null;
                                        $detailCoachId = old("coach_id.$index", $detail?->coach_id ?? '');
                                        $detailCityId = old("city_id.$index", $detail?->city_id ?? '');
                                        $detailVenueId = old("venue_id.$index", $detail?->venue_id ?? '');
                                        $detailStartDate = old("start_date.$index", $detail?->start_date ?? '');
                                        $detailEndDate = old("end_date.$index", $detail?->end_date ?? '');
                                        $detailStartTime = old("start_time.$index", $detail?->start_time ? \Carbon\Carbon::parse($detail->start_time)->format('H:i') : '');
                                        $detailEndTime = old("end_time.$index", $detail?->end_time ? \Carbon\Carbon::parse($detail->end_time)->format('H:i') : '');
                                        $detailId = old("detail_ids.$index", $detail?->id ?? '');
                                        $detailVenueName = $detail?->venueRelation?->name;
                                        $detailSelectedUserIds = collect(old("selected_user_ids.$index", $detail?->resolved_selected_user_ids ?? []))
                                            ->filter()
                                            ->map(fn ($value) => (int) $value)
                                            ->values()
                                            ->all();
                                        $detailSelectedUsers = \App\Models\User::query()
                                            ->whereIn('id', $detailSelectedUserIds)
                                            ->orderBy('name')
                                            ->get(['id', 'name']);
                                    @endphp
                                    <div class="competition-field border rounded-4 p-3 mb-3" data-detail-index="{{ $index }}">
                                        <input type="hidden" name="detail_ids[]" value="{{ $detailId }}">
                                        <div class="row g-3 align-items-start">
                                            <div class="col-md-6 col-xl-4">
                                                <label>Coach</label>
                                                <select class="form-select select2 js-detail-coach" name="coach_id[]">
                                                    <option value="">Select Coach</option>
                                                    @foreach ($coaches as $coach)
                                                        <option value="{{ $coach->id }}" {{ (string) $detailCoachId === (string) $coach->id ? 'selected' : '' }}>{{ $coach->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 col-xl-4">
                                                <label>City</label>
                                                <select class="form-select select2 js-detail-city" name="city_id[]">
                                                    <option value="">Select City</option>
                                                    @foreach ($cities as $city)
                                                        <option value="{{ $city->id }}" {{ (string) $detailCityId === (string) $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 col-xl-4">
                                                <label>Selected Users</label>
                                                <select class="form-select select2 js-detail-users" name="selected_user_ids[{{ $index }}][]" multiple data-selected-users='@json($detailSelectedUserIds)'>
                                                    @foreach ($detailSelectedUsers as $selectedUser)
                                                        <option value="{{ $selectedUser->id }}" selected>{{ $selectedUser->name }}</option>
                                                    @endforeach
                                                </select>
                                                <small class="text-muted">Only users from the selected city are shown.</small>
                                                @error("selected_user_ids.$index")<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-md-6 col-xl-4">
                                                <label>Venue</label>
                                                <select class="form-select select2 js-detail-venue" name="venue_id[]" data-selected-venue="{{ $detailVenueId }}">
                                                    <option value="">Select Venue</option>
                                                    @if ($detailVenueId && $detailVenueName)
                                                        <option value="{{ $detailVenueId }}" selected>{{ $detailVenueName }}</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-md-6 col-xl-3"><label>Start Date</label><input type="date" class="form-control" name="start_date[]" value="{{ $detailStartDate }}"></div>
                                            <div class="col-md-6 col-xl-3"><label>End Date</label><input type="date" class="form-control" name="end_date[]" value="{{ $detailEndDate }}"></div>
                                            <div class="col-md-6 col-xl-3"><label>Start Time</label><input type="time" class="form-control js-start-time" name="start_time[]" value="{{ $detailStartTime }}"></div>
                                            <div class="col-md-6 col-xl-3"><label>End Time</label><input type="time" class="form-control js-end-time" name="end_time[]" value="{{ $detailEndTime }}" readonly><small class="text-muted">Auto calculated</small></div>
                                            <div class="col-md-6 col-xl-4"><label>Image</label><input type="file" class="form-control" name="image[]" accept="image/*"></div>
                            <div class="col-md-12 d-flex justify-content-end"><button type="button" class="btn btn-outline-danger btn-sm remove-competition {{ $index === 0 ? 'd-none' : '' }}">Remove Competition</button></div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                        <template id="competition-detail-template">
                            <div class="competition-field border rounded-4 p-3 mb-3" data-detail-index="__INDEX__">
                                <input type="hidden" name="detail_ids[]" value="">
                                <div class="row g-3 align-items-start">
                                    <div class="col-md-6 col-xl-4">
                                        <label>Coach</label>
                                        <select class="form-select select2 js-detail-coach" name="coach_id[]">
                                            <option value="">Select Coach</option>
                                            @foreach ($coaches as $coach)
                                                <option value="{{ $coach->id }}">{{ $coach->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-xl-4">
                                        <label>City</label>
                                        <select class="form-select select2 js-detail-city" name="city_id[]">
                                            <option value="">Select City</option>
                                            @foreach ($cities as $city)
                                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-xl-4">
                                        <label>Selected Users</label>
                                        <select class="form-select select2 js-detail-users" name="selected_user_ids[__INDEX__][]" multiple data-selected-users="[]"></select>
                                        <small class="text-muted">Only users from the selected city are shown.</small>
                                    </div>
                                    <div class="col-md-6 col-xl-4">
                                        <label>Venue</label>
                                        <select class="form-select select2 js-detail-venue" name="venue_id[]">
                                            <option value="">Select Venue</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-xl-3"><label>Start Date</label><input type="date" class="form-control" name="start_date[]"></div>
                                    <div class="col-md-6 col-xl-3"><label>End Date</label><input type="date" class="form-control" name="end_date[]"></div>
                                    <div class="col-md-6 col-xl-3"><label>Start Time</label><input type="time" class="form-control js-start-time" name="start_time[]"></div>
                                    <div class="col-md-6 col-xl-3"><label>End Time</label><input type="time" class="form-control js-end-time" name="end_time[]" readonly><small class="text-muted">Auto calculated</small></div>
                                    <div class="col-md-6 col-xl-4"><label>Image</label><input type="file" class="form-control" name="image[]" accept="image/*"></div>
                                    <div class="col-md-12 d-flex justify-content-end"><button type="button" class="btn btn-outline-danger btn-sm remove-competition">Remove Competition</button></div>
                                </div>
                            </div>
                        </template>
                        @endif

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('competitions.index') }}" class="btn btn-outline-secondary btn-sm">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-sm" id="submitCompetition">{{ $isEdit ? 'Update' : 'Save' }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="{{ asset('assets/customjs/competition-form.js') }}"></script>
