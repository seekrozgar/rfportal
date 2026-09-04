<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Payment Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('payment')
    ->name('payment.')
    ->group(function () {
        Route::post('/initiate', [PaymentController::class, 'initiate'])->name('initiate');
        Route::get('/callback', [PaymentController::class, 'callback'])->name('callback');
        Route::get('/success', [PaymentController::class, 'success'])->name('success');
        Route::get('/failed', [PaymentController::class, 'failed'])->name('failed');
    });
