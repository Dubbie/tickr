<?php

use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\CustomerTicketController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Middleware\ValidateCustomerLink;
use Illuminate\Support\Facades\Route;

// Customer routes
Route::middleware([ValidateCustomerLink::class])->prefix('customer/{link}')->group(function () {
    Route::get('ticket', [CustomerTicketController::class, 'index'])->name('api.customer.ticket.index');
    Route::post('ticket/store', [CustomerTicketController::class, 'store'])->name('api.customer.ticket.store');
});

// Admin routes
Route::middleware('auth')->group(function () {
    // Customers
    Route::prefix('customer')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('api.customer.index');
    });

    // Tickets
    Route::prefix('ticket')->group(function () {
        Route::get('/', [TicketController::class, 'index'])->name('api.ticket.index');
    });
});
