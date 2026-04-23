@extends('layouts.app')
@section('title', 'Competitions')
@section('content')
    <div class="px-4 pt-4 container-fluid" style="min-height: 82.5vh">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="p-4 text-center rounded bg-light">
                    <div class="mb-4 d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">Competition Details</h6>
                    </div>
                    <form action="{{ route('competition-users.update', $competitionUser->id) }}" method="POST"
                        id="competitionForm">
                        @csrf
                        @method('PUT')

                        @foreach ($exercises as $exercise)
                            <div class="mb-3 border p-3 rounded">
                                <h5>{{ $exercise->name }}</h5>
                                <label for="score_{{ $exercise->id }}" class="form-label">Score</label>
                                <input type="number" step="0.01" name="scores[{{ $exercise->id }}]"
                                    class="form-control score-input" value="{{ $results[$exercise->id]->score ?? '' }}"
                                    required min="0">
                                <div class="text-danger error-message" id="score_{{ $exercise->id }}-error"></div>

                                {{-- YouTube Links per exercise --}}
                                <div class="mt-3">
                                    <label class="form-label">YouTube Video Links</label>
                                    <div id="youtube-links-wrapper-{{ $exercise->id }}">
                                        @php
                                            $exerciseLinks = collect($results[$exercise->id]->videos ?? []);
                                        @endphp

                                        @if ($exerciseLinks->count())
                                            @foreach ($exerciseLinks as $video)
                                                <div class="d-flex mb-2 youtube-link-row">
                                                    <input type="url" class="form-control me-2"
                                                        name="youtube_links[{{ $exercise->id }}][]"
                                                        value="{{ $video->youtube_link }}"
                                                        placeholder="https://www.youtube.com/watch?v=..." required>
                                                    <button type="button"
                                                        class="btn btn-danger btn-sm remove-link">&times;</button>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="d-flex mb-2 youtube-link-row">
                                                <input type="url" class="form-control me-2"
                                                    name="youtube_links[{{ $exercise->id }}][]"
                                                    placeholder="https://www.youtube.com/watch?v=..." required>
                                                <button type="button"
                                                    class="btn btn-danger btn-sm remove-link">&times;</button>
                                            </div>
                                        @endif

                                    </div>
                                    <button type="button" class="btn btn-outline-secondary btn-sm add-link"
                                        data-exercise="{{ $exercise->id }}">+ Add another link</button>
                                </div>
                            </div>
                        @endforeach

                        <button type="button" style="margin-top: 10px;" class="btn btn-success" id="submitScores">Save
                            Scores</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        #competitions-table th,
        #competitions-table td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
        }

        .error-message {
            font-size: 0.875rem;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // Client-side validation
            $('#submitScores').on('click', function(e) {
                e.preventDefault();
                let isValid = true;
                const errorElements = document.getElementsByClassName('error-message');

                // Clear previous errors
                Array.from(errorElements).forEach(element => {
                    element.textContent = '';
                });

                // Validate each score input
                $('.score-input').each(function() {
                    const input = $(this);
                    const exerciseId = input.attr('name').match(/\d+/)[
                        0]; // Extract exercise ID from name
                    const value = parseFloat(input.val()) || 0;

                    if (!input.val().trim()) {
                        document.getElementById(`score_${exerciseId}-error`).textContent =
                            'Score is required.';
                        isValid = false;
                    } else if (value < 0) {
                        document.getElementById(`score_${exerciseId}-error`).textContent =
                            'Score cannot be negative.';
                        isValid = false;
                    }
                });

                if (isValid) {
                    $('#competitionForm').submit();
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            // Add link dynamically
            document.querySelectorAll('.add-link').forEach(button => {
                button.addEventListener('click', function() {
                    let exerciseId = this.dataset.exercise;
                    let wrapper = document.getElementById(`youtube-links-wrapper-${exerciseId}`);

                    let div = document.createElement('div');
                    div.classList.add('d-flex', 'mb-2', 'youtube-link-row');

                    div.innerHTML = `
                <input type="url" class="form-control me-2"
                       name="youtube_links[${exerciseId}][]"
                       placeholder="https://www.youtube.com/watch?v=..." required>
                <button type="button" class="btn btn-danger btn-sm remove-link">&times;</button>
            `;

                    wrapper.appendChild(div);
                });
            });

            // Remove link
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-link')) {
                    e.target.closest('.youtube-link-row').remove();
                }
            });
        });
    </script>
@endsection
