@extends('layouts.app')
@section('title', 'Edit Organisation Type')
@section('content')
    <div class="container-fluid pt-4 px-4" style="min-height: 82.5vh">
        <form action="{{ route('organisation-types.update', $organisation_type->id) }}') }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row g-4">


                <div class="col-lg-12">
                    <div class="bg-light rounded p-4">
                        {{-- Flash messages --}}
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" placeholder="Name" value="{{ $organisation_type->name }}"
                                    name="name" required>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                        </div>



                        <div class="mt-4">
                            <button class="btn btn-primary" type="submit">Save changes</button>
                            <a href="{{ route('organisation-types.index') }}" class="btn btn-outline-danger">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
