<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;

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

Route::apiResource('products', ProductController::class)->only([
    'store', 'update', 'show'
]);

Route::apiResource('products/{product}/offers', OfferController::class)->only([
    'index', 'store'
]);
Route::delete('products/{product}/offers', [OfferController::class, 'delete']);

Route::post('order', OrderController::class);
