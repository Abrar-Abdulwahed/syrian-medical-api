<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\OrderController;

Route::name('admin')->apiResource('admin/reservations', OrderController::class)->only(['index', 'show']);
