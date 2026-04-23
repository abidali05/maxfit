@extends('layouts.app-auth')

@section('title', 'Login')

@section('content')
    <div class="container-fluid d-flex justify-content-center align-items-center min-vh-100 bg-light">
        <div class="col-md-4 bg-white p-4 rounded shadow">
            <div class="text-center">
                <img src="{{ asset('assets/images/logo.png') }}" alt="" class="img-fluid">
            </div>
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
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


            <form method="POST" action="{{ route('branches.login.submit') }}" class="mt-4">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control" id="email" name="email" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label d-flex justify-content-between">
                        <span>Password</span>
                        <a href="{{ route('password.request') }}" class="text-decoration-none small">Forgot Password?</a>
                    </label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember Me</label>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn text-white" style="background-color: #57B9FF">Login</button>
                </div>
            </form>


        </div>
    </div>
@endsection
