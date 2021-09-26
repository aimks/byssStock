<?php

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

Route::group(['prefix' => 'stocks'], function () {
    Route::post('operate', 'StockController@operate');
    Route::get('info', 'StockController@getStockInfo');
    Route::get('holdings', 'StockController@getHoldings');
    Route::get('assets/chart', 'StockController@getAssetsChart');
});
