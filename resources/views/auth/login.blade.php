@extends('layouts.guest')

@section('title', 'Log in')

@section('content')
    <h2 class="h5 fw-bold mb-3 text-center">Welcome back</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember">
            <label class="form-check-label" for="remember">Remember me</label>
        </div>
        <button type="submit" class="btn btn-brand w-100">
            <i class="bi bi-box-arrow-in-right"></i> Log in
        </button>
    </form>
    <p class="text-center small mt-4 mb-0">
        Don't have an account? <a href="{{ route('register') }}">Register here</a>
    </p>
@endsection
