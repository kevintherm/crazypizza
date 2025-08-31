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
        Route::get('ingredients/data-table', [IngredientController::class, 'dataTable'])->name('ingredients.dataTable');
        Route::post('ingredients', [IngredientController::class, 'createUpdate'])->name('ingredients.createUpdate');
        Route::delete('ingredients', [IngredientController::class, 'delete'])->name('ingredients.delete');
        Route::delete('ingredients/bulk-delete', [IngredientController::class, 'bulkDelete'])->name('ingredients.bulkDelete');
    });

});
