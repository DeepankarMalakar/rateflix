@extends('layouts.app', ['title' => 'Edit Movie'])

@section('content')
<div class="container" style="max-width: 720px;">
    <div class="page-header">
        <div>
            <a href="{{ url('/admin') }}" class="back-link">? Admin Dashboard</a>
            <h1 class="page-title">Edit Movie</h1>
        </div>
    </div>

    <div class="form-card">
        <form method="POST" action="{{ url('/admin/movies/' . $movie->id . '/update') }}" novalidate>
            @csrf
            <div class="form-group">
                <label for="title" class="form-label">Movie Title</label>
                <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $movie->title) }}" required>
            </div>
            <div class="form-group">
                <label for="genre" class="form-label">Genre</label>
                <input type="text" id="genre" name="genre" class="form-control" value="{{ old('genre', $movie->genre) }}" required>
            </div>
            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" class="form-control" rows="5" required>{{ old('description', $movie->description) }}</textarea>
            </div>
            <div class="form-group">
                <label for="release_date" class="form-label">Release Date</label>
                <input type="date" id="release_date" name="release_date" class="form-control" value="{{ old('release_date', $movie->release_date) }}" required>
            </div>
            <div class="form-group">
                <label for="poster_url" class="form-label">Poster URL (optional)</label>
                <input type="url" id="poster_url" name="poster_url" class="form-control" value="{{ old('poster_url', $movie->poster_url) }}" placeholder="Leave blank to auto-fetch from TMDB">
            </div>
            <div class="form-actions">
                <a href="{{ url('/admin') }}" class="btn btn-ghost">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
