
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SupervisorManagement\{
    SupervisorController,
    PowerController
};
use App\Http\Controllers\Admin\UserManagement\{
    UserController,
    ApplicantController,
    ProfileUpdateRequests
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

/*********** USERS [PATIENT | SERVICE PROVIDER] ***********/
Route::prefix('admin')->group(function () {
    Route::controller(ApplicantController::class)->prefix('applicants')->group(function () {
        Route::get('/', 'index');
        Route::get('/{user}', 'show')->name('admin.show.applicant');
        Route::put('{id}/accept', 'accept');
        Route::delete('{id}/refuse', 'refuse');
    });

    Route::controller(ProfileUpdateRequests::class)->prefix('users/profile-update-requests')->group(function () {
        Route::get('/', 'index');
        Route::put('{pending}/accept', 'accept');
        Route::delete('{pending}/refuse', 'refuse');
    });

    Route::controller(UserController::class)->prefix('users')->group(function () {
        Route::get('/', 'index');
        Route::get('{id}', 'show')->name('admin.show.user');
        Route::put('{id}/activation', 'activation');
    });
});

/*********** SUPERVISORS ***********/
Route::apiResource('admin/supervisors', SupervisorController::class);
Route::controller(SupervisorController::class)->prefix('admin/supervisors')->group(function () {
    Route::put('{supervisor}/deactivate', 'deactivate');
    Route::put('{supervisor}/activate', 'activate');
});
Route::apiResource('admin/supervisors/{supervisor}/permissions', PowerController::class)->only('index', 'store');
