<?php

use App\Http\Controllers\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PizzaController;
use App\Http\Controllers\IngredientController;

Route::post('login', [AuthController::class, 'authenticate']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['web', 'auth:sanctum'])->group(function () {



    Route::prefix('products')->group(function () {

        Route::prefix('ingredients')->group(function () {
            Route::get('data-table', [IngredientController::class, 'dataTable'])->name('ingredients.dataTable');
            Route::post('/', [IngredientController::class, 'createUpdate'])->name('ingredients.createUpdate');
            Route::delete('/', [IngredientController::class, 'delete'])->name('ingredients.delete');
            Route::delete('/bulk-delete', [IngredientController::class, 'bulkDelete'])->name('ingredients.bulkDelete');
        });

        Route::prefix('pizzas')->group(function () {
            Route::get('data-table', [PizzaController::class, 'dataTable'])->name('pizzas.dataTable');
            Route::post('/', [PizzaController::class, 'createUpdate'])->name('pizzas.createUpdate');
            Route::delete('/', [PizzaController::class, 'delete'])->name('pizzas.delete');
            Route::delete('/bulk-delete', [PizzaController::class, 'bulkDelete'])->name('pizzas.bulkDelete');
        });

        Route::prefix('reviews')->group(function () {
            Route::get('data-table', [ReviewController::class, 'dataTable'])->name('reviews.dataTable');
            Route::delete('/', [ReviewController::class, 'delete'])->name('reviews.delete');
            Route::delete('/bulk-delete', [ReviewController::class, 'bulkDelete'])->name('reviews.bulkDelete');
        });

        Route::prefix('orders')->group(function () {
            Route::get('data-table', [OrderController::class, 'dataTable'])->name('orders.dataTable');
            Route::post('/', [OrderController::class, 'createUpdate'])->name('orders.createUpdate');
            Route::delete('/', [OrderController::class, 'delete'])->name('orders.delete');
            Route::delete('/bulk-delete', [OrderController::class, 'bulkDelete'])->name('orders.bulkDelete');
        });

    });

});
