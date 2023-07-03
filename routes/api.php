<?php

use App\Http\Controllers\PlayerController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\StyleController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AdController;

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

Route::controller(UserController::class)->group(function () {
    Route::post('/login', 'login');
    Route::middleware('auth:sanctum')->get('/logout', 'logout');

    Route::prefix('/user')->group(function () {
        Route::middleware(['auth:sanctum'])->get('/get-user', 'get_user');

        Route::middleware(['auth:sanctum', 'abilities:manager'])->group(function () {
            Route::post('/store', 'store');
            Route::get('/', 'index');
            Route::get('/show', 'show');
            Route::post('/update', 'update');
            Route::delete('/destroy', 'destroy');

            Route::get('/login-manager', 'login_manager');
            Route::get('/reset-link', 'reset_link');
        });

        Route::get('/check-reset-link', 'check_reset_link');
        Route::post('/reset-password', 'reset_password');
    });
});

Route::prefix('/role')->middleware(['auth:sanctum', 'abilities:manager'])->controller(RoleController::class)->group(function () {
    Route::get('/', 'index');
});

Route::prefix('/manager')->middleware(['auth:sanctum', 'abilities:manager'])->controller(ManagerController::class)->group(function () {
    Route::post('/store', 'store');
    Route::get('/', 'index');
    Route::get('/show', 'show');
    Route::post('/update', 'update');
    Route::delete('/destroy', 'destroy');
});

Route::prefix('/facility')->middleware(['auth:sanctum', 'abilities:user'])->controller(FacilityController::class)->group(function () {
    Route::post('/store', 'store');
    Route::get('/', 'index');
    Route::get('/show', 'show');
    Route::post('/update', 'update');
    Route::delete('/destroy', 'destroy');
});

Route::prefix('/playlist')->middleware(['auth:sanctum', 'abilities:user'])->controller(PlaylistController::class)->group(function () {
    Route::post('/store', 'store');
    Route::get('/', 'index');
    Route::get('/show', 'show');
    Route::post('/update', 'update');
    Route::delete('/destroy', 'destroy');
});

Route::prefix('/style')->middleware(['auth:sanctum', 'abilities:user'])->controller(StyleController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/show', 'show');
    Route::get('/list-music', 'music');
});

Route::prefix('/ad')->middleware(['auth:sanctum', 'abilities:user'])->controller(AdController::class)->group(function () {
    Route::post('/store', 'store');
    Route::get('/', 'index');
    Route::get('/show', 'show');
    Route::post('/update', 'update');
    Route::delete('/destroy', 'destroy');
});

Route::prefix('/player')->controller(PlayerController::class)->group(function () {
    Route::get('/check-token', 'check_token');

    Route::middleware(['auth:sanctum', 'abilities:player'])->group(function () {
        Route::get('/show', 'show');
    });
});
