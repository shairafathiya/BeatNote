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

// Logout route (opsional)
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

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



// Additional AJAX routes for better UX
Route::get('/notes/tag/{tag}', [NotesController::class, 'getByTag'])->name('notes.by-tag');
Route::get('/notes/search', [NotesController::class, 'search'])->name('notes.search');


//music
Route::get('/music', [SongController::class, 'create'])->name('songs.create');
Route::post('/music', [SongController::class, 'store'])->name('songs.store');


//comment
// Route::get('/notes/{notes}/edit', [NotesController::class, 'edit'])->name('notes.edit');
// Route::put('/notes/{notes}', [NotesController::class, 'update'])->name('notes.update');
// Route::delete('/notes/{notes}', [NotesController::class, 'destroy'])->name('notes.destroy');