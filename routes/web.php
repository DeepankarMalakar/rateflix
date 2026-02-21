<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public routes: accessible to everyone.
Route::get('/', [MovieController::class, 'index'])->name('movies.index');
Route::get('/movies/{movie}', [MovieController::class, 'show'])->name('movies.show');

// Guest-only auth pages/actions.
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Logged-in user routes.
Route::middleware('auth')->group(function () {
    // Session/logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // User dashboard
    Route::get('/dashboard', [UserController::class, 'dashboard']);

    // Review CRUD (owner actions handled in controller policy checks)
    Route::post('/movies/{movie}/reviews', [ReviewController::class, 'store']);
    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit']);
    Route::post('/reviews/{review}/update', [ReviewController::class, 'update']);
    Route::post('/reviews/{review}/delete', [ReviewController::class, 'delete']);

    // Admin movie management (admin role enforced in controller)
    Route::get('/admin', [AdminController::class, 'dashboard']);
    Route::get('/admin/movies/create', [AdminController::class, 'createMovie']);
    Route::post('/admin/movies/store', [AdminController::class, 'storeMovie']);
    Route::get('/admin/movies/{movie}/edit', [AdminController::class, 'editMovie']);
    Route::post('/admin/movies/{movie}/update', [AdminController::class, 'updateMovie']);
    Route::post('/admin/movies/{movie}/delete', [AdminController::class, 'deleteMovie']);

    // Admin review moderation
    Route::post('/admin/reviews/{review}/approve', [AdminController::class, 'approveReview']);
    Route::post('/admin/reviews/{review}/delete', [AdminController::class, 'deleteReview']);
});
