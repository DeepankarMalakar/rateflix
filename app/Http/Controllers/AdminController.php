<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Review;
use App\Models\User;
use App\Services\TmdbClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminController extends Controller
{
    // Admin overview: movies, reviews, and users in one page.
    public function dashboard(): View
    {
        $this->ensureAdmin();

        $movies = Movie::query()
            ->withAvg(['reviews as avg_rating' => fn ($q) => $q->where('status', 'approved')], 'rating')
            ->withCount(['reviews as review_count' => fn ($q) => $q->where('status', 'approved')])
            ->latest()
            ->get();

        $reviews = Review::query()->with(['movie', 'user'])->latest()->get();
        $users = User::query()->latest()->get();

        return view('admin.dashboard', compact('movies', 'reviews', 'users'));
    }

    public function createMovie(): View
    {
        $this->ensureAdmin();
        return view('admin.movies.create');
    }

    public function storeMovie(Request $request, TmdbClient $tmdb): RedirectResponse
    {
        $this->ensureAdmin();

        $data = $this->validateMovie($request);
        // If poster URL is blank, try TMDB auto-lookup.
        if (blank($data['poster_url'])) {
            $data['poster_url'] = $tmdb->searchPosterUrl($data['title'], $data['release_date']);
        }

        $data['created_by'] = Auth::id();
        Movie::create($data);

        return redirect('/admin')->with('success', 'Movie added successfully.');
    }

    public function editMovie(Movie $movie): View
    {
        $this->ensureAdmin();
        return view('admin.movies.edit', compact('movie'));
    }

    public function updateMovie(Request $request, Movie $movie, TmdbClient $tmdb): RedirectResponse
    {
        $this->ensureAdmin();

        $data = $this->validateMovie($request);
        if (blank($data['poster_url'])) {
            $data['poster_url'] = $tmdb->searchPosterUrl($data['title'], $data['release_date']) ?? $movie->poster_url;
        }

        $movie->update($data);

        return redirect('/admin')->with('success', 'Movie updated successfully.');
    }

    public function deleteMovie(Movie $movie): RedirectResponse
    {
        $this->ensureAdmin();
        $movie->delete();

        return redirect('/admin')->with('success', 'Movie deleted successfully.');
    }

    public function approveReview(Review $review): RedirectResponse
    {
        $this->ensureAdmin();
        $review->update(['status' => 'approved']);

        return redirect('/admin')->with('success', 'Review approved.');
    }

    public function deleteReview(Review $review): RedirectResponse
    {
        $this->ensureAdmin();
        $review->delete();

        return redirect('/admin')->with('success', 'Review deleted.');
    }

    private function validateMovie(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string', 'min:10'],
            'genre' => ['required', 'string', 'max:100'],
            'release_date' => ['required', 'date'],
            'poster_url' => ['nullable', 'url'],
        ]);
    }

    private function ensureAdmin(): void
    {
        // Central role gate for all admin actions in this controller.
        abort_unless(Auth::check() && Auth::user()->isAdmin(), 403);
    }
}
