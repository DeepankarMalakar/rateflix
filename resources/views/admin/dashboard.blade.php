@extends('layouts.app', ['title' => 'Admin Dashboard'])

@section('content')
<div class="container admin-layout">
    <div class="page-header">
        <h1 class="page-title">Admin Dashboard</h1>
        <a href="{{ url('/admin/movies/create') }}" class="btn btn-primary">+ Add Movie</a>
    </div>

    <div class="stats-grid">
        <div class="stat-card"><div class="stat-icon">&#127909;</div><div class="stat-value">{{ $movies->count() }}</div><div class="stat-label">Total Movies</div></div>
        <div class="stat-card"><div class="stat-icon">&#11088;</div><div class="stat-value">{{ $reviews->count() }}</div><div class="stat-label">Total Reviews</div></div>
        <div class="stat-card"><div class="stat-icon">&#128101;</div><div class="stat-value">{{ $users->count() }}</div><div class="stat-label">Registered Users</div></div>
        <div class="stat-card"><div class="stat-icon">&#9989;</div><div class="stat-value">{{ $reviews->where('status', 'approved')->count() }}</div><div class="stat-label">Approved Reviews</div></div>
    </div>

    <div class="tabs" id="adminTabs">
        <button class="tab-btn active" data-tab="movies-tab">Movies ({{ $movies->count() }})</button>
        <button class="tab-btn" data-tab="reviews-tab">Reviews ({{ $reviews->count() }})</button>
        <button class="tab-btn" data-tab="users-tab">Users ({{ $users->count() }})</button>
    </div>

    <div id="movies-tab" class="tab-content active">
        <div class="table-card">
            <table class="data-table">
                <thead><tr><th>#</th><th>Title</th><th>Genre</th><th>Release</th><th>Avg Rating</th><th>Reviews</th><th>Actions</th></tr></thead>
                <tbody>
                @foreach($movies as $movie)
                    <tr>
                        <td>{{ $movie->id }}</td>
                        <td><a href="{{ url('/movies/' . $movie->id) }}" class="table-link">{{ $movie->title }}</a></td>
                        <td><span class="genre-tag sm">{{ $movie->genre }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($movie->release_date)->format('Y') }}</td>
                        <td>{{ $movie->avg_rating ? number_format($movie->avg_rating, 1) : '—' }}</td>
                        <td>{{ $movie->review_count }}</td>
                        <td class="table-actions">
                            <a href="{{ url('/admin/movies/' . $movie->id . '/edit') }}" class="btn btn-outline btn-xs">Edit</a>
                            <form method="POST" action="{{ url('/admin/movies/' . $movie->id . '/delete') }}" style="display:inline" onsubmit="return confirm('Delete this movie?');">@csrf<button class="btn btn-danger btn-xs">Delete</button></form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="reviews-tab" class="tab-content">
        <div class="table-card">
            <table class="data-table">
                <thead><tr><th>#</th><th>Movie</th><th>User</th><th>Rating</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
                <tbody>
                @foreach($reviews as $review)
                    <tr>
                        <td>{{ $review->id }}</td>
                        <td><a href="{{ url('/movies/' . $review->movie_id) }}" class="table-link">{{ $review->movie->title }}</a></td>
                        <td>{{ $review->user->name }}</td>
                        <td><span class="rating-pill">{{ $review->rating }}/10</span></td>
                        <td><span class="status-badge status-{{ $review->status }}">{{ ucfirst($review->status) }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($review->created_at)->format('M j, Y') }}</td>
                        <td class="table-actions">
                            @if($review->status !== 'approved')
                                <form method="POST" action="{{ url('/admin/reviews/' . $review->id . '/approve') }}" style="display:inline">@csrf<button class="btn btn-success btn-xs">Approve</button></form>
                            @endif
                            <form method="POST" action="{{ url('/admin/reviews/' . $review->id . '/delete') }}" style="display:inline" onsubmit="return confirm('Delete this review?');">@csrf<button class="btn btn-danger btn-xs">Delete</button></form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="users-tab" class="tab-content">
        <div class="table-card">
            <table class="data-table">
                <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Role</th><th>Joined</th></tr></thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td><span class="role-badge role-{{ $user->role }}">{{ $user->role === 'admin' ? 'Admin' : 'User' }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($user->created_at)->format('M j, Y') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


