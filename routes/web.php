<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManajemenUserController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SupervisorPermissionController;
use App\Http\Controllers\AbsensiManualController;
use App\Http\Controllers\AbsensiLiveLocationController;
use App\Http\Controllers\AbsensiQRCodeController;
use App\Http\Controllers\AbsensiSuperadminController;
use App\Http\Controllers\LaporanAbsensiController;
use App\Http\Controllers\SendQrCodeController;

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
    Route::prefix('/dashboardSuperadmin')->group(function () {
        Route::get('/absensi', [AbsensiSuperadminController::class, 'index']);
        Route::get('/absensi/data/{userId}', [AbsensiSuperadminController::class, 'json'])->name('get.recap.Attendence.Superadmin');
    });
    Route::prefix('/dashboardSuperadmin')->group(function () {
        Route::get('/send-qr-code-to-karyawan', [SendQrCodeController::class, 'index']);
        Route::post('/send-qr-code-to-karyawan/send/datang/{id}', [SendQrCodeController::class, 'sendQrCodeToUserDatang']);
        Route::post('/send-qr-code-to-karyawan/send/pulang/{id}', [SendQrCodeController::class, 'sendQrCodeToUserPulang']);
        Route::get('/send-qr-code-to-karyawan/data', [SendQrCodeController::class, 'json']);
    });
    // Rute lain untuk dashboard superadmin
});
Route::middleware(['auth', 'checkStatus:aktif', 'check.role:karyawan'])->group(function () {
    Route::get('/dashboardkaryawan', [DashboardController::class, 'indexKaryawan']);
    Route::prefix('/dashboardkaryawan')->group(function () {
        Route::get('/Permission', [PermissionController::class, 'index']);
        Route::get('/Permission/create', [PermissionController::class, 'create']);
        Route::post('/Permission/store', [PermissionController::class, 'store']);
        Route::get('/Permission/show/{id}', [PermissionController::class, 'show']);
        Route::get('/Permission/edit/{id}', [PermissionController::class, 'edit']);
        Route::put('/Permission/update/{id}', [PermissionController::class, 'update']);
        Route::delete('/Permission/destroy/{id}', [PermissionController::class, 'destroy']);
        Route::get('/Permission/data', [PermissionController::class, 'json']);
    });
    Route::prefix('/dashboardkaryawan')->group(function () {
        Route::get('/Profiles', [ProfileController::class, 'indexKaryawan']);
        Route::put('/Profiles/update/{id}', [ProfileController::class, 'updateKaryawan']);
    });
    Route::prefix('/dashboardkaryawan')->group(function () {
        Route::get('/Qr-Code', [SendQrCodeController::class, 'indexKaryawan']);
    });
    Route::prefix('/dashboardkaryawan')->group(function () {
        Route::get('/Absensi/Manual', [AbsensiManualController::class, 'index']);
        Route::post('/Absensi/Manual/Store', [AbsensiManualController::class, 'storeDatang']);
        Route::put('/Absensi/Manual/Pulang/{id}', [AbsensiManualController::class, 'storePulang']);
        Route::get('/Absensi/Manual/data', [AbsensiManualController::class, 'json']);
    });
    Route::prefix('/dashboardkaryawan')->group(function () {
        Route::get('/Absensi/QrCode', [AbsensiQRCodeController::class, 'index']);
        Route::get('/Absensi/QrCode/datang', [AbsensiQRCodeController::class, 'datang']);
        Route::post('/Absensi/QrCode/datang/scan/Store', [AbsensiQRCodeController::class, 'scanQrCodeDatang'])->name('scan.qrcode.datang');
        Route::post('/Absensi/QrCode/upload/Store', [AbsensiQRCodeController::class, 'uploadQrCodeDatang']);
        Route::put('/Absensi/QrCode/scan/Pulang/{id}', [AbsensiQRCodeController::class, 'scanQrCodePulang']);
        Route::put('/Absensi/QrCode/upload/Pulang/{id}', [AbsensiQRCodeController::class, 'uploadQrCodePulang']);
        Route::get('/Absensi/QrCode/data', [AbsensiQRCodeController::class, 'json']);
    });
    Route::prefix('/dashboardkaryawan')->group(function () {
        Route::get('/Absensi/LiveLocation', [AbsensiLiveLocationController::class, 'index']);
        Route::get('/Absensi/LiveLocation/datang', [AbsensiLiveLocationController::class, 'create']);
        Route::post('/Absensi/LiveLocation/datang/storeDatang', [AbsensiLiveLocationController::class, 'storeDatang']);
        Route::get('/Absensi/LiveLocation/pulang/{id}', [AbsensiLiveLocationController::class, 'edit']);
        Route::put('/Absensi/LiveLocation/pulang/updateDatang/{id}', [AbsensiLiveLocationController::class, 'storePulang']);
        Route::get('/Absensi/LiveLocation/edit/letter/{id}', [AbsensiLiveLocationController::class, 'edit_letter']);
        Route::put('/Absensi/LiveLocation/Update/letter/update_letter/{id}', [AbsensiLiveLocationController::class, 'update_letter']);
        Route::get('/Absensi/LiveLocation/show/{id}', [AbsensiLiveLocationController::class, 'show']);  // Route untuk pembaruan letter of assignment
        Route::get('/Absensi/LiveLocation/data', [AbsensiLiveLocationController::class, 'json']);
    });
    Route::prefix('/dashboardkaryawan')->group(function () {
        Route::get('/Laporan/Absensi', [LaporanAbsensiController::class, 'index']);
        Route::get('/Laporan/Absensi/data', [LaporanAbsensiController::class, 'json']);
    });
    // Rute lain untuk dashboard superadmin
});
Route::middleware(['auth', 'checkStatus:aktif', 'check.role:supervisor'])->group(function () {
    Route::get('/dashboardsupervisor', [DashboardController::class, 'indexsupervisor']);
    Route::prefix('/dashboardsupervisor')->group(function () {
        Route::get('/Rekap_Permission', [SupervisorPermissionController::class, 'index']);
        Route::get('/Rekap_Permission/show/{id}', [SupervisorPermissionController::class, 'show']);
        Route::get('/Rekap_Permission/accepted/{id}', [SupervisorPermissionController::class, 'approveindex']);
        Route::get('/Rekap_Permission/rejected/{id}', [SupervisorPermissionController::class, 'rejectindex']);
        Route::put('/Rekap_Permission/accepted/update/{id}', [SupervisorPermissionController::class, 'approve']);
        Route::put('/Rekap_Permission/rejected/update/{id}', [SupervisorPermissionController::class, 'reject']);
        Route::get('/Rekap_Permission/data/{userId}', [SupervisorPermissionController::class, 'json'])->name('get.recap.Permission.supervisor');
    });
    Route::prefix('/dashboardsupervisor')->group(function () {
        Route::get('/Profiles', [ProfileController::class, 'indexSupervisor']);
        Route::put('/Profiles/update/{id}', [ProfileController::class, 'updateSupervisor']);
    });
    // Rute lain untuk dashboard superadmin
});
// Route::middleware(['auth', 'checkStatus:nonaktif', 'check.role:superadmin'])->group(function () {
//     Route::get('/dashboardSuperadmin', [DashboardController::class, 'index']);
//     // Rute lain untuk dashboard superadmin
// });
