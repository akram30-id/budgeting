<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

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

Route::post('/save-token', [App\Http\Controllers\UserController::class, 'saveTokenToSession'])->name('save.token');