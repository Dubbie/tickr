<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PortalController;
use App\Http\Middleware\ValidateCustomerLink;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'index'])->name('home');


// Portal specific routes
Route::middleware([ValidateCustomerLink::class])->prefix('portal/{link}')->group(function () {
    Route::get('/', [PortalController::class, 'index'])->name('portal.index');
});

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
