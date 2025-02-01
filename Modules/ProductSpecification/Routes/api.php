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

Route::resource('product-specifications', 'ProductSpecificationController', ['only' => ['index']]);


Route::middleware('auth:api')->group(function () {
    Route::resource('product-specifications', 'ProductSpecificationController', ['only' => ['store', 'update', 'destroy']]);

    Route::resource('product-specification-options', 'ProductSpecificationOptionController', ['only' => ['store', 'update', 'destroy']]);
});
