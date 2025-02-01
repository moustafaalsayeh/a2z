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
    Route::resource('saves', 'SavesController', ['only', ['index', 'store', 'destroy']]);
    Route::resource('save-collections', 'SaveCollectionController', ['only', ['index', 'store', 'update', 'destroy']]);

    Route::delete('save-collections/{save_collection}/media/{media}', 'SaveCollectionController@destoryMedia');
    Route::put('save-collections/{save_collection}/media/{media}', 'SaveCollectionController@updateMedia');
});
