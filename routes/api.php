<?php

use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\CustomerTicketController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\ValidateCustomerLink;
use Illuminate\Support\Facades\Route;

// Customer routes
Route::middleware([ValidateCustomerLink::class])->prefix('customer/{link}')->group(function () {
    Route::post('ticket/{ticketNumber}/reply/store', [CustomerTicketController::class, 'reply'])->name('api.customer.ticket.reply.store');
    Route::get('ticket/{ticketNumber}', [CustomerTicketController::class, 'show'])->name('api.customer.ticket.show');
    Route::get('ticket', [CustomerTicketController::class, 'index'])->name('api.customer.ticket.index');
    Route::post('ticket/store', [CustomerTicketController::class, 'store'])->name('api.customer.ticket.store');
});

// Admin routes
Route::middleware('auth')->group(function () {
    // Customers
    Route::prefix('customer')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('api.customer.index');
        Route::post('/store', [CustomerController::class, 'store'])->name('api.customer.store');
        Route::get('/{customer}', [CustomerController::class, 'show'])->name('api.customer.show');
    });

    // Tickets
    Route::prefix('ticket')->group(function () {
        Route::get('/', [TicketController::class, 'index'])->name('api.ticket.index');
        Route::get('/count', [TicketController::class, 'counts'])->name('api.ticket.counts');
        Route::get('/averages', [TicketController::class, 'averages'])->name('api.ticket.averages');
        Route::post('/store', [TicketController::class, 'store'])->name('api.ticket.store');
        Route::post('/{ticketNumber}/reply/store', [TicketController::class, 'reply'])->name('api.ticket.reply.store');
        Route::post('/{ticketNumber}/close', [TicketController::class, 'close'])->name('api.ticket.close');
        Route::post('/{ticketNumber}/assign', [TicketController::class, 'assignToUser'])->name('api.ticket.assign');
        Route::get('/{ticketNumber}', [TicketController::class, 'show'])->name('api.ticket.show');
    });

    // Users
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('api.user.index');
        Route::post('/store', [UserController::class, 'store'])->name('api.user.store');
        Route::delete('/{user}/destroy', [UserController::class, 'destroy'])->name('api.user.destroy');
    });
});
