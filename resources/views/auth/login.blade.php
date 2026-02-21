@extends('layouts.app', ['title' => 'Sign In'])

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-icon">&#127909;</div>
            <h1 class="auth-title">Welcome Back</h1>
            <p class="auth-sub">Sign in to your account to continue</p>
        </div>

        <form method="POST" action="{{ url('/login') }}" class="auth-form" novalidate>
            @csrf
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary btn-full">Sign In</button>
        </form>

        <p class="auth-footer">Don't have an account? <a href="{{ url('/register') }}">Create one free</a></p>

        <div class="demo-credentials">
            <p class="demo-title">Demo Credentials</p>
            <p>Admin: <code>admin@rateflix.com</code> / <code>password</code></p>
            <p>User: <code>jane@example.com</code> / <code>password</code></p>
        </div>
    </div>
</div>
@endsection


