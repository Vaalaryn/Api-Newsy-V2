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

Route::prefix('{lang?}')->middleware('locale')->group(function () {
    Route::group(['prefix' => 'user'], function () {

        Route::post('add', 'UserController@add');
        Route::post('connect', 'UserController@connect');

        Route::group(['middleware' => 'auth.api'], function () {
            Route::post('delete', 'UserController@delete');
            Route::post('update', 'UserController@update');
            Route::post('getData','UserController@getData');
            Route::post('updateData','UserController@updateData');
        });
    });
});


Route::post('newsy', 'NewsyController@request');

