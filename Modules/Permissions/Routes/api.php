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
    Route::get('permissions-and-roles', 'PermissionsController@index');
    Route::post('role', 'PermissionsController@storeRole');
    Route::post('role-permssions/{role}', 'PermissionsController@assignPermissionsToRole');
    Route::post('user-role', 'PermissionsController@assignRoleToUser');
    Route::get('role/{role}', 'PermissionsController@showRole');
    Route::put('role/{role}', 'PermissionsController@updateRole');
    Route::delete('role/{role}', 'PermissionsController@destroyRole');
});

