
<?php

use Illuminate\Support\Facades\Route;
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
Route::prefix('admin/user-management')->group(function () {
        Route::controller(UserController::class)->prefix('users')->group(function(){
            Route::get('/', 'index'); // all users, + fetch by type(patient, service-provider)
            Route::get('{id}', 'show')->name('show.user');
        });

        Route::controller(ApplicantController::class)->prefix('registration-requests')->group(function(){
            Route::get('/', 'index');
            Route::post('/{id}/accept', 'accept');
            Route::delete('/{id}/refuse', 'refuse');
        });

        Route::controller(ProfileUpdateRequests::class)->prefix('profile-update-requests')->group(function(){
            Route::get('/', 'index');
            Route::post('{pending}/accept', 'accept');
            Route::delete('{pending}/refuse', 'refuse');
        });
});