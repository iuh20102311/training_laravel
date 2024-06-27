<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/dashboard', [UserController::class, 'store'])->name('users.store');
    Route::get('/dashboard/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/dashboard/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/dashboard/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::resource('products', ProductController::class);
});

require __DIR__ . '/auth.php';