<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('export', [UserController::class, 'export'])->name('users.export');
Route::post('import', [UserController::class, 'import'])->name('users.import');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['role:Admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/active', [UserController::class, 'updateIsActive'])->name('users.active');

        Route::resource('orders', OrderController::class);
        Route::patch('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    });

    Route::middleware(['role:Admin,Editor'])->group(function () {
        Route::resource('products', ProductController::class)->except(['index', 'show']);
    });

    Route::resource('products', ProductController::class)->only(['index', 'show']);

    Route::get('/home', function () {
        $user = Auth::user();
        if ($user->group_role === 'Admin') {
            return redirect()->route('users.index');
        } else {
            return redirect()->route('products.index');
        }
    });
});

require __DIR__ . '/auth.php';

Route::post('/login', [LoginController::class, 'login'])->name('login');