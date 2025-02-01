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

Route::resource('menus', 'MenuController', ['only' => ['index']]);

Route::middleware('auth:api')->group(function () {
    Route::resource('menus', 'MenuController', ['only' => ['store', 'update', 'destroy']]);

    Route::get('menus/{menu}/detach/{id}', 'MenuController@detachProduct');
});
