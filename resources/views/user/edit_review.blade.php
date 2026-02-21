@extends('layouts.app', ['title' => 'Edit Review'])

@section('content')
<div class="container" style="max-width: 640px;">
    <div class="page-header">
        <div>
            <a href="{{ url('/dashboard') }}" class="back-link">? My Dashboard</a>
            <h1 class="page-title">Edit Review</h1>
        </div>
    </div>

    <div class="form-card">
        <form method="POST" action="{{ url('/reviews/' . $review->id . '/update') }}" novalidate>
            @csrf
            <div class="form-group">
                <label for="rating" class="form-label">Rating (1-10)</label>
                <input type="number" id="rating" name="rating" class="form-control" min="1" max="10" value="{{ old('rating', $review->rating) }}" required>
            </div>
            <div class="form-group">
                <label for="review_text" class="form-label">Review</label>
                <textarea id="review_text" name="review_text" class="form-control" rows="6" minlength="10" required>{{ old('review_text', $review->review_text) }}</textarea>
            </div>
            <div class="form-actions">
                <a href="{{ url('/dashboard') }}" class="btn btn-ghost">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
