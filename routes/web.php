<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'index'])->name('home');

Route::group([
    'prefix' => 'admin',
    'middleware' => 'auth'
], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::group([
        'prefix' => 'customer'
    ], function () {
        Route::get('/', [CustomerController::class, 'index'])->name('customer.index');
    });
});

include __DIR__ . '/auth.php';
