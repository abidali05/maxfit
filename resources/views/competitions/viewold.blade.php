@extends('layouts.app')
@section('title', 'View Competition')
@section('content')
    <div class="px-4 pt-4 container-fluid" style="min-height: 82.5vh">
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="p-4 rounded bg-light">
                    <h2>{{ $competition->name }}</h2>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong>Age Group:</strong> {{ $competition->age_group }}
                        </div>
                        <div class="col-md-6">
                            <strong>Genz:</strong> {{ ucfirst($competition->genz) }}
                        </div>
                        <div class="col-md-6">
                            <strong>Coach Name:</strong> {{ $competition->coach_name }}
                        </div>
                        <div class="col-md-6">
                            <strong>Country:</strong> {{ $competition->country }}
                        </div>
                        <div class="col-md-6">
                            <strong>City:</strong> {{ $competition->city }}
                        </div>
                        <div class="col-md-6">
                            <strong>Start Date:</strong> {{ $competition->start_date ?? 'N/A' }}
                        </div>
                        <div class="col-md-6">
                            <strong>End Date:</strong> {{ $competition->end_date ?? 'N/A' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Start Time:</strong> {{ $competition->start_time ?? 'N/A' }}
                        </div>
                        <div class="col-md-6">
                            <strong>End Time:</strong> {{ $competition->end_time ?? 'N/A' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Time Allowed:</strong> {{ $competition->time_allowed ? $competition->time_allowed . ' minutes' : 'N/A' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Organization Type:</strong> {{ $competition->organisationType->name ?? 'N/A' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Organization:</strong> {{ $competition->organisation->name ?? 'N/A' }}
                        </div>
                        <div class="col-md-12">
                            <strong>Description:</strong> {{ $competition->description ?? 'N/A' }}
                        </div>
                        @if ($competition->image)
                            <div class="col-md-12">
                                <strong>Image:</strong>
                                <img src="{{ asset($competition->image) }}" alt="Competition Image" style="max-width: 200px;">
                            </div>
                        @endif
                    </div>

                    <h3 class="mt-4">Participants and Results</h3>
                    @if ($competition->competitionUsers->isEmpty())
                        <p>No users have accepted this competition.</p>
                    @else
                        <form action="{{ route('competitions.results.store', $competition->id) }}" method="POST">
                            @csrf
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        @foreach ($exercises as $exercise)
                                            <th>{{ $exercise->name }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($competition->competitionUsers as $competitionUser)
                                        <tr>
                                            <td>{{ $competitionUser->user->name ?? 'Unknown User' }}</td>
                                            @foreach ($exercises as $exercise)
                                                <td>
                                                    <input type="hidden" name="results[{{$competitionUser->id}}][{{$exercise->id}}][competition_user_id]" value="{{ $competitionUser->id }}">
                                                    <input type="hidden" name="results[{{$competitionUser->id}}][{{$exercise->id}}][exercise_id]" value="{{ $exercise->id }}">
                                                    <input type="number" class="form-control" name="results[{{$competitionUser->id}}][{{$exercise->id}}][score]"
                                                           value="{{ $competitionUser->competitionResult->where('exercise_id', $exercise->id)->first()->score ?? '' }}"
                                                           min="0" step="0.01">
                                                    @error("results.$competitionUser->id.$exercise->id.score")
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-primary">Save Results</button>
                            <a href="{{ route('competitions.index') }}" class="btn btn-outline-danger">Back</a>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
