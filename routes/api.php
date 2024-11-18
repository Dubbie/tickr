<?php

use App\Http\Controllers\Api\CustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group([
    'prefix' => 'customer',
    'middleware' => 'auth'
], function () {
    Route::get('/', [CustomerController::class, 'index'])->name('api.customer.index');
});

Route::get('/customer/id/verify', [CustomerController::class, 'verifyId'])->name('api.customer.id.verify');
