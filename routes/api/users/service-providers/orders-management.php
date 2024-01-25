<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\ServiceProvider\OrderManagement\OrderController;


Route::name('provider')->apiResource('dashboard/orders', OrderController::class);
