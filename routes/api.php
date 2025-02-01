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
Route::get('global-data', 'GlobalDataController@index');

Route::group(['middleware' => 'auth:api'], function () {
        // Admin Area (Authentication Required)
        Route::group([
            'namespace' => 'Admin',
            'prefix' => 'admin',
        ], function () {
            require_once base_path('routes/custom/admin_routes.php');
        });

        // Website Area Routes (Authentication Required)
        Route::group([
            'namespace' => 'Website',
        ], function () {
            require_once base_path('routes/custom/website_routes.php');
        });
    }
);

// Website Area Normal Routes (No Authentication)
Route::group([
    // 'namespace' => 'Website',
], function () {
    require_once base_path('routes/custom/no_auth_website_routes.php');
});
