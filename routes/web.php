<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TreasuryController;

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

Route::get('/treasury/cash', [TreasuryController::class, 'cash'])->middleware('global.auth');
