
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ItemManagement\{
    ReviewController,
    ProductController,
    ServiceController
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

Route::name('admin')->apiResource('admin/products', ProductController::class)->only(['store', 'update', 'destroy']);
Route::name('admin')->apiResource('admin/services', ServiceController::class)->only(['store', 'update', 'destroy']);

Route::name('admin.')->prefix('admin/items')->group(function () {
    Route::get('/', [ReviewController::class, 'index'])->name('items.index');
    Route::get('{type}/{id}', [ReviewController::class, 'show'])->name('items.show');
});
