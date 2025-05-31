<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\NotesController;


Route::get('/', function () {
    return view('/landing');
});

// Register routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Login routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Landing page (hanya bisa diakses setelah login)
Route::get('/home', function () {
    return view('home');
})->middleware('auth')->name('home');


Route::get('/music', function () {
    return view('music');
})->name('music');

Route::get('/events', function () {
    return view('events');
})->name('events');

// Notes routes 
Route::get('/notes', function () {
    return view('notes');
});

Route::get('/notes', [NotesController::class, 'index'])->name('notes.index');
Route::post('/notes', [NotesController::class, 'store'])->name('notes.store');
Route::delete('/notes/{note}', [NotesController::class, 'destroy'])->name('notes.destroy');



// Additional AJAX routes for better UX
Route::get('/notes/tag/{tag}', [NotesController::class, 'getByTag'])->name('notes.by-tag');
Route::get('/notes/search', [NotesController::class, 'search'])->name('notes.search');


// Routes untuk Music
Route::get('/music', [SongController::class, 'index'])->name('songs.index');
Route::post('/songs', [SongController::class, 'store'])->name('songs.store');
Route::put('/songs/{song}', [SongController::class, 'update'])->name('songs.update');
Route::delete('/songs/{song}', [SongController::class, 'destroy'])->name('songs.destroy');
Route::get('/songs/filter', [SongController::class, 'filter'])->name('songs.filter');

