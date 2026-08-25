@extends('layouts.app')
@section('title', 'Create Sub-Group for ' . $group->name)
@section('content')
    <div class="container-fluid pt-4 px-4" style="min-height: 82.5vh">
        <form action="{{ route('exercise-sub-groups.store') }}" method="POST" enctype="multipart/form-data" id="subGroupForm">
            @csrf
            <input type="hidden" name="exercise_group_id" value="{{ $group->id }}">

            <div class="row g-4">
                <!-- Sub-Group Info Card -->
                <div class="col-lg-4">
                    <div class="bg-light rounded p-4 h-100 shadow-sm">
                        <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-2">
                            <div>
                                <h5 class="mb-0 fw-bold">Sub-Group Info</h5>
                                <span class="badge bg-primary text-white mt-1">{{ $group->name }}</span>
                            </div>
                            <a href="{{ route('exercise-groups.show', $group->id) }}" class="btn btn-outline-secondary btn-sm"><i class="fa fa-arrow-left me-1"></i>Back</a>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Sub-Group Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. Batsman, Bowler, Forward, Defender" required>
                            @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="sub_title" class="form-label fw-bold">Subtitle / Tagline</label>
                            <input type="text" name="sub_title" id="sub_title" class="form-control" value="{{ old('sub_title') }}" placeholder="e.g. Batting Specific Drills & Agility">
                            @error('sub_title')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label fw-bold">Sub-Group Image / Icon</label>
                            <input type="file" name="image" id="image" class="form-control" accept="image/*">
                            @error('image')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label fw-bold">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="mt-4 pt-3 border-top d-grid">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save me-2"></i>Save Sub-Group & Exercises</button>
                        </div>
                    </div>
                </div>

                <!-- Exercise Selection & Ordering Card -->
                <div class="col-lg-8">
                    <div class="bg-light rounded p-4 h-100 shadow-sm">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3 border-bottom pb-2">
                            <div>
                                <h5 class="mb-0 fw-bold">Assign & Order Exercises for this Sub-Group</h5>
                                <small class="text-muted">Select exercises from the left catalog and organize their sequence on the right.</small>
                            </div>
                            <span class="badge bg-primary fs-6" id="selectedCountBadge">0 exercises selected</span>
                        </div>

                        @error('exercise_ids')
                            <div class="alert alert-danger py-2 small">{{ $message }}</div>
                        @enderror

                        <div class="row g-3">
                            <!-- Left: Exercise Catalog Checkboxes -->
                            <div class="col-md-7">
                                <div class="card border-0 shadow-none bg-white p-3 rounded h-100">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <h6 class="fw-bold mb-0 text-dark">Available Exercises</h6>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-primary btn-sm" id="checkAllBtn">Select All</button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" id="uncheckAllBtn">Clear All</button>
                                        </div>
                                    </div>

                                    <div class="input-group input-group-sm mb-3">
                                        <span class="input-group-text bg-light"><i class="fa fa-search text-muted"></i></span>
                                        <input type="text" id="searchExerciseInput" class="form-control" placeholder="Search exercises...">
                                    </div>

                                    <div class="exercise-picker-container" style="max-height: 480px; overflow-y: auto;">
                                        @foreach($categories as $cat)
                                            @if($cat->exercises->count() > 0)
                                                <div class="category-block mb-3 bg-light border rounded p-2">
                                                    <div class="d-flex align-items-center justify-content-between mb-2 border-bottom pb-1">
                                                        <span class="fw-bold small text-primary"><i class="fa fa-tag me-1"></i>{{ $cat->name }} ({{ $cat->exercises->count() }})</span>
                                                        <a href="javascript:void(0)" class="small text-decoration-none select-category-btn" data-cat-id="{{ $cat->id }}">Toggle</a>
                                                    </div>
                                                    <div class="row g-2">
                                                        @foreach($cat->exercises as $ex)
                                                            <div class="col-12 exercise-item-col" data-name="{{ strtolower($ex->name) }}">
                                                                <label class="d-flex align-items-center gap-2 p-2 border rounded cursor-pointer bg-white mb-0 w-100" style="cursor: pointer;">
                                                                    <input type="checkbox"
                                                                        data-id="{{ $ex->id }}"
                                                                        data-name="{{ $ex->name }}"
                                                                        data-category="{{ $cat->name }}"
                                                                        data-level="{{ $ex->fitness_level ?? 'Level' }}"
                                                                        data-type="{{ $ex->exercise_type ?? 'count' }}"
                                                                        data-image="{{ $ex->image ? asset('storage/' . $ex->image) : asset('assets/images/user.jpg') }}"
                                                                        class="form-check-input exercise-checkbox cat-{{ $cat->id }}">
                                                                    <img src="{{ $ex->image ? asset('storage/' . $ex->image) : asset('assets/images/user.jpg') }}" alt="" class="rounded" width="32" height="32" style="object-fit: cover;">
                                                                    <div class="text-truncate flex-grow-1">
                                                                        <div class="fw-bold small text-dark text-truncate">{{ $ex->name }}</div>
                                                                        <div class="text-muted" style="font-size: 11px;">
                                                                            {{ $ex->fitness_level ?? 'Level' }} &bull; {{ $ex->exercise_type ?? 'count' }}
                                                                        </div>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach

                                        @if($uncategorizedExercises->count() > 0)
                                            <div class="category-block mb-3 bg-light border rounded p-2">
                                                <div class="d-flex align-items-center justify-content-between mb-2 border-bottom pb-1">
                                                    <span class="fw-bold small text-secondary"><i class="fa fa-tag me-1"></i>Uncategorized ({{ $uncategorizedExercises->count() }})</span>
                                                    <a href="javascript:void(0)" class="small text-decoration-none select-category-btn" data-cat-id="uncat">Toggle</a>
                                                </div>
                                                <div class="row g-2">
                                                    @foreach($uncategorizedExercises as $ex)
                                                        <div class="col-12 exercise-item-col" data-name="{{ strtolower($ex->name) }}">
                                                            <label class="d-flex align-items-center gap-2 p-2 border rounded cursor-pointer bg-white mb-0 w-100" style="cursor: pointer;">
                                                                <input type="checkbox"
                                                                    data-id="{{ $ex->id }}"
                                                                    data-name="{{ $ex->name }}"
                                                                    data-category="Uncategorized"
                                                                    data-level="{{ $ex->fitness_level ?? 'Level' }}"
                                                                    data-type="{{ $ex->exercise_type ?? 'count' }}"
                                                                    data-image="{{ $ex->image ? asset('storage/' . $ex->image) : asset('assets/images/user.jpg') }}"
                                                                    class="form-check-input exercise-checkbox cat-uncat">
                                                                <img src="{{ $ex->image ? asset('storage/' . $ex->image) : asset('assets/images/user.jpg') }}" alt="" class="rounded" width="32" height="32" style="object-fit: cover;">
                                                                <div class="text-truncate flex-grow-1">
                                                                    <div class="fw-bold small text-dark text-truncate">{{ $ex->name }}</div>
                                                                    <div class="text-muted" style="font-size: 11px;">
                                                                        {{ $ex->fitness_level ?? 'Level' }} &bull; {{ $ex->exercise_type ?? 'count' }}
                                                                    </div>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Preserved Order List -->
                            <div class="col-md-5">
                                <div class="card border-0 shadow-none bg-white p-3 rounded h-100">
                                    <div class="d-flex align-items-center justify-content-between mb-2 border-bottom pb-2">
                                        <h6 class="fw-bold mb-0 text-success"><i class="fa fa-sort-numeric-down me-1"></i>Selected Order</h6>
                                        <small class="text-muted">Use ▲ ▼ to reorder</small>
                                    </div>

                                    <div id="selectedOrderContainer" style="max-height: 480px; overflow-y: auto;">
                                        <div id="noSelectedMessage" class="text-center text-muted py-5">
                                            <i class="fa fa-list-ol fa-2x mb-2 text-muted"></i>
                                            <p class="small mb-0">No exercises selected yet.<br>Check exercises on the left to add them here in order.</p>
                                        </div>
                                        <ul class="list-group list-group-flush" id="selectedOrderList">
                                            <!-- Dynamically appended ordered items -->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function () {
            function updateOrderIndices() {
                const items = $('#selectedOrderList .order-item');
                if (items.length === 0) {
                    $('#noSelectedMessage').show();
                } else {
                    $('#noSelectedMessage').hide();
                }

                items.each(function (index) {
                    $(this).find('.order-number').text('#' + (index + 1));
                });

                $('#selectedCountBadge').text(items.length + ' exercises selected');
            }

            function addExerciseToOrderList(data) {
                if ($(`#order-item-${data.id}`).length > 0) return;

                const itemHtml = `
                    <li class="list-group-item d-flex align-items-center justify-content-between p-2 border rounded mb-2 bg-light order-item" id="order-item-${data.id}" data-id="${data.id}">
                        <input type="hidden" name="exercise_ids[]" value="${data.id}">
                        <div class="d-flex align-items-center gap-2 text-truncate me-2">
                            <span class="badge bg-primary rounded-pill order-number" style="min-width: 28px;">#1</span>
                            <img src="${data.image}" alt="" class="rounded" width="28" height="28" style="object-fit: cover;">
                            <div class="text-truncate">
                                <div class="fw-bold small text-dark text-truncate" style="max-width: 140px;" title="${data.name}">${data.name}</div>
                                <span class="text-muted" style="font-size: 10px;">${data.category}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <button type="button" class="btn btn-outline-secondary btn-sm p-1 move-up-btn" title="Move Up" style="line-height: 1;"><i class="fa fa-arrow-up" style="font-size: 10px;"></i></button>
                            <button type="button" class="btn btn-outline-secondary btn-sm p-1 move-down-btn" title="Move Down" style="line-height: 1;"><i class="fa fa-arrow-down" style="font-size: 10px;"></i></button>
                            <button type="button" class="btn btn-outline-danger btn-sm p-1 remove-order-item" title="Remove" style="line-height: 1;"><i class="fa fa-times" style="font-size: 10px;"></i></button>
                        </div>
                    </li>
                `;

                $('#selectedOrderList').append(itemHtml);
                updateOrderIndices();
            }

            function removeExerciseFromOrderList(id) {
                $(`#order-item-${id}`).remove();
                updateOrderIndices();
            }

            // Checkbox change listener
            $(document).on('change', '.exercise-checkbox', function () {
                const id = $(this).data('id');
                if ($(this).is(':checked')) {
                    const data = {
                        id: id,
                        name: $(this).data('name'),
                        category: $(this).data('category'),
                        level: $(this).data('level'),
                        type: $(this).data('type'),
                        image: $(this).data('image')
                    };
                    addExerciseToOrderList(data);
                } else {
                    removeExerciseFromOrderList(id);
                }
            });

            // Remove button from ordered list
            $(document).on('click', '.remove-order-item', function () {
                const li = $(this).closest('.order-item');
                const id = li.data('id');
                $(`.exercise-checkbox[data-id="${id}"]`).prop('checked', false);
                li.remove();
                updateOrderIndices();
            });

            // Move Up
            $(document).on('click', '.move-up-btn', function () {
                const li = $(this).closest('.order-item');
                const prev = li.prev('.order-item');
                if (prev.length > 0) {
                    li.insertBefore(prev);
                    updateOrderIndices();
                }
            });

            // Move Down
            $(document).on('click', '.move-down-btn', function () {
                const li = $(this).closest('.order-item');
                const next = li.next('.order-item');
                if (next.length > 0) {
                    li.insertAfter(next);
                    updateOrderIndices();
                }
            });

            // Search filter
            $('#searchExerciseInput').on('input', function () {
                const query = $(this).val().toLowerCase().trim();
                $('.exercise-item-col').each(function () {
                    const name = $(this).data('name');
                    if (name.includes(query)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });

                $('.category-block').each(function () {
                    const visibleChildren = $(this).find('.exercise-item-col:visible').length;
                    $(this).toggle(visibleChildren > 0);
                });
            });

            // Select all visible
            $('#checkAllBtn').on('click', function () {
                $('.exercise-item-col:visible .exercise-checkbox').each(function () {
                    if (!$(this).is(':checked')) {
                        $(this).prop('checked', true).trigger('change');
                    }
                });
            });

            // Clear all
            $('#uncheckAllBtn').on('click', function () {
                $('.exercise-checkbox').prop('checked', false);
                $('#selectedOrderList').html('');
                updateOrderIndices();
            });

            // Toggle per category
            $('.select-category-btn').on('click', function () {
                const catId = $(this).data('cat-id');
                const checkboxes = $(`.cat-${catId}`);
                const allChecked = checkboxes.length === checkboxes.filter(':checked').length;
                checkboxes.each(function () {
                    $(this).prop('checked', !allChecked).trigger('change');
                });
            });
        });
    </script>
@endsection
