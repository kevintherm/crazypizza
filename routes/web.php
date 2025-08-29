<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\IngredientController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {

    Route::get('/', function () {
        return 'welcome';
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
        Route::get('ingredients/data-table', [IngredientController::class, 'dataTable']);
        Route::post('ingredients', [IngredientController::class, 'store']);
    });

    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

});
