@extends('layouts.app')
@section('title', 'Edit Set')
@section('content')
    <div class="px-4 pt-4 container-fluid" style="min-height: 82.5vh">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="p-4 rounded bg-light">
                    <div class="mb-4 d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">Edit Set</h6>
                        <a href="{{ route('sets.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                    <form action="{{ route('sets.update', $set->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $set->name) }}" required>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            {{-- @dd($set->genz); --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="genz" class="form-label">Genz</label>
                                    <select class="form-control" id="genz" name="genz" required>
                                        <option value="">Select Genz</option>
                                        <option value="fatherfits" {{ $set->genz == 'fatherfits' ? 'selected' : '' }}>Father Fit</option>
                                        <option value="motherfits" {{ $set->genz == 'motherfits' ? 'selected' : '' }}>Mother Fit</option>
                                    </select>
                                    @error('genz')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Exercises (Drag to reorder)</label>
                            <div id="exercises-container" class="border p-3 rounded">
                                <div class="text-muted">Loading exercises...</div>
                            </div>
                            <div id="selected-exercises" class="mt-3">
                                <h6>Selected Exercises (in order):</h6>
                                <div id="sortable-exercises" class="list-group">
                                    <!-- Selected exercises will appear here -->
                                </div>
                            </div>
                            @error('exercises')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Update Set</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const genzSelect = document.getElementById('genz');
            const exercisesContainer = document.getElementById('exercises-container');
            const setExercises = @json($set->exercises->sortBy('pivot.sequence')->values());
            let selectedExercises = setExercises.map(ex => ({id: ex.id, name: ex.name}));

            function loadExercises(genz) {
                if (genz) {
                    fetch(`/set/exercises-by-genz?genz=${genz}`)
                        .then(response => response.json())
                        .then(data => {
                            exercisesContainer.innerHTML = '';
                            if (data.length === 0) {
                                exercisesContainer.innerHTML = '<div class="text-muted">No exercises found for this genz.</div>';
                                return;
                            }
                            data.forEach(exercise => {
                                const div = document.createElement('div');
                                div.className = 'form-check mb-2';

                                const checkbox = document.createElement('input');
                                checkbox.type = 'checkbox';
                                checkbox.className = 'form-check-input';
                                checkbox.value = exercise.id;
                                checkbox.id = `exercise_${exercise.id}`;
                                checkbox.checked = selectedExercises.some(e => e.id == exercise.id);
                                checkbox.addEventListener('change', function() {
                                    if (this.checked) {
                                        selectedExercises.push({id: exercise.id, name: exercise.name});
                                    } else {
                                        selectedExercises = selectedExercises.filter(e => e.id != exercise.id);
                                    }
                                    updateSelectedExercises();
                                });

                                const label = document.createElement('label');
                                label.className = 'form-check-label';
                                label.htmlFor = `exercise_${exercise.id}`;
                                label.textContent = exercise.name;

                                div.appendChild(checkbox);
                                div.appendChild(label);
                                exercisesContainer.appendChild(div);
                            });
                        })
                        .catch(error => {
                            console.error('Error loading exercises:', error);
                            exercisesContainer.innerHTML = '<div class="text-danger">Error loading exercises.</div>';
                        });
                } else {
                    exercisesContainer.innerHTML = '<div class="text-muted">Please select a genz first.</div>';
                }
            }

            function updateSelectedExercises() {
                const container = document.getElementById('sortable-exercises');
                container.innerHTML = '';
                
                selectedExercises.forEach((exercise, index) => {
                    const item = document.createElement('div');
                    item.className = 'list-group-item d-flex justify-content-between align-items-center';
                    item.dataset.exerciseId = exercise.id;
                    item.innerHTML = `
                        <span><strong>${index + 1}.</strong> ${exercise.name}</span>
                        <input type="hidden" name="exercise_ids[]" value="${exercise.id}">
                        <input type="hidden" name="sequences[]" value="${index + 1}">
                        <span class="badge bg-primary rounded-pill">Drag to reorder</span>
                    `;
                    container.appendChild(item);
                });
                
                if (selectedExercises.length > 0) {
                    new Sortable(container, {
                        animation: 150,
                        onEnd: function() {
                            updateSequenceInputs();
                        }
                    });
                }
            }
            
            function updateSequenceInputs() {
                const items = document.querySelectorAll('#sortable-exercises .list-group-item');
                const exerciseIds = [];
                const sequences = [];
                
                items.forEach((item, index) => {
                    const exerciseId = item.dataset.exerciseId;
                    exerciseIds.push(exerciseId);
                    sequences.push(index + 1);
                    item.querySelector('span strong').textContent = `${index + 1}.`;
                });
                
                const container = document.getElementById('sortable-exercises');
                container.querySelectorAll('input[name="exercise_ids[]"]').forEach((input, index) => {
                    input.value = exerciseIds[index];
                });
                container.querySelectorAll('input[name="sequences[]"]').forEach((input, index) => {
                    input.value = sequences[index];
                });
            }

            // Load exercises on page load
            if (genzSelect.value) {
                loadExercises(genzSelect.value);
                updateSelectedExercises();
            }

            genzSelect.addEventListener('change', function() {
                selectedExercises = [];
                loadExercises(this.value);
                updateSelectedExercises();
            });
        });
    </script>
@endsection
