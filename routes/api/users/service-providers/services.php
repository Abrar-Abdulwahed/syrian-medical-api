<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\ServiceProvider\ServiceController;

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
// Route::controller(ServiceController::class)->prefix('dashboard/providers/services')->group(function () {
//     Route::get('{user}/services', 'index');
//     Route::post('{user}/services', 'store');
//     Route::put('{user}/services/{service}', 'update');
//     Route::get('{user}/services/{service}', 'show');
//     Route::delete('{user}/services/{service}', 'destroy');
// });
Route::apiResource('dashboard/providers/services', ServiceController::class);

