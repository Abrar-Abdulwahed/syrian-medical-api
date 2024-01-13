
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SupervisorManagement\SupervisorController;
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

Route::apiResource('admin/supervisors', SupervisorController::class);
Route::put('admin/supervisors/{supervisor}/deactivate', [SupervisorController::class, 'deactivate']);
Route::put('admin/supervisors/{supervisor}/activate', [SupervisorController::class, 'activate']);


