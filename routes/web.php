<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\EventController; // ✅ Tambahkan ini

// Landing page umum
Route::get('/', function () {
    return view('landing');
});

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Halaman utama (hanya setelah login)
Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return view('home');
    })->name('home');

    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/login');
    })->name('logout');

    // Music
    Route::get('/music', [SongController::class, 'create'])->name('music');
    Route::post('/music', [SongController::class, 'store'])->name('songs.store');

    // Notes
    Route::get('/notes', [NotesController::class, 'index'])->name('notes.index');
    Route::post('/notes', [NotesController::class, 'store'])->name('notes.store');
    Route::get('/notes/tag/{tag}', [NotesController::class, 'getByTag'])->name('notes.by-tag');
    Route::get('/notes/search', [NotesController::class, 'search'])->name('notes.search');

    // Events ✅ (menggunakan EventController)
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
    Route::post('/events/join/{id}', [EventController::class, 'join'])->name('events.join');

});
