<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/rice/store', [RiceController::class, 'store'])->name('rice.store');
    Route::put('/rice/{rice}', [RiceController::class, 'update'])->name('rice.update');
    Route::delete('/rice/{rice}', [RiceController::class, 'destroy'])->name('rice.destroy');

    Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store');
    Route::patch('/payments/{id}', [PaymentController::class, 'update'])->name('payments.update');
});

require __DIR__.'/auth.php';