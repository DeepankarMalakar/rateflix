<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
    public function dashboard(): View
    {
        $reviews = Review::query()
            ->with('movie')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.dashboard', compact('reviews'));
    }
}
