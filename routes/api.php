<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\ProjectController;
use App\Http\Controllers\api\TreasuryController;
use App\Http\Controllers\api\TreasuryDetailController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// REGISTER & LOGIN
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


// AUTHENTICATED ROUTES
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/list-projects', [ProjectController::class, 'getListProject']);

    Route::get('/list-treasury', [TreasuryController::class, 'getListCash']);
    Route::post('/create-treasury', [TreasuryController::class, 'addCash']);
    Route::get('/list-treasury-detail', [TreasuryDetailController::class, 'getListDetail']);
    Route::post('/delete-treasury', [TreasuryController::class, 'deleteCash']);
    Route::post('/update-checked-treasury-detail', [TreasuryDetailController::class, 'updateTreasuryDetail']);
    Route::post('/create-new-cash', [TreasuryDetailController::class, 'createTreasuryDetail']);
    Route::post('/delete-cash', [TreasuryDetailController::class, 'deleteCash']);
    Route::post('/update-cash', [TreasuryDetailController::class, 'updateTreasuryDetail']);
    Route::get('/cash-detail', [TreasuryDetailController::class, 'getCashDetail']);
});
