<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\Patient\{
    PaymentController,
    ReservationController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::name('patient')->apiResource('patients/reservations', ReservationController::class)->only('index', 'show', 'destroy');
Route::post('patients/items/{type}/{item}/reserve', [ReservationController::class, 'store'])
    ->name('reservations.make');

Route::post('patients/pay', PaymentController::class)->name('patients.pay');
