<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\ServiceProvider\ProfileController;

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
Route::prefix('service-providers')->middleware(['auth:sanctum', 'verified', 'activated'])->group(function () {
    Route::controller(ProfileController::class)->group(function () {
        Route::get('profile-details', 'showDetails');
        Route::post('profile-details', 'updateDetails');
    });
});

