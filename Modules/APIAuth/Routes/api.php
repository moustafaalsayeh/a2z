<?php

use Illuminate\Support\Facades\Route;

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
Route::post('login', 'AuthController@login');

Route::post('register', 'AuthController@registerQueue');
// Route::post('accept-invitation', 'AuthController@acceptInvitation');
Route::group([
    'prefix' => 'password'
], function () {
    Route::post('send-reset-email', 'AuthController@sendResetLinkEmail');
    Route::post('reset', 'AuthController@resetPassword');
});
Route::get('verify-email/{email}/{verifyToken}', 'AuthController@verifyEmail')->name('sendVerifyEmail');
Route::get('verify-phone/{phone}/{verifyCode}', 'AuthController@verifyPhone')->name('sendVerifyPhone');
Route::get('resend-verify-mail/{email}', 'AuthController@resendVerifyEmail');
Route::get('resend-verify-phone/{phone}', 'AuthController@resendVerifyPhone');
Route::middleware('auth:api')->post('resend-verify-email', 'AuthController@resendVerifyEmail')->name('resendVerifyEmail');

Route::middleware('auth:api')->group(function (){

    Route::post('logout', 'AuthController@logout');

    Route::get('user', 'UserController@index');
    Route::get('all-users', 'UserController@indexAll');
    Route::post('user', 'UserController@update');
    Route::delete('user/{user}', 'UserController@destroy');

    Route::put('user-media/{media}', 'UserController@updateMedia');
    Route::delete('user-media/{media}', 'UserController@destoryMedia');
});
