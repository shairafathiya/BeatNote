<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SongController;


// Root redirect ke register (halaman pertama)
Route::get('/', function () {
    return redirect('/landing');
});

// Register routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Login routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Landing page (hanya bisa diakses setelah login)
Route::get('/landing', function () {
    return view('home');
})->middleware('auth')->name('home');

// Logout route (opsional)
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

Route::get('/note', function () {
    return view('note');
})->name('note');

Route::get('/music', function () {
    return view('music');
})->name('music');

Route::get('/events', function () {
    return view('events');
})->name('events');