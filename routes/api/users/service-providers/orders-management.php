<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\ServiceProvider\OrderController;


Route::name('provider')->apiResource('providers/reservations', OrderController::class)->only(['index', 'show']);
Route::controller(OrderController::class)->prefix('providers/reservations')->group(function () {
    Route::patch('{id}/accept', 'accept');
    Route::post('{id}/refuse', 'refuse');
});
