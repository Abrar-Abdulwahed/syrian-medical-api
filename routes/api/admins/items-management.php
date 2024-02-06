
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ItemManagement\{
    ReviewController,
    ProductController,
    ServiceController
};
use App\Http\Controllers\Admin\{
    HomeController,
    OrderController,
    SalesController
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

/*********** Items Management ***********/
Route::name('admin')->apiResource('admin/products', ProductController::class)->only(['store', 'update', 'destroy']);
Route::name('admin')->apiResource('admin/services', ServiceController::class)->only(['store', 'update', 'destroy']);

Route::name('admin.')->prefix('admin/items')->group(function () {
    Route::get('/', [ReviewController::class, 'index'])->name('items.index');
    Route::get('{type}/{item}', [ReviewController::class, 'show'])->name('items.show');
});

/*********** Orders Management ***********/
Route::name('admin')->apiResource('admin/reservations', OrderController::class)->only(['index', 'show']);

/*********** Sales Management ***********/
Route::name('admin')->get('admin/sales', SalesController::class);

/*********** Home ***********/
Route::name('admin')->get('admin/', HomeController::class);
