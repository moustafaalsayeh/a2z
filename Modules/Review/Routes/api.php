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

Route::get('review-items', 'ReviewableController@index');
Route::get('reviews', 'ReviewController@index');

Route::middleware('auth:api')->group(function () {

    Route::resource('review-items', 'ReviewableController', ['only' => ['store', 'update', 'destroy']]);
    Route::resource('reviews', 'ReviewController', ['only' => ['store', 'update', 'destroy']]);
    Route::put('review-rates/{review_rate}', 'ReviewRateController@update');

});
