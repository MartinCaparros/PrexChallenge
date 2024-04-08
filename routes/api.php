<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\UserGifController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('log')->group(function () {
    Route::withoutMiddleware('auth:api')->prefix('user')->group(function () {
        Route::post('login', [UserController::class, 'login']);
        Route::post('register', [UserController::class, 'register']);
    });

    Route::middleware('auth:api')->prefix('gif')->group(function () {
        Route::get('search', [UserGifController::class, 'search']);
        Route::get('search/byId', [UserGifController::class, 'searchById']);
        Route::post('save/favorite', [UserGifController::class, 'storeFavorite']);
    })->name('gif::');
});

