<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManajemenUserController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PermissionController;

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
Route::get('/generate-and-send-qr-code', [QrCodeController::class, 'generateAndSendQrCode']);
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
    Route::prefix('/dashboardSuperadmin')->group(function () {
        Route::get('/Profiles', [ProfileController::class, 'index']);
        Route::put('/Profiles/update/{id}', [ProfileController::class, 'updateSuperadmin']);
    });
    // Rute lain untuk dashboard superadmin
});
Route::middleware(['auth', 'checkStatus:aktif', 'check.role:karyawan'])->group(function () {
    Route::get('/dashboardkaryawan', [DashboardController::class, 'indexKaryawan']);
    Route::prefix('/dashboardkaryawan')->group(function () {
        Route::get('/Permission', [PermissionController::class, 'index']);
        Route::get('/Permission/create', [PermissionController::class, 'create']);
        Route::post('/Permission/store', [PermissionController::class, 'store']);
        Route::get('/Permission/edit/{id}', [PermissionController::class, 'edit']);
        Route::put('/Permission/update/{id}', [PermissionController::class, 'update']);
        Route::delete('/Permission/destroy/{id}', [PermissionController::class, 'destroy']);
        Route::get('/Permission/data', [PermissionController::class, 'json']);
    });
    Route::prefix('/dashboardkaryawan')->group(function () {
        Route::get('/Profiles', [ProfileController::class, 'indexKaryawan']);
        Route::put('/Profiles/update/{id}', [ProfileController::class, 'updateKaryawan']);
    });
    // Rute lain untuk dashboard superadmin
});
// Route::middleware(['auth', 'checkStatus:nonaktif', 'check.role:superadmin'])->group(function () {
//     Route::get('/dashboardSuperadmin', [DashboardController::class, 'index']);
//     // Rute lain untuk dashboard superadmin
// });
