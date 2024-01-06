<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\{
    AuthController,
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
Route::middleware('guest')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
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