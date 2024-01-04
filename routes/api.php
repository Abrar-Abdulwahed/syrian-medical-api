<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;

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
Route::middleware('guest')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::prefix('register')->group(function () {
            Route::post('patient', 'storePatient');
            Route::post('service-provider', 'storeServiceProvider');
        });
        Route::post('login', 'login');
        Route::post('login/verify', 'verify2FA');
    });

    Route::controller(ForgotPasswordController::class)->group(function () {
        Route::post('forgot-password', 'forgotPassword');
        Route::post('forgot-password/verify', 'verify');
        Route::post('reset-password', 'resetPassword');
    });
});
Route::middleware('auth:sanctum')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('logout', 'logout');
        Route::post('change-password', 'changePassword');
    });

});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
