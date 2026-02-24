<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TreasuryController;
// use App\Http\Controllers\DebtController;
use App\Http\Controllers\SettingsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [DashboardController::class, 'index'])->middleware('global.auth');

Route::get('/login', [App\Http\Controllers\UserController::class, 'login']);
Route::get('/register', [App\Http\Controllers\UserController::class, 'register'])->name('register');
Route::get('/logout', [App\Http\Controllers\UserController::class, 'logout'])->name('logout');

Route::post('/save-token', [App\Http\Controllers\UserController::class, 'saveTokenToSession'])->name('save.token');

Route::prefix('treasury')->group(function () {
    Route::get('/', [TreasuryController::class, 'index'])->middleware('global.auth');
    Route::get('/detail', [TreasuryController::class, 'detail'])->middleware('global.auth');
});

Route::get('/settings', [SettingsController::class, 'index'])->middleware('global.auth');

// Route::prefix('debt')->group(function () {
//     Route::get('/', [DebtController::class, 'index'])->middleware('global.auth');
// });
