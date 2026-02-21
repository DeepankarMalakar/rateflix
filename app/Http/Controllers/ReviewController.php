<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function store(Request $request, Movie $movie): RedirectResponse
    {
        $request->validate([
            'rating' => ['required', 'integer', 'between:1,10'],
            'review_text' => ['required', 'string', 'min:10'],
        ]);

        Review::updateOrCreate(
            ['movie_id' => $movie->id, 'user_id' => Auth::id()],
            [
                'rating' => (int) $request->rating,
                'review_text' => $request->review_text,
                'status' => 'approved',
            ]
        );

        return redirect()->route('movies.show', $movie)->with('success', 'Review submitted successfully.');
    }

    public function edit(Review $review): View
    {
        abort_unless($review->user_id === Auth::id(), 403);

        return view('user.edit_review', compact('review'));
    }

    public function update(Request $request, Review $review): RedirectResponse
    {
        abort_unless($review->user_id === Auth::id(), 403);

        $data = $request->validate([
            'rating' => ['required', 'integer', 'between:1,10'],
            'review_text' => ['required', 'string', 'min:10'],
        ]);

        $review->update($data);

        return redirect('/dashboard')->with('success', 'Review updated successfully.');
    }

    public function delete(Review $review): RedirectResponse
    {
        abort_unless($review->user_id === Auth::id(), 403);

        $review->delete();

        return redirect('/dashboard')->with('success', 'Review deleted successfully.');
    }
}
