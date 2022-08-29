<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Customer\AuthController;
use App\Http\Controllers\Api\V1\Customer\LoanController;


/*
|--------------------------------------------------------------------------
| API V1 Customer Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Guest Routes

Route::prefix('auth')->group(function () {
    //Auth
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');
});

Route::middleware(['auth:sanctum', 'ability:customer.*'])->group(function () {
    //Auth 
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    });

    //Loan Routes
    Route::prefix('loans')->name('loans.')->group(function () {
        Route::post('request', [LoanController::class, 'loanRequest'])->name('request');
        Route::get('/', [LoanController::class, 'index'])->name('index');
        Route::get('{loan}', [LoanController::class, 'show'])->name('show');
        Route::post('{loan}/repayments/{repayment}/pay', [LoanController::class, 'repay'])->name('repay');
    });
});
