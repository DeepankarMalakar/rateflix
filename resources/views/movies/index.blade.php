@extends('layouts.app', ['title' => 'Browse Movies'])

@section('content')
<section class="hero">
    <div class="container">
        <h1 class="hero-title">Discover &amp; Review <span class="highlight">Great Films</span></h1>
        <p class="hero-sub">Browse thousands of movies, read honest reviews, and share your thoughts with the community.</p>

        <form method="GET" action="{{ url('/') }}" class="search-form">
            <div class="search-bar">
                <span class="search-icon">&#128269;</span>
                <input type="text" name="search" class="search-input" placeholder="Search by title or genre..." value="{{ $search }}" autocomplete="off">
                @if($genre)
                    <input type="hidden" name="genre" value="{{ $genre }}">
                @endif
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>
    </div>
</section>

<section class="container filter-bar">
    <div class="filter-row">
        <span class="filter-label">Filter by genre:</span>
        <div class="genre-pills">
            <a href="{{ $search ? url('/?search=' . urlencode($search)) : url('/') }}" class="pill{{ $genre === '' ? ' pill-active' : '' }}">All</a>
            @foreach($genres as $g)
                <a href="{{ url('/?genre=' . urlencode($g) . ($search ? '&search=' . urlencode($search) : '')) }}" class="pill{{ $genre === $g ? ' pill-active' : '' }}">{{ $g }}</a>
            @endforeach
        </div>

        <span class="movie-count">{{ $movies->count() }} film{{ $movies->count() !== 1 ? 's' : '' }}</span>
    </div>
</section>

<section class="container">
    @if($movies->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">&#127909;</div>
            <h2>No movies found</h2>
            <p>Try adjusting your search or filter criteria.</p>
            <a href="{{ url('/') }}" class="btn btn-primary">Clear Filters</a>
        </div>
    @else
        <div class="movie-grid">
            @foreach($movies as $movie)
                <article class="movie-card">
                    <a href="{{ url('/movies/' . $movie->id) }}" class="card-poster-link">
                        <div class="card-poster">
                            @if($movie->poster_url)
                                @php
                                    $posterSrc = preg_match('#^https?://#i', $movie->poster_url) ? $movie->poster_url : asset($movie->poster_url);
                                @endphp
                                <img src="{{ $posterSrc }}" alt="{{ $movie->title }} poster" loading="lazy" onerror="this.onerror=null; this.remove(); this.parentElement.classList.add('poster-error'); this.parentElement.insertAdjacentHTML('beforeend', '<div class=&quot;poster-placeholder&quot;>&#127909;</div>');">
                            @else
                                <div class="poster-placeholder">&#127909;</div>
                            @endif
                            <div class="card-overlay"><span class="view-label">View Details</span></div>
                        </div>
                    </a>

                    <div class="card-body">
                        <div class="card-meta">
                            <span class="genre-tag">{{ $movie->genre }}</span>
                            <span class="release-year">{{ \Carbon\Carbon::parse($movie->release_date)->format('Y') }}</span>
                        </div>

                        <h3 class="card-title"><a href="{{ url('/movies/' . $movie->id) }}">{{ $movie->title }}</a></h3>

                        <div class="card-rating">
                            <div class="rating-stars">
                                @php $avg = (float) ($movie->avg_rating ?? 0); $filled = (int) round($avg / 2); @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="star {{ $i <= $filled ? 'star-filled' : 'star-empty' }}">&#9733;</span>
                                @endfor
                            </div>
                            <span class="rating-number">{{ $avg > 0 ? number_format($avg, 1) : 'Unrated' }}</span>
                            @if(($movie->review_count ?? 0) > 0)
                                <span class="review-count">({{ $movie->review_count }} review{{ $movie->review_count !== 1 ? 's' : '' }})</span>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</section>
@endsection


