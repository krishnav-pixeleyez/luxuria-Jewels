<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Admin Routes
Route::middleware(['auth','admin'])->group(function() {
    Route::get('/dashboard', function() {
        return view('admin.dashboard'); // create this view
    })->name('dashboard');
});

// Manager Routes
Route::middleware(['auth','manager'])->group(function() {
    Route::get('/manager/dashboard', function() {
        return view('manager.dashboard'); // create this view
    })->name('manager.dashboard');
});

// User Routes
Route::middleware(['auth'])->group(function() {
    Route::get('/user/dashboard', function() {
        return view('user.dashboard'); // create this view
    })->name('user.dashboard');
});