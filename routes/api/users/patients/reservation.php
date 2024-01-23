<?php

use App\Http\Controllers\User\Patient\ReservationController;
use Illuminate\Support\Facades\Route;

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

Route::apiResource('/patients/reservations', ReservationController::class)->only('index', 'show', 'destroy');
Route::post('/patients/items/{type}/{item}/reserve', [ReservationController::class, 'store'])
    ->name('reservations.make');
