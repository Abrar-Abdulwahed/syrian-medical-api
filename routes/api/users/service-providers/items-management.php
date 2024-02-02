<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\ServiceProvider\ItemManagement\{
    ReviewController,
    ProductController,
    ServiceController
};
use App\Http\Controllers\User\ServiceProvider\OrderController;

/*********** Items Management ***********/
Route::name('provider')->apiResource('providers/products', ProductController::class)->only(['store', 'update', 'destroy']);
Route::name('provider')->apiResource('providers/services', ServiceController::class)->only(['store', 'update', 'destroy']);

Route::name('provider.')->prefix('providers/items')->group(function () {
    Route::get('/', [ReviewController::class, 'index'])->name('items.index');
    Route::get('{type}/{item}', [ReviewController::class, 'show'])->name('items.show');
});

/*********** Orders Management ***********/
Route::name('provider')->apiResource('providers/reservations', OrderController::class)->only(['index', 'show']);
Route::controller(OrderController::class)->prefix('providers/reservations')->group(function () {
    Route::patch('{item}/accept', 'accept');
    Route::post('{id}/refuse', 'refuse');
});
