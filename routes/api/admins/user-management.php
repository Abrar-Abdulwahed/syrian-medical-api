
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
Route::middleware(['auth:sanctum', 'can:is-super-admin'])->group(function () {
    Route::controller(UserManagementController::class)->prefix('admin/user-management')->group(function () {
        Route::get('users', 'index');
        Route::get('patients', 'patients');
        Route::get('service-providers', 'serviceProviders');
        Route::get('user/{user}', 'show');
        Route::post('user/{user}/accept', 'ServiceProviderAccept');
        Route::post('user/{user}/refuse', 'ServiceProviderRefuse');
    });
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});