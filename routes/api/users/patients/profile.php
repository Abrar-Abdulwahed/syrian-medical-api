<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\PaymentMethodController;
use App\Http\Controllers\User\Patient\ProfileController;

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

Route::prefix('dashboard/patients/profile')->group(function () {
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/', 'showDetails');
        Route::post('/update', 'updateDetails');
        Route::post('/change-picture', 'updatePicture');
        Route::post('/change-location', 'updateLocation');
    });
});
Route::apiResource('dashboard/patients/payment-method', PaymentMethodController::class)->only(['index', 'store']);
