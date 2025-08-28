<?php

use App\Http\Controllers\AuthController;
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

    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

});
