<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\ServiceProvider\OrderManagement\OrderController;


Route::name('provider')->apiResource('dashboard/reservations', OrderController::class)->only(['index', 'show']);
Route::controller(OrderController::class)->prefix('dashboard/reservations')->group(function () {
    Route::patch('{id}/accept', 'accept');
    Route::post('{id}/refuse', 'refuse');
});
