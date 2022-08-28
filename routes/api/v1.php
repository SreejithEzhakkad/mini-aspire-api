<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\CustomerAuthController;
use App\Http\Controllers\Api\V1\Auth\AdminAuthController;
use App\Http\Controllers\Api\V1\LoanController;


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

/**
 * Auth Routes
 */
Route::prefix('auth')->group(function () {
    
    //Customer Auth
    
    Route::prefix('customer')->name('customer.')->group(function () {
        Route::post('register', [CustomerAuthController::class, 'register'])->name('register');
        Route::post('login', [CustomerAuthController::class, 'login'])->name('login');
        Route::middleware(['auth:sanctum', 'ability:customer.*'])->group(function () {
            Route::post('logout', [CustomerAuthController::class, 'logout'])->name('logout');
        });
    });
    
    // Admin Auth

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::post('register', [AdminAuthController::class, 'register'])->name('register');
        Route::post('login', [AdminAuthController::class, 'login'])->name('login');
        Route::middleware(['auth:sanctum', 'ability:admin.*'])->group(function () {
            Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
        });
    });

});

/**
 * Loan Routes
 */
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('loans')->name('loans.')->group(function () {
        Route::post('request', [LoanController::class, 'loanRequest'])->middleware('ability:customer.*')->name('request');
        Route::get('/', [LoanController::class, 'index'])->name('index');
        Route::get('{loan}', [LoanController::class, 'show'])->name('show');
        Route::post('{loan}/repayments/{repayment}/pay', [LoanController::class, 'repay'])->middleware(['ability:customer.*'])->name('repay');
        Route::post('{loan}/approve', [LoanController::class, 'approve'])->middleware('ability:admin.*')->name('approve');
    });
});

