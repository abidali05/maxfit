@extends('layouts.branch.app')
@section('title', 'Coaches')
@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded p-4">
                {{-- <div class="d-flex justify-content-between mb-3">
                    <h4>Coaches</h4>
                    <a href="{{ route('coaches.create') }}" class="btn btn-primary">Add New</a>
                </div> --}}
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Image</th>
                            <th>Bio</th>
                            {{-- <th>Action</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($coaches as $i => $coach)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $coach->name }}</td>
                            <td>{{ $coach->email }}</td>
                            <td>{{ $coach->phone }}</td>
                            <td>@if($coach->image)<img src="{{ asset('storage/' . $coach->image) }}" width="60">@endif</td>
                            <td>{{ $coach->bio }}</td>
                            {{-- <td>
                                <a href="{{ route('coaches.edit', $coach->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('coaches.destroy', $coach->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td> --}}
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
