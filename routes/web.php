<?php

use App\Http\Controllers\OrdersController;
use App\Http\Controllers\PaymentsController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::get('/orders',[ OrdersController::class, 'index']);
Route::post('/orders', [OrdersController::class, 'store']);

Route::get('/payments', [PaymentsController::class, 'index']);
Route::post('/payments', [PaymentsController::class, 'store']);
