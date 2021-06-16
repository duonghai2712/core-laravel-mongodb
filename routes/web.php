<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['web'], 'prefix' => '/', 'before' => ''], function () {

    Route::any('/help/{action_name?}', ['as' => 'YSystem', 'uses' => 'YHelper\HelperController@index']);

    Route::any('/auth/{action_name?}', ['as' => 'YSystem', 'uses' => 'YSystem\SystemController@index']);

    Route::group(['middleware' => ['project']], function () {

        Route::any('/{action_name?}', ['as' => 'YDashboard', 'uses' => 'YDashboard\DashboardController@index']);

    });

});
