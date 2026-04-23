@extends('layouts.app')
@section('title', 'Challenge Details')
@section('content')
<div class="container mt-4">

    <h4>Challenge #{{ $challenge->id }}</h4>
    <p><strong>Status:</strong> {{ ucfirst($challenge->status) }}</p>
    <p><strong>Start:</strong> {{ $challenge->start_date }} {{ $challenge->start_time ? date('h:i A', strtotime($challenge->start_time)) : '' }}</p>
    <p><strong>End:</strong> {{ $challenge->end_date }} {{ $challenge->end_time ? date('h:i A', strtotime($challenge->end_time)) : '' }}</p>
    <p><strong>Challenger:</strong> {{ $challenge->challenger->name ?? 'N/A' }}</p>
    <p><strong>Challenge To:</strong> {{ $challenge->challengeTo->name ?? 'N/A' }}</p>

    <hr>

    <h5>Exercises</h5>

    @if($challenge->challengeExercises->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Set Name</th>
                    <th>Exercise Name</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Video</th>
                    <th>Image</th>
                </tr>
            </thead>
            <tbody>
                @foreach($challenge->challengeExercises as $index => $ce)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $ce->set->name ?? 'N/A' }}</td>
                        <td>{{ $ce->exercise->name ?? 'N/A' }}</td>
                        <td>{{ $ce->exercise->exercise_type ?? 'N/A' }}</td>
                        <td style="max-width:300px; white-space: pre-wrap;">{{ $ce->exercise->description ?? 'N/A' }}</td>
                        <td>
                            @if($ce->exercise->youtube_link)
                                <a href="{{ $ce->exercise->youtube_link }}" target="_blank">Video</a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if($ce->exercise->image)
                                <img src="{{ asset($ce->exercise->image) }}" alt="Exercise Image" width="50">
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No exercises assigned to this challenge.</p>
    @endif

</div>
@endsection
