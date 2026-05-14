(function ($) {
    "use strict";

    const config = window.competitionFormConfig || {};
    const countUrl = config.countUrl || window.competitionCountUrl || null;
    const orgUrl = config.orgUrl || window.competitionOrganizationsUrl || null;
    const countryUrl = config.countryUrl || window.competitionCountriesUrl || null;
    const venueUrlBase = config.venueUrlBase || window.competitionVenuesUrlBase || null;
    const acceptedUsersByCityUrlBase = config.acceptedUsersByCityUrlBase || null;
    const coachOptions = Array.isArray(window.competitionCoachOptions) ? window.competitionCoachOptions : [];
    const cityOptions = Array.isArray(window.competitionCityOptions) ? window.competitionCityOptions : [];

    const form = document.getElementById('competitionForm');
    const competitionContainer = document.getElementById('competition-container');
    const ageGroupInput = document.getElementById('age_group');
    const countrySelect = document.getElementById('country');
    const orgTypeSelect = document.getElementById('org_type');
    const orgSelect = document.getElementById('org');
    const genzHidden = document.getElementById('genz_hidden');
    const genzSelect = document.getElementById('genz');
    const eligibleUsersCountEl = document.getElementById('eligibleUsersCount');
    const detailTemplate = document.getElementById('competition-detail-template');

    let countTimer = null;

    function readSelectValue(selectEl) {
        if (!selectEl) {
            return '';
        }

        if (window.jQuery && $(selectEl).length) {
            const value = $(selectEl).val();
            if (Array.isArray(value)) {
                return value[0] || '';
            }
            return value || '';
        }

        return selectEl.value || '';
    }

    function readSelectValues(selectEl) {
        if (!selectEl) {
            return [];
        }

        if (window.jQuery && $(selectEl).length) {
            const value = $(selectEl).val();
            if (Array.isArray(value)) {
                return value.filter(Boolean);
            }
            return value ? [value] : [];
        }

        if (selectEl.multiple) {
            return Array.from(selectEl.selectedOptions || []).map((option) => option.value).filter(Boolean);
        }

        return selectEl.value ? [selectEl.value] : [];
    }

    function selectedOrgTypes() {
        if (!orgTypeSelect) {
            return [];
        }

        if (window.jQuery && $(orgTypeSelect).length) {
            const values = $(orgTypeSelect).val();
            if (Array.isArray(values)) {
                return values.filter(Boolean);
            }
        }

        return Array.from(orgTypeSelect.selectedOptions || []).map((option) => option.value).filter(Boolean);
    }

    function parseAgeGroup(value) {
        const text = String(value || "").trim();
        if (!text) {
            return null;
        }

        const parts = text.split("-").map((part) => part.trim()).filter(Boolean);
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

    function inferGenz(value) {
        const parsed = parseAgeGroup(value);
        if (!parsed) {
            return "";
        }

        return parsed.max < 14 ? "motherfits" : "fatherfits";
    }

    function syncGenz() {
        const genz = inferGenz(ageGroupInput ? ageGroupInput.value : "");
        if (genzHidden) {
            genzHidden.value = genz;
        }
        if (genzSelect) {
            genzSelect.value = genz;
        }
        return genz;
    }

    function initSelect2(scope) {
        if (!$.fn.select2) {
            return;
        }

        $(scope).find('.select2').each(function () {
            const $el = $(this);

            if ($el.data('select2')) {
                $el.select2('destroy');
            }

            const select2Options = {
                theme: 'bootstrap-5',
                width: '100%',
                dropdownParent: $el.closest('.competition-field, .app-filter-card, form'),
            };

            if ($el.hasClass('js-detail-users')) {
                select2Options.placeholder = 'Select users';
                select2Options.closeOnSelect = false;
            }

            $el.select2(select2Options);
        });
    }

    function scheduleCountUpdate(delay = 450) {
        clearTimeout(countTimer);
        countTimer = setTimeout(updateEligibleCount, delay);
    }

    function updateEligibleCount() {
        if (!eligibleUsersCountEl || !countUrl) {
            return;
        }

        const params = new URLSearchParams();
        const ageGroup = ageGroupInput ? ageGroupInput.value.trim() : "";
        const country = String(readSelectValue(countrySelect) || '').trim();
        const orgs = readSelectValues(orgSelect).map((value) => String(value).trim()).filter(Boolean);
        const genz = genzHidden ? genzHidden.value.trim() : "";

        if (ageGroup) {
            params.set('age_group', ageGroup);
        }
        if (country) {
            params.set('country', country);
        }
        orgs.forEach((value) => params.append('orgs[]', value));
        if (genz) {
            params.set('genz', genz);
        }

        selectedOrgTypes().forEach((value) => params.append('org_types[]', value));

        eligibleUsersCountEl.textContent = '...';

        fetch(`${countUrl}?${params.toString()}`, {
            headers: { Accept: 'application/json' },
        })
            .then((response) => response.json().then((payload) => ({ ok: response.ok, payload })))
            .then(({ ok, payload }) => {
                if (!ok) {
                    throw new Error(payload?.message || 'Unable to fetch count');
                }

                eligibleUsersCountEl.textContent = Number(payload.count || 0).toLocaleString();
                if (payload.genz) {
                    if (genzHidden) {
                        genzHidden.value = payload.genz;
                    }
                    if (genzSelect) {
                        genzSelect.value = payload.genz;
                    }
                }
            })
            .catch(() => {
                eligibleUsersCountEl.textContent = '0';
            });
    }

    function initOrganizationSelect() {
        if (!orgSelect || !$.fn.select2 || !orgUrl) {
            return;
        }

        if ($(orgSelect).data('select2')) {
            $(orgSelect).select2('destroy');
        }

        $(orgSelect).select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Select Organization',
            allowClear: true,
            closeOnSelect: false,
            dropdownParent: $(orgSelect).closest('.competition-field, .app-filter-card, form'),
            ajax: {
                delay: 200,
                transport: function (params, success, failure) {
                    const term = params.data.term || '';
                    const orgTypes = selectedOrgTypes();

                    if (!orgTypes.length) {
                        success({ results: [] });
                        return;
                    }

                    const endpoint = new URL(orgUrl, window.location.origin);
                    if (term) {
                        endpoint.searchParams.set('term', term);
                    }
                    orgTypes.forEach((value) => endpoint.searchParams.append('org_types[]', value));

                    fetch(endpoint.toString(), {
                        headers: { Accept: 'application/json' },
                    })
                        .then((response) => response.json())
                        .then((payload) => {
                            const results = (payload.results || payload || []).map((item) => ({
                                id: item.id,
                                text: item.text || item.name,
                            }));
                            success({ results });
                        })
                        .catch((error) => {
                            failure(error);
                        });

                    return {};
                },
                processResults: function (data) {
                    return data;
                },
                cache: true,
            },
            minimumInputLength: 0,
        });

        $(orgSelect).off('.countHook').on('change.countHook select2:select.countHook select2:unselect.countHook', function () {
            clearInvalidOrgSelection();
            scheduleCountUpdate(250);
        });
    }

    function initCountrySelect() {
        if (!countrySelect || !$.fn.select2 || !countryUrl) {
            return;
        }

        const selectedCountryId = countrySelect.dataset.selectedCountry || '';
        const selectedCountryName = countrySelect.dataset.selectedCountryName || '';
        const optionExists = Array.from(countrySelect.options).some((option) => String(option.value) === String(selectedCountryId));

        if (selectedCountryId && selectedCountryName && !optionExists) {
            const option = document.createElement('option');
            option.value = selectedCountryId;
            option.textContent = selectedCountryName;
            option.selected = true;
            countrySelect.appendChild(option);
        }

        if ($(countrySelect).data('select2')) {
            $(countrySelect).select2('destroy');
        }

        $(countrySelect).select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Select Country',
            allowClear: true,
            ajax: {
                delay: 200,
                transport: function (params, success, failure) {
                    const endpoint = new URL(countryUrl, window.location.origin);
                    const term = params.data.term || '';
                    if (term) {
                        endpoint.searchParams.set('term', term);
                    }

                    fetch(endpoint.toString(), {
                        headers: { Accept: 'application/json' },
                    })
                        .then((response) => response.json())
                        .then((payload) => {
                            const results = (payload.results || payload || []).map((item) => ({
                                id: item.id,
                                text: item.text || item.name,
                            }));
                            success({ results });
                        })
                        .catch((error) => failure(error));

                    return {};
                },
                processResults: function (data) {
                    return data;
                },
                cache: true,
            },
            minimumInputLength: 0,
        });

        $(countrySelect).off('.countHook').on('change.countHook select2:select.countHook select2:unselect.countHook', function () {
            scheduleCountUpdate(250);
        });
    }

    function loadVenues(block) {
        if (!venueUrlBase || !block) {
            return;
        }

        const citySelect = block.querySelector('.js-detail-city');
        const venueSelect = block.querySelector('.js-detail-venue');
        if (!citySelect || !venueSelect) {
            return;
        }

        const cityId = citySelect.value;
        const selectedVenueId = venueSelect.dataset.selectedVenue || venueSelect.value || '';

        venueSelect.innerHTML = '<option value="">Select Venue</option>';
        if (!cityId) {
            $(venueSelect).trigger('change.select2');
            return;
        }

        fetch(`${venueUrlBase}/${cityId}`, {
            headers: { Accept: 'application/json' },
        })
            .then((response) => response.json())
            .then((items) => {
                items.forEach((venue) => {
                    const option = document.createElement('option');
                    option.value = venue.id;
                    option.textContent = venue.name;
                    if (String(selectedVenueId) === String(venue.id)) {
                        option.selected = true;
                    }
                    venueSelect.appendChild(option);
                });
                $(venueSelect).trigger('change');
                $(venueSelect).trigger('change.select2');
            })
            .catch(() => {
                venueSelect.innerHTML = '<option value="">Select Venue</option>';
                $(venueSelect).trigger('change.select2');
            });
    }

    function loadAcceptedUsersByCity(block, clearCurrent = false) {
        if (!block) {
            return;
        }

        const citySelect = block.querySelector('.js-detail-city');
        const usersSelect = block.querySelector('.js-detail-users');
        if (!citySelect || !usersSelect) {
            return;
        }

        if (clearCurrent) {
            usersSelect.innerHTML = '';
            $(usersSelect).val(null).trigger('change.select2');
        }

        const cityId = citySelect.value;
        const selectedUsers = (() => {
            try {
                return JSON.parse(usersSelect.dataset.selectedUsers || '[]');
            } catch (error) {
                return [];
            }
        })();

        usersSelect.disabled = true;
        if (!acceptedUsersByCityUrlBase || !cityId) {
            usersSelect.innerHTML = '';
            usersSelect.disabled = false;
            $(usersSelect).trigger('change.select2');
            return;
        }

        usersSelect.innerHTML = '';
        const loadingOption = document.createElement('option');
        loadingOption.value = '';
        loadingOption.textContent = 'Loading users...';
        usersSelect.appendChild(loadingOption);
        $(usersSelect).trigger('change.select2');

        fetch(`${acceptedUsersByCityUrlBase}/${cityId}`, {
            headers: { Accept: 'application/json' },
        })
            .then((response) => response.json())
            .then((items) => {
                usersSelect.innerHTML = '';
                items.forEach((user) => {
                    const option = document.createElement('option');
                    option.value = user.id;
                    option.textContent = user.name;
                    if (!clearCurrent && selectedUsers.includes(Number(user.id))) {
                        option.selected = true;
                    }
                    usersSelect.appendChild(option);
                });
                usersSelect.dataset.selectedUsers = '[]';
                usersSelect.disabled = false;
                $(usersSelect).trigger('change');
                $(usersSelect).trigger('change.select2');
            })
            .catch(() => {
                usersSelect.innerHTML = '';
                usersSelect.disabled = false;
                $(usersSelect).trigger('change.select2');
            });
    }

    function calculateEndTime(startTimeInput) {
        const timeAllowed = parseInt(document.getElementById('time_allowed')?.value || '0', 10);
        const endTimeInput = startTimeInput.closest('.competition-field')?.querySelector('.js-end-time');

        if (!endTimeInput) {
            return;
        }

        if (!startTimeInput.value || !Number.isFinite(timeAllowed) || timeAllowed <= 0) {
            endTimeInput.value = '';
            return;
        }

        const [hours, minutes] = startTimeInput.value.split(':').map(Number);
        if (!Number.isFinite(hours) || !Number.isFinite(minutes)) {
            endTimeInput.value = '';
            return;
        }

        const start = new Date();
        start.setHours(hours, minutes, 0, 0);
        start.setMinutes(start.getMinutes() + timeAllowed);

        const hh = String(start.getHours()).padStart(2, '0');
        const mm = String(start.getMinutes()).padStart(2, '0');
        endTimeInput.value = `${hh}:${mm}`;
    }

    function updateRemoveButtons() {
        if (!competitionContainer) {
            return;
        }

        const blocks = competitionContainer.querySelectorAll('.competition-field');
        blocks.forEach((block, index) => {
            const button = block.querySelector('.remove-competition');
            if (button) {
                button.classList.toggle('d-none', blocks.length === 1 || index === 0);
            }
        });
    }

    function buildDetailBlock() {
        if (!detailTemplate) {
            return null;
        }

        const node = detailTemplate.content.firstElementChild.cloneNode(true);
        const nextIndex = competitionContainer ? competitionContainer.querySelectorAll('.competition-field').length : 0;
        node.dataset.detailIndex = String(nextIndex);
        node.innerHTML = node.innerHTML.replace(/__INDEX__/g, String(nextIndex));
        node.querySelectorAll('input, textarea').forEach((field) => {
            if (field.type === 'file') {
                return;
            }
            if (field.type === 'hidden' || field.type === 'date' || field.type === 'time' || field.tagName === 'TEXTAREA') {
                field.value = '';
            }
        });

        node.querySelectorAll('select').forEach((field) => {
            field.selectedIndex = 0;
            if (field.classList.contains('js-detail-venue')) {
                field.dataset.selectedVenue = '';
            }
            if (field.classList.contains('js-detail-users')) {
                field.dataset.selectedUsers = '[]';
            }
        });

        return node;
    }

    function addDetailBlock() {
        if (!competitionContainer) {
            return;
        }

        const block = buildDetailBlock();
        if (!block) {
            return;
        }

        competitionContainer.appendChild(block);
        initSelect2(block);

        updateRemoveButtons();
        scheduleCountUpdate();
    }

    function clearInvalidOrgSelection() {
        if (!orgSelect) {
            return;
        }

        const selected = readSelectValues(orgSelect).map((value) => String(value));
        if (!selected.length) {
            return;
        }

        const existing = new Set(Array.from(orgSelect.options).map((option) => String(option.value)));
        const validSelected = selected.filter((value) => existing.has(String(value)));

        if (validSelected.length !== selected.length) {
            $(orgSelect).val(validSelected).trigger('change.select2');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        initSelect2(document);
        syncGenz();
        updateRemoveButtons();

        if (orgTypeSelect) {
            $(orgTypeSelect).off('.countHook').on('change.countHook select2:select.countHook select2:unselect.countHook', function () {
                if (orgSelect) {
                    $(orgSelect).val(null).trigger('change');
                }
                scheduleCountUpdate(250);
            });
        }

        if (countrySelect && (!window.jQuery || !$.fn.select2)) {
            countrySelect.addEventListener('change', function () {
                scheduleCountUpdate(250);
            });
        }

        if (orgSelect && (!window.jQuery || !$.fn.select2)) {
            orgSelect.addEventListener('change', function () {
                clearInvalidOrgSelection();
                scheduleCountUpdate(250);
            });
        }

        if (ageGroupInput) {
            ageGroupInput.addEventListener('input', function () {
                syncGenz();
                scheduleCountUpdate(700);
            });
        }

        document.getElementById('time_allowed')?.addEventListener('input', function () {
            competitionContainer?.querySelectorAll('.js-start-time').forEach((input) => {
                if (input.value) {
                    calculateEndTime(input);
                }
            });
        });

        document.getElementById('add-link')?.addEventListener('click', function () {
            const wrapper = document.getElementById('youtube-links-wrapper');
            if (!wrapper) {
                return;
            }

            const row = document.createElement('div');
            row.className = 'd-flex gap-2 mb-2 youtube-link-row';
            row.innerHTML = '<input type="url" class="form-control" name="youtube_links[]" placeholder="https://www.youtube.com/watch?v=..."><button type="button" class="btn btn-outline-danger btn-sm remove-link">Remove</button>';
            wrapper.appendChild(row);
        });

        document.getElementById('youtube-links-wrapper')?.addEventListener('click', function (event) {
            if (event.target.classList.contains('remove-link')) {
                event.target.closest('.youtube-link-row')?.remove();
            }
        });

        document.getElementById('add-competition')?.addEventListener('click', addDetailBlock);

        competitionContainer?.addEventListener('click', function (event) {
            if (event.target.classList.contains('remove-competition')) {
                event.target.closest('.competition-field')?.remove();
                updateRemoveButtons();
                scheduleCountUpdate();
            }
        });

        if (competitionContainer) {
            $(competitionContainer).on('change select2:select', '.js-detail-city', function () {
                const block = this.closest('.competition-field');
                const venueSelect = block?.querySelector('.js-detail-venue');
                const usersSelect = block?.querySelector('.js-detail-users');
                if (venueSelect) {
                    venueSelect.dataset.selectedVenue = '';
                }
                if (usersSelect) {
                    usersSelect.dataset.selectedUsers = '[]';
                }
                loadVenues(block);
                loadAcceptedUsersByCity(block, true);
            });
        }

        competitionContainer?.addEventListener('input', function (event) {
            if (event.target.classList.contains('js-start-time')) {
                calculateEndTime(event.target);
            }
        });

        competitionContainer?.querySelectorAll('.competition-field').forEach((block) => {
            if (block.querySelector('.js-detail-city')?.value) {
                loadVenues(block);
                loadAcceptedUsersByCity(block);
            }

            const startTimeInput = block.querySelector('.js-start-time');
            if (startTimeInput?.value) {
                calculateEndTime(startTimeInput);
            }
        });

        initCountrySelect();
        initOrganizationSelect();
        updateEligibleCount();
    });

    document.getElementById('has_entry_fee')?.addEventListener('change', function () {
        const entryFeeField = document.getElementById('entry_fee_field');
        const entryFeeInput = document.getElementById('entry_fee');

        if (!entryFeeField) {
            return;
        }

        if (this.value === '1') {
            entryFeeField.style.display = 'block';
        } else {
            entryFeeField.style.display = 'none';
            if (entryFeeInput) {
                entryFeeInput.value = '';
            }
        }
    });
})(jQuery);
