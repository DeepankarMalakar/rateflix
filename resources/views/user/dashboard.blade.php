@extends('layouts.app', ['title' => 'My Dashboard'])

@section('content')
<div class="container">
    <div class="page-header">
        <div>
            <h1 class="page-title">My Reviews</h1>
            <p class="page-sub">Manage your submitted reviews</p>
        </div>
    </div>

    @if($reviews->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">&#128221;</div>
            <h2>No reviews yet</h2>
            <p>Browse movies and share your first review.</p>
            <a href="{{ url('/') }}" class="btn btn-primary">Browse Movies</a>
        </div>
    @else
        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Movie</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th>Last Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reviews as $review)
                        <tr>
                            <td><a href="{{ url('/movies/' . $review->movie_id) }}" class="table-link">{{ $review->movie->title }}</a></td>
                            <td><span class="rating-pill">{{ $review->rating }}/10</span></td>
                            <td><span class="status-badge status-{{ $review->status }}">{{ ucfirst($review->status) }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($review->updated_at)->format('M j, Y') }}</td>
                            <td class="table-actions">
                                <a href="{{ url('/reviews/' . $review->id . '/edit') }}" class="btn btn-outline btn-sm">Edit</a>
                                <form method="POST" action="{{ url('/reviews/' . $review->id . '/delete') }}" style="display:inline" onsubmit="return confirm('Delete your review?');">
                                    @csrf
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection


