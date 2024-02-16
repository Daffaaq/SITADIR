<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManajemenUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::middleware(['guest'])->group(function () {
    Route::get('/', [
        LoginController::class, 'index'
    ])->name('login');
    Route::post('/', [LoginController::class, 'login']);
});
Route::get('/logout', [LoginController::class, 'logout']);
Route::middleware(['auth', 'checkStatus:aktif', 'check.role:superadmin'])->group(function () {
    Route::get('/dashboardSuperadmin', [DashboardController::class, 'index']);
    Route::prefix('/dashboardSuperadmin')->group(function () {
        Route::get('/Users', [ManajemenUserController::class, 'index']);
        Route::get('/Users/create', [ManajemenUserController::class, 'create']);
        Route::post('/Users/store', [ManajemenUserController::class, 'store']);
        Route::get('/Users/edit/{id}', [ManajemenUserController::class, 'edit']);
        Route::put('/Users/update/{id}', [ManajemenUserController::class, 'update']);
        Route::delete('/Users/destroy/{id}', [ManajemenUserController::class, 'destroy']);
        Route::get('/Users/data', [ManajemenUserController::class, 'json']);
    });
    // Rute lain untuk dashboard superadmin
});
// Route::middleware(['auth', 'checkStatus:nonaktif', 'check.role:superadmin'])->group(function () {
//     Route::get('/dashboardSuperadmin', [DashboardController::class, 'index']);
//     // Rute lain untuk dashboard superadmin
// });
