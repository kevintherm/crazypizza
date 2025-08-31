<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IngredientController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [AuthController::class, 'authenticate']);

Route::middleware(['web', 'auth:sanctum'])->group(function () {

    Route::prefix('products')->group(function () {

        Route::prefix('ingredients')->group(function () {
            Route::get('data-table', [IngredientController::class, 'dataTable'])->name('ingredients.dataTable');
            Route::post('/', [IngredientController::class, 'createUpdate'])->name('ingredients.createUpdate');
            Route::delete('/', [IngredientController::class, 'delete'])->name('ingredients.delete');
            Route::delete('/bulk-delete', [IngredientController::class, 'bulkDelete'])->name('ingredients.bulkDelete');
        });

        Route::prefix('pizzas')->group(function () {
            Route::get('data-table', [IngredientController::class, 'dataTable'])->name('pizzas.dataTable');
            Route::post('/', [IngredientController::class, 'createUpdate'])->name('pizzas.createUpdate');
            Route::delete('/', [IngredientController::class, 'delete'])->name('pizzas.delete');
            Route::delete('/bulk-delete', [IngredientController::class, 'bulkDelete'])->name('pizzas.bulkDelete');
        });

    });

});
