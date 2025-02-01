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

Route::resource('outlets', 'OutletController', ['only' => ['show', 'index']]);

Route::get('delivery-areas', 'DeliveryAreaController@index');

Route::middleware('auth:api')->group(function () {
    Route::resource('work-hour', 'WorkHourController', ['only' => ['store', 'update', 'destroy']]);

    Route::resource('outlets', 'OutletController', ['only' => ['store', 'update', 'destroy']]);

    Route::resource('delivery-areas', 'DeliveryAreaController', ['only' => ['store', 'update', 'destroy']]);

    Route::get('outlet-covers-location/{outlet}/{location}', 'OutletController@coversLocation');

    Route::get('my-outlets', 'OutletController@indexUser');

    Route::delete('outlets/{outlet}/media/{media}', 'OutletController@destoryMedia');
    Route::put('outlets/{outlet}/media/{media}', 'OutletController@updateMedia');
});
