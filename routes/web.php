<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index']);

Route::get('/api/orders', [DashboardController::class, 'apiOrders'])->name('api.orders');

Route::post('/import-data', [ImportController::class, 'import'])->name('import.data');
