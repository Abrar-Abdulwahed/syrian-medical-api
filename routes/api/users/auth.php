<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{
    RegisterController,
    LoginController,
    ChangePasswordController,
    ForgotPasswordController
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

Route::controller(RegisterController::class)->prefix('register')->group(function () {
        Route::post('email/verify', 'EmailVerify')->name('verification.verify');
        Route::post('patient', 'storePatient');
        Route::post('service-provider', 'storeServiceProvider');
});
Route::controller(LoginController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('login/verify', 'verify2FA');
    Route::post('logout', 'logout')->name('logout');
});
Route::controller(ForgotPasswordController::class)->group(function () {
    Route::post('forgot-password', 'forgotPassword')->name('password.reset');
    Route::post('forgot-password/verify', 'verify');
    Route::post('reset-password', 'resetPassword');
});
Route::post('change-password', [ChangePasswordController::class, 'changePassword']);

