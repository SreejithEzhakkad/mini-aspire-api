<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Customer\AuthController as CustomerAuthController;
use App\Http\Controllers\Api\V1\Admin\AuthController as AdminAuthController;
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
 * Customer Routes
 */

Route::prefix('customer')->name('customer.')
->group(base_path('routes/api/v1/customer.php'));


/**
 * Admin Routes
 */

Route::prefix('admin')->name('admin.')
->group(base_path('routes/api/v1/admin.php'));

