@extends('layouts.coach.app')
@section('title', 'Competitions')
@section('content')
    <div class="px-4 pt-4 container-fluid" style="min-height: 82.5vh">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="p-4 text-center rounded bg-light">
                    <div class="mb-4 d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">Competition Details</h6>
                    </div>
                    <form action="{{ route('coach.competition-result-update', $competitionUser->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @forelse ($sets as $set)
                            @php
                                $setExercises = $set->setExercises->filter(fn($se) => !empty($se->exercise));
                            @endphp

                            @if ($setExercises->isEmpty())
                                @continue
                            @endif

                            <div class="mb-4 text-start">
                                <h5 class="mb-3">{{ $set->name }}</h5>

                                @foreach ($setExercises as $setExercise)
                                    @php
                                        $exercise = $setExercise->exercise;
                                        $result = $results->get($exercise->id);
                                        $exerciseLinks = collect($result?->videos ?? []);
                                        $primaryLink = optional($exerciseLinks->first())->youtube_link;
                                    @endphp

                                    <div class="mb-3 border p-3 rounded text-start">
                                        <h6 class="mb-2">{{ $exercise->name }}</h6>
                                        <label for="score_{{ $exercise->id }}" class="form-label">Score</label>
                                        <input type="number" step="0.01" name="scores[{{ $exercise->id }}]"
                                            class="form-control" value="{{ $result?->score ?? '' }}" required min="0">

                                        {{-- YouTube Link (single input; preserves extra existing links in hidden fields) --}}
                                        <div class="mt-3">
                                            <label class="form-label">YouTube Video Link</label>
                                            <input type="url" class="form-control" name="youtube_links[{{ $exercise->id }}][]"
                                                value="{{ $primaryLink ?? '' }}"
                                                placeholder="https://www.youtube.com/watch?v=...">

                                            @foreach ($exerciseLinks->slice(1) as $video)
                                                <input type="hidden" name="youtube_links[{{ $exercise->id }}][]"
                                                    value="{{ $video->youtube_link }}">
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @empty
                            <div class="text-start text-muted">No sets found for this competition.</div>
                        @endforelse

                        <button type="submit" class="btn btn-success">Save Scores</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
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
    </style>
@endpush

@push('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#competitions-table').DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: 10,
                order: [],
                language: {
                    search: "",
                    searchPlaceholder: "Search competitions..."
                }
            });
        });
    </script>
@endpush
