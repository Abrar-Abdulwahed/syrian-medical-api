<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\ServiceProvider\ItemManagement\{
    ReviewController,
    ProductController,
    ServiceController
};

Route::name('provider')->apiResource('dashboard/products', ProductController::class)->only(['store', 'update', 'destroy']);
Route::name('provider')->apiResource('dashboard/services', ServiceController::class)->only(['store', 'update', 'destroy']);

Route::name('provider.')->prefix('dashboard/items')->group(function () {
    Route::get('/', [ReviewController::class, 'index'])->name('items.index');
    Route::get('{type}/{id}', [ReviewController::class, 'show'])->name('items.show');
});
