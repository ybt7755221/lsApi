<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
/**
 * The route authentication function
 */
Route::auth();
/**
 * all of the route about system configration.
 */
Route::group(['middleware' => 'auth'], function () {

    Route::get('/', 'HomeController@index');
    Route::get('home', 'HomeController@index');
});
/**
 * all of the route about users configration.
 */
Route::group(['middleware' => 'auth', 'prefix' => 'user'], function () {
    Route::get('/', 'UserController@index');
});
/**
 * all of the route about content configration.
 */
Route::group(['middleware' => 'auth', 'prefix' => 'content'], function () {
    Route::get('/', 'HomeController@index');
    Route::get('home', 'HomeController@index');
});
/**
 * all of the route about menus configration.
 */
Route::group(['middleware' => 'auth', 'prefix' => 'menu'], function () {
    Route::get('/', 'HomeController@index');
    Route::get('home', 'HomeController@index');
});