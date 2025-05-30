<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SongController;

Route::get('/', function () {
    return view('landing');
});

Route::get('login',function (){
    return view('login');
});


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::resource('songs', SongController::class);
// Atau jika ingin custom routes:
Route::get('/music', [SongController::class, 'index'])->name('songs.index');
Route::post('/music', [SongController::class, 'store'])->name('songs.store');
Route::get('/music/{song}/edit', [SongController::class, 'edit'])->name('songs.edit');
Route::put('/music/{song}', [SongController::class, 'update'])->name('songs.update');
Route::delete('/music/{song}', [SongController::class, 'destroy'])->name('songs.destroy');