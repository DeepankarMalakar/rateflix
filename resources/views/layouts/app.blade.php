<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') }} - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
<nav class="navbar">
    <div class="container nav-inner">
        <a href="{{ url('/') }}" class="brand">
            <span class="brand-icon">&#127909;</span>
            <span>{{ config('app.name') }}</span>
        </a>

        <div class="nav-links">
            <a href="{{ url('/') }}" class="nav-link{{ request()->path() === '/' ? ' active' : '' }}">Movies</a>

            @auth
                <a href="{{ url('/dashboard') }}" class="nav-link{{ request()->path() === 'dashboard' ? ' active' : '' }}">My Reviews</a>

                @if(auth()->user()->isAdmin())
                    <a href="{{ url('/admin') }}" class="nav-link{{ str_starts_with(request()->path(), 'admin') ? ' active' : '' }}">Admin</a>
                @endif

                <div class="nav-user">
                    <span class="user-greeting">&#128075; {{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ url('/logout') }}" style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-sm">Sign Out</button>
                    </form>
                </div>
            @else
                <a href="{{ url('/login') }}" class="nav-link">Sign In</a>
                <a href="{{ url('/register') }}" class="btn btn-primary btn-sm">Join Free</a>
            @endauth
        </div>
    </div>
</nav>

@if(session('success'))
    <div class="flash flash-success">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="flash flash-error">{{ $errors->first() }}</div>
@endif

<main class="main-content">
    @yield('content')
</main>

<footer class="footer">
    <div class="container footer-inner">
        <span class="brand">&#127909; {{ config('app.name') }}</span>
        <span class="footer-text">A movie review platform</span>
        <span class="footer-year">© {{ date('Y') }}</span>
    </div>
</footer>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>


