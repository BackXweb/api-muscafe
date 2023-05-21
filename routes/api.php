<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/user')->group(function () {
    Route::post('/login', [\App\Http\Controllers\UserController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::middleware('abilities:manager')->group(function () {
            Route::post('/store', [\App\Http\Controllers\UserController::class, 'store']);

            Route::get('/', [\App\Http\Controllers\UserController::class, 'index']);
            Route::get('/show', [\App\Http\Controllers\UserController::class, 'show']);
            Route::post('/update', [\App\Http\Controllers\UserController::class, 'update']);
            Route::delete('/destroy', [\App\Http\Controllers\UserController::class, 'destroy']);
        });

        Route::get('/logout', [\App\Http\Controllers\UserController::class, 'logout']);
    });
});
