
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserManagementController;

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
Route::prefix('admin')->middleware(['auth:sanctum', 'can:is-super-admin'])->group(function () {
    Route::controller(UserManagementController::class)->prefix('user-management')->group(function () {
        Route::get('users', 'index');
        Route::get('patients', 'patients');
        Route::get('service-providers', 'serviceProviders');
        Route::get('user/{id}', 'show');
        Route::post('user/{id}/accept', 'ServiceProviderAccept');
        Route::post('user/{id}/refuse', 'ServiceProviderRefuse');
    });
});