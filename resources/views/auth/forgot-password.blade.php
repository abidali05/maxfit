@extends('layouts.app-auth')

@section('title', 'Forgot Password')

@section('content')
<div class="container-fluid d-flex justify-content-center align-items-center min-vh-100 bg-light">
    <div class="col-md-4 bg-white p-4 rounded shadow">
        <div class="text-center mb-3">
            <img src="{{ asset('assets/images/logo.png') }}" alt="MaxFit" class="img-fluid">
            <h5 class="mt-3">Forgot Your Password?</h5>
            <p class="text-muted small">Enter your email address to receive a reset link.</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" name="email" id="email" required autofocus>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn text-white" style="background-color: #57B9FF">
                    Send Password Reset Link
                </button>
            </div>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="text-decoration-none small">Back to login</a>
        </div>
    </div>
</div>
@endsection
