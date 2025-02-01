<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\Country\Entities\Country;

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

Route::resource('/country', 'CountryController', ['only' => ['store' ,'update', 'destroy']])->middleware('auth:api');
Route::get('/country', 'CountryController@index');
Route::get('/city', 'CityController@index');

Route::resource('/language', 'LanguageController', ['only' => ['store', 'update', 'destroy']])->middleware('auth:api');
Route::get('/language', 'LanguageController@index');
