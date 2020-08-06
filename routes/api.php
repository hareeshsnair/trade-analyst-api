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
Route::post('auth', 'UserController@store');

Route::middleware(['auth:sanctum', 'mobileVerified'])->group(function() {

    Route::apiResource('users', 'UserController')->except('store');;
    Route::post('verify-otp', 'UserController@otpVerification');

    Route::get('exchanges', 'StockExchangeController');
    Route::get('instruments', 'InstrumentTypeController');
    Route::get('trade-types', 'TradeTypeController');

    Route::apiResource('orders', 'OrderController');
});