<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\Patient\HomeController;
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

Route::prefix('patients/home')->group(function () {
    Route::controller(HomeController::class)->group(function () {
        Route::get('/', 'index');
    });
});
