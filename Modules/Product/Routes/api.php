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
Route::resource('product', 'ProductController', ['only' => ['index', 'show']]);

Route::resource('product-types', 'ProductTypeController', ['only' => ['index', 'show']]);
Route::get('product-types-list', 'ProductTypeController@list');

Route::middleware('auth:api')->group(function () {
    Route::resource('product', 'ProductController', ['only' => ['store', 'update', 'destroy']]);

    Route::resource('product-types', 'ProductTypeController', ['only' => ['store', 'update', 'destroy']]);

    Route::resource('carts', 'CartController', ['only' => ['index', 'store', 'destroy']]);
    Route::get('carts-admin', 'CartController@indexAdmin');
    Route::get('carts-admin/{cart}', 'CartController@show');
    Route::put('cart-items/{cart_item}', 'CartController@updateItem');
    Route::delete('cart-items/{cart_item}', 'CartController@removeItem');

    Route::get('my-products', 'ProductController@indexUser');

    Route::delete('product/{product}/media/{media}', 'ProductController@destoryMedia');
    Route::put('product/{product}/media/{media}', 'ProductController@updateMedia');

    Route::delete('product-types/{product_type}/media/{media}', 'ProductTypeController@destoryMedia');
    Route::put('product-types/{product_type}/media/{media}', 'ProductTypeController@updateMedia');
});
