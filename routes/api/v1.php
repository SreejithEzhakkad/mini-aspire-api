<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\CustomerAuthController;
use App\Http\Controllers\Api\V1\Auth\AdminAuthController;


/*
|--------------------------------------------------------------------------
| API V1 Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('customer')->name('customer.')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('register', [CustomerAuthController::class, 'register'])->name('register');
        Route::post('login', [CustomerAuthController::class, 'login'])->name('login');
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [CustomerAuthController::class, 'logout'])->name('logout');
        });
    });
});


Route::prefix('admin')->name('admin.')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('register', [AdminAuthController::class, 'register'])->name('register');
        Route::post('login', [AdminAuthController::class, 'login'])->name('login');
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
        });
    });
});
