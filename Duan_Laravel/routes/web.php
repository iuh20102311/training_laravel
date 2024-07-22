<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DiscountCode;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified', 'check.user.status'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['role:Admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/active', [UserController::class, 'updateIsActive'])->name('users.active');
        Route::get('export', [UserController::class, 'export'])->name('users.export');
        Route::post('import', [UserController::class, 'import'])->name('users.import');

        Route::resource('orders', OrderController::class);
        Route::patch('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    });

    Route::middleware(['role:Admin,Editor'])->group(function () {
        Route::resource('products', ProductController::class)->except(['index', 'show']);
    });

    Route::middleware(['role:Reviewer'])->group(function () {
        Route::resource('products', ProductController::class)->only(['index', 'show']);
        Route::post('/orders/add-to-cart', [OrderController::class, 'addToCart'])->name('orders.add');
        Route::post('/orders/remove-from-cart', [OrderController::class, 'removeFromCart'])->name('orders.remove');
        Route::get('/cart', [OrderController::class, 'showCart'])->name('orders.cart');
        Route::get('/checkout', [OrderController::class, 'showCheckout'])->name('orders.checkout');
        Route::post('/place-order', [OrderController::class, 'placeOrder'])->name('orders.place');
        Route::get('/orders/preview', [OrderController::class, 'preview'])->name('orders.preview');
        Route::post('/orders/check-discount', [OrderController::class, 'checkDiscount'])->name('orders.check-discount');    
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