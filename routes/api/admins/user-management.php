
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserManagement\{
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
        Route::controller(ApplicantController::class)->prefix('registration-requests')->group(function(){
            Route::get('/', 'index');
            Route::post('/{id}/accept', 'accept');
            Route::post('/{id}/refuse', 'refuse');
        });

        Route::controller(ProfileUpdateRequests::class)->prefix('profile-update-requests')->group(function(){
            Route::get('/', 'showUserProfileUpdateRequests');
            Route::get('review', 'reviewUserProfileUpdateRequests');
        });

        // Route::get('users', 'index'); // all users, + fetch by type(patient, service-provider)
        // Route::get('user/{id}', 'show')->name('show.user');
});