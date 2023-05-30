<?php

use Illuminate\Support\Facades\Route;

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

Route::post('/login', [\App\Http\Controllers\UserController::class, 'login']);
Route::middleware('auth:sanctum')->get('/logout', [\App\Http\Controllers\UserController::class, 'logout']);

Route::prefix('/user')->group(function () {
    Route::middleware(['auth:sanctum', 'abilities:manager'])->group(function () {
        Route::post('/store', [\App\Http\Controllers\UserController::class, 'store']);
        Route::get('/', [\App\Http\Controllers\UserController::class, 'index']);
        Route::get('/show', [\App\Http\Controllers\UserController::class, 'show']);
        Route::post('/update', [\App\Http\Controllers\UserController::class, 'update']);
        Route::delete('/destroy', [\App\Http\Controllers\UserController::class, 'destroy']);

        Route::get('/login-manager', [\App\Http\Controllers\UserController::class, 'login_manager']);
        Route::get('/reset-link', [\App\Http\Controllers\UserController::class, 'reset_link']);
    });

    Route::middleware(['auth:sanctum'])->get('/get-user', [\App\Http\Controllers\UserController::class, 'get_user']);

    Route::get('/check-reset-link', [\App\Http\Controllers\UserController::class, 'check_reset_link']);
    Route::post('/reset-password', [\App\Http\Controllers\UserController::class, 'reset_password']);
});

Route::prefix('/manager')->group(function () {
    Route::middleware(['auth:sanctum', 'abilities:manager'])->group(function () {
        Route::post('/store', [\App\Http\Controllers\ManagerController::class, 'store']);
        Route::get('/', [\App\Http\Controllers\ManagerController::class, 'index']);
        Route::get('/show', [\App\Http\Controllers\ManagerController::class, 'show']);
        Route::post('/update', [\App\Http\Controllers\ManagerController::class, 'update']);
        Route::delete('/destroy', [\App\Http\Controllers\ManagerController::class, 'destroy']);
    });
});

Route::prefix('/facility')->group(function () {
    Route::middleware(['auth:sanctum', 'abilities:user'])->group(function () {
        Route::post('/store', [\App\Http\Controllers\FacilityController::class, 'store']);
        Route::get('/', [\App\Http\Controllers\FacilityController::class, 'index']);
        Route::get('/show', [\App\Http\Controllers\FacilityController::class, 'show']);
        Route::post('/update', [\App\Http\Controllers\FacilityController::class, 'update']);
        Route::delete('/destroy', [\App\Http\Controllers\FacilityController::class, 'destroy']);
    });
});

Route::prefix('/playlist')->group(function () {
    Route::middleware(['auth:sanctum', 'abilities:user'])->group(function () {
        Route::post('/store', [\App\Http\Controllers\PlaylistController::class, 'store']);
        Route::get('/', [\App\Http\Controllers\PlaylistController::class, 'index']);
        Route::get('/show', [\App\Http\Controllers\PlaylistController::class, 'show']);
        // Route::post('/update', [\App\Http\Controllers\PlaylistController::class, 'update']);
        Route::delete('/destroy', [\App\Http\Controllers\PlaylistController::class, 'destroy']);
    });
});

Route::prefix('/style')->group(function () {
    Route::middleware(['auth:sanctum', 'abilities:user'])->group(function () {
        Route::get('/', [\App\Http\Controllers\StyleController::class, 'index']);
        Route::get('/show', [\App\Http\Controllers\StyleController::class, 'show']);
        Route::get('/list-music', [\App\Http\Controllers\StyleController::class, 'music']);
    });
});
