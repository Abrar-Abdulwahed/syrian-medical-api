<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
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

include __DIR__ . '/api/users/auth.php';

include __DIR__ . '/api/users/service-providers/profile.php';
include __DIR__ . '/api/users/service-providers/items-management.php';


include __DIR__ . '/api/users/patients/profile.php';
include __DIR__ . '/api/users/patients/home.php';

include __DIR__ . '/api/admins/auth.php';
include __DIR__ . '/api/admins/user-management.php';
include __DIR__ . '/api/admins/items-management.php';
