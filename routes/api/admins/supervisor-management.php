
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
// Route::prefix('admin/supervisor-management')->group(function () {
    Route::resource('/supervisor-management', SupervisorController::class);
// });

