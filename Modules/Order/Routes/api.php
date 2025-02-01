<?php

use Illuminate\Http\Request;

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


Route::middleware('auth:api')->group(function () {
    Route::get('buyer-orders', 'OrderController@indexBuyer');
    Route::get('seller-orders', 'OrderController@indexSeller');
    Route::get('delivery-orders', 'OrderController@indexDelivery');
    Route::get('prepared-orders', 'OrderController@indexPreparedOrders');
    Route::get('orders/reorder/{order}', 'OrderController@reorder');
    Route::get('orders/reorder-to-cart/{order}', 'OrderController@reorderToCart');
    Route::resource('orders', 'OrderController');
});
