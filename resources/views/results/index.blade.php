@extends('layouts.app')
@section('title', 'Competitions')
@section('content')
    <div class="px-4 pt-4 container-fluid" style="min-height: 82.5vh">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="p-4 text-center rounded bg-light">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle text-start table-bordered datatable">
                            <thead>
                                <tr class="text-dark">
                                    <th>S.No</th>
                                    <th>Competition Name</th>
                                    <th>Start Date & Time</th>
                                    <th>User Name</th>
                                    <th>Percentage</th>
                                    <th>PushUps Per Min</th>
                                    <th>Position</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($results as $i => $result)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $result->competitionUser->competition->name ?? '-' }}</td>
                                        <td>
                                            @if($result->competitionUser && $result->competitionUser->competition)
                                                {{ \Carbon\Carbon::parse($result->competitionUser->competition->start_date ?? '')->format('d M Y') }}<br>
                                                <small>{{ \Carbon\Carbon::parse($result->competitionUser->competition->start_time ?? '')->format('h:i A') }}</small>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $result->competitionUser->user->name ?? '-' }}</td>
                                        <td>{{ $result->percentage ?? '-' }}%</td>
                                        <td>{{ $result->per_min ?? '-' }}</td>
                                        <td>{{ $result->position ?? '-' }}</td>
                                        {{-- <td class="text-end">
                                            <a href="{{ $result->competitionUser && $result->competitionUser->competition ? route('competitions.show', $result->competitionUser->competition->id) : '#' }}" class="btn btn-sm btn-primary @if(!($result->competitionUser && $result->competitionUser->competition)) disabled @endif">View</a>
                                        </td> --}}
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
