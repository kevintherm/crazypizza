<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\IngredientController;

Route::middleware(['guest', 'init-cart'])->group(function () {

    Route::get('', [GuestController::class, 'welcome'])->name('welcome');
    Route::get('pizzas', [GuestController::class, 'pizzas'])->name('pizzas');
    Route::get('cart', [GuestController::class, 'cart'])->name('cart');

    Route::view('/track', 'track')->name('order.track');
    Route::post('/track', [GuestController::class, 'trackOrder'])->name('order.track');

    Route::get('/checkout/success', [PaymentController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/cancel', [PaymentController::class, 'cancel'])->name('checkout.cancel');
    Route::get('/order/{invoice}', [GuestController::class, 'orderConfirmation'])->name('order.confirmation');
    Route::post('/checkout', [PaymentController::class, 'checkout'])->name('checkout');

    Route::prefix('cart')->middleware('limit:15,30')->group(function () {
        Route::get('count', [CartController::class, 'getCount'])->name('cart.count');
        Route::post('add', [CartController::class, 'addToCart'])->name('cart.add');
        Route::post('update', [CartController::class, 'updateCart'])->name('cart.update');
        Route::post('remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
    });

    Route::name('login')->group(function () {
        Route::get('login', [AuthController::class, 'login']);
        Route::post('login', [AuthController::class, 'authenticate']);
    });

});

Route::middleware(['auth'])->group(function () {

    Route::view('dashboard', 'admin.dashboard')->name('dashboard');

    Route::prefix('products')->group(function () {
        Route::view('ingredients', 'admin.manage-ingredients');
        Route::view('pizzas', 'admin.manage-pizzas');
    });

    Route::prefix('orders')->group(function () {
        Route::view('/', 'admin.manage-orders');
    });


    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

});
