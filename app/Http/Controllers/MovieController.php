<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MovieController extends Controller
{
    // Homepage: supports search + genre filter + aggregate rating data.
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $genre = trim((string) $request->query('genre', ''));

        $movies = Movie::query()
            ->withAvg(['reviews as avg_rating' => fn ($q) => $q->where('status', 'approved')], 'rating')
            ->withCount(['reviews as review_count' => fn ($q) => $q->where('status', 'approved')])
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('title', 'like', "%{$search}%")
                        ->orWhere('genre', 'like', "%{$search}%");
                });
            })
            ->when($genre !== '', fn ($q) => $q->where('genre', $genre))
            ->latest()
            ->get();

        $genres = Movie::query()->distinct()->orderBy('genre')->pluck('genre');

        return view('movies.index', compact('movies', 'genres', 'search', 'genre'));
    }

    public function show(Movie $movie): View
    {
        // Keep homepage/detail rating math aligned: only approved reviews count.
        $movie->loadAvg(['reviews as avg_rating' => fn ($q) => $q->where('status', 'approved')], 'rating')
            ->loadCount(['reviews as review_count' => fn ($q) => $q->where('status', 'approved')]);

        $reviews = Review::query()
            ->with('user')
            ->where('movie_id', $movie->id)
            // Public movie page shows moderated/approved reviews only.
            ->where('status', 'approved')
            ->latest()
            ->get();

        $hasReviewed = Auth::check()
            ? Review::query()->where('movie_id', $movie->id)->where('user_id', Auth::id())->exists()
            : false;

        return view('movies.show', compact('movie', 'reviews', 'hasReviewed'));
    }
}
