<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;

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
Route::middleware(['auth'])->group(function () {
    Route::middleware(['check.role:superadmin'])->group(function () {
        Route::get('/dashboardSuperadmin', [DashboardController::class, 'index']);
    });
});
