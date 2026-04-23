@extends('layouts.app')
@section('title', 'Rules of Counting')
@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded p-4">
                <div class="d-flex justify-content-between mb-3">
                    <h4>Rules of Counting</h4>
                    <a href="{{ route('rulesof-counting.create') }}" class="btn btn-primary">Add New</a>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Exercise</th>
                            <th>Title</th>
                            <th>Image</th>
                            <th>Video</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rules as $i => $rule)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $rule->competition->name }}</td>
                            <td>{{ $rule->custom_exercise_name }}</td>
                            <td>@if($rule->image_file)<img src="{{ asset('storage/' . $rule->image_file) }}" width="60">@endif</td>
                            <td>@if($rule->video_file)<a href="{{ asset('storage/' . $rule->video_file) }}" target="_blank">View</a>@endif</td>
                            <td>{{ $rule->description }}</td>
                            <td>
                                <a href="{{ route('rulesof-counting.edit', $rule->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('rulesof-counting.destroy', $rule->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
