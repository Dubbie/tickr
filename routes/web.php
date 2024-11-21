<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\TicketController;
use App\Http\Middleware\ValidateCustomerLink;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'index'])->name('home');


// Portal specific routes
Route::middleware([ValidateCustomerLink::class])->prefix('portal/{link}')->group(function () {
    Route::get('/', [PortalController::class, 'index'])->name('portal.index');
    Route::get('/new', [PortalController::class, 'create'])->name('portal.create');
    Route::get('/ticket/{ticketNumber}', [PortalController::class, 'show'])->name('portal.show');
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
        Route::get('/{customer}', [CustomerController::class, 'show'])->name('customer.show');
    });

    Route::prefix('ticket')->group(function () {
        Route::get('/', [TicketController::class, 'index'])->name('ticket.index');
        Route::get('/{ticketNumber}', [TicketController::class, 'show'])->name('ticket.show');
    });

    Route::get('404', [PageController::class, 'notFound'])->name('404');
});

include __DIR__ . '/auth.php';
