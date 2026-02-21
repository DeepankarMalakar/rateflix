@extends('layouts.app', ['title' => $movie->title])

@section('content')
<div class="container">
    <nav class="breadcrumb">
        <a href="{{ url('/') }}">Movies</a> <span>›</span> <span>{{ $movie->title }}</span>
    </nav>

    <div class="movie-detail">
        <div class="movie-poster-wrap">
            @if($movie->poster_url)
                @php $posterSrc = preg_match('#^https?://#i', $movie->poster_url) ? $movie->poster_url : asset($movie->poster_url); @endphp
                <img class="movie-poster" src="{{ $posterSrc }}" alt="{{ $movie->title }} poster" onerror="this.onerror=null; this.remove(); this.parentElement.classList.add('poster-error'); this.parentElement.insertAdjacentHTML('beforeend', '<div class=&quot;poster-placeholder large&quot;>&#127909;</div>');">
            @else
                <div class="poster-placeholder large">&#127909;</div>
            @endif
        </div>

        <div class="movie-info">
            <div class="movie-meta-badges">
                <span class="genre-tag">{{ $movie->genre }}</span>
                <span class="release-badge">&#128197; {{ \Carbon\Carbon::parse($movie->release_date)->format('M j, Y') }}</span>
            </div>

            <h1 class="movie-title">{{ $movie->title }}</h1>

            <div class="rating-summary">
                @php $avg = (float) ($movie->avg_rating ?? 0); @endphp
                <div class="rating-big">{{ $avg > 0 ? number_format($avg, 1) : '—' }}</div>
                <div class="rating-details">
                    <div class="rating-stars large">
                        @php $filled = (int) round($avg / 2); @endphp
                        @for($i = 1; $i <= 5; $i++)
                            <span class="star {{ $i <= $filled ? 'star-filled' : 'star-empty' }}">&#9733;</span>
                        @endfor
                    </div>
                    <div class="rating-label">{{ ($movie->review_count ?? 0) > 0 ? $movie->review_count . ' review' . ($movie->review_count != 1 ? 's' : '') : 'No reviews yet' }}</div>
                </div>
            </div>

            <p class="movie-description">{!! nl2br(e($movie->description)) !!}</p>

            @auth
                @if(auth()->user()->isAdmin())
                    <div class="admin-actions">
                        <a href="{{ url('/admin/movies/' . $movie->id . '/edit') }}" class="btn btn-outline btn-sm">Edit Movie</a>
                        <form method="POST" action="{{ url('/admin/movies/' . $movie->id . '/delete') }}" style="display:inline" onsubmit="return confirm('Are you sure you want to delete this movie?');">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Delete Movie</button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>
    </div>

    <section class="reviews-section">
        <h2 class="section-title">Reviews</h2>

        @auth
            @if(!$hasReviewed)
                <div class="review-form-card">
                    <h3 class="form-title">Write a Review</h3>
                    <form method="POST" action="{{ url('/movies/' . $movie->id . '/reviews') }}" class="review-form">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Your Rating (1-10)</label>
                            <div class="interactive-stars" id="starWidget">
                                @for($i = 1; $i <= 10; $i++)
                                    <button type="button" class="star-btn" data-val="{{ $i }}" title="{{ $i }}/10">&#9733;</button>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="ratingInput" value="">
                            <span class="rating-hint" id="ratingHint">Click a star to rate</span>
                        </div>

                        <div class="form-group">
                            <label for="review_text" class="form-label">Your Review</label>
                            <textarea id="review_text" name="review_text" class="form-control" rows="5" minlength="10" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit Review</button>
                    </form>
                </div>
            @else
                <div class="info-banner">You have already submitted a review for this film. Visit your <a href="{{ url('/dashboard') }}">dashboard</a> to edit it.</div>
            @endif
        @else
            <div class="info-banner"><a href="{{ url('/login') }}">Sign in</a> or <a href="{{ url('/register') }}">create an account</a> to leave a review.</div>
        @endauth

        @if($reviews->isEmpty())
            <div class="empty-reviews"><p>No reviews yet. Be the first to share your thoughts!</p></div>
        @else
            <div class="review-list">
                @foreach($reviews as $review)
                    <div class="review-card">
                        <div class="review-header">
                            <div class="reviewer-info">
                                <div class="reviewer-avatar">{{ strtoupper(substr($review->user->name, 0, 1)) }}</div>
                                <div>
                                    <span class="reviewer-name">{{ $review->user->name }}</span>
                                    <span class="review-date">{{ \Carbon\Carbon::parse($review->created_at)->format('M j, Y') }}</span>
                                </div>
                            </div>
                            <div class="review-rating-badge">
                                <span class="rating-pill">{{ $review->rating }}/10</span>
                            </div>
                        </div>

                        <p class="review-text">{!! nl2br(e($review->review_text)) !!}</p>

                        @auth
                            @if($review->user_id === auth()->id() || auth()->user()->isAdmin())
                                <div class="review-actions">
                                    @if($review->user_id === auth()->id())
                                        <a href="{{ url('/reviews/' . $review->id . '/edit') }}" class="btn btn-outline btn-xs">Edit</a>
                                        <form method="POST" action="{{ url('/reviews/' . $review->id . '/delete') }}" style="display:inline" onsubmit="return confirm('Delete your review?');">
                                            @csrf
                                            <button class="btn btn-danger btn-xs">Delete</button>
                                        </form>
                                    @elseif(auth()->user()->isAdmin())
                                        <form method="POST" action="{{ url('/admin/reviews/' . $review->id . '/delete') }}" style="display:inline" onsubmit="return confirm('Delete this user review as admin?');">
                                            @csrf
                                            <button class="btn btn-danger btn-xs">Admin Delete</button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        @endauth
                    </div>
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection


