<?php

use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\CustomerTicketController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\UserController;
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
        Route::get('/{ticketNumber}/reply/index', [TicketController::class, 'replies'])->name('api.ticket.reply.index');
        Route::post('/{ticketNumber}/reply/store', [TicketController::class, 'reply'])->name('api.ticket.reply.store');
        Route::post('/{ticketNumber}/assign', [TicketController::class, 'assignToUser'])->name('api.ticket.assign');
        Route::get('/{ticketNumber}', [TicketController::class, 'show'])->name('api.ticket.show');
    });

    // Users
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('api.user.index');
    });
});
