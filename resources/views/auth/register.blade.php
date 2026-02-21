@extends('layouts.app', ['title' => 'Create Account'])

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-icon">&#127909;</div>
            <h1 class="auth-title">Create Account</h1>
            <p class="auth-sub">Join the RateFlix community today</p>
        </div>

        <form method="POST" action="{{ url('/register') }}" class="auth-form" novalidate>
            @csrf
            <div class="form-group">
                <label for="name" class="form-label">Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary btn-full">Create Account</button>
        </form>

        <p class="auth-footer">Already have an account? <a href="{{ url('/login') }}">Sign in</a></p>
    </div>
</div>
@endsection


