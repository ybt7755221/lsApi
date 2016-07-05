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
    Route::get('/error', 'HomeController@error');
    Route::get('/', 'HomeController@index');
    Route::get('/home', 'HomeController@index');
    Route::post('/fields', 'FieldsController@create');
    Route::post('/link', 'LinkController@create');
    Route::post('/home/fields', 'FieldsController@create');
    Route::post('/home/link', 'LinkController@create');
    Route::delete('/fields', 'FieldsController@removed');
    Route::delete('/link', 'LinkController@removed');
    Route::delete('/home/fields', 'FieldsController@removed');
    Route::delete('/home/link', 'LinkController@removed');
    Route::put('/home/fields', 'FieldsController@edit');
    Route::put('/home/link', 'LinkController@edit');
    Route::put('/fields', 'FieldsController@edit');
    Route::put('/link', 'LinkController@edit');
});
/**
 * all of the route about users configration.
 */
Route::group(['middleware' => 'auth', 'prefix' => 'user'], function () {
    Route::get('/', 'UserController@index');
    Route::post('/', 'UserController@create');
    Route::delete('/', 'UserController@remove');
    Route::delete('/multiOperation', 'UserController@multiOperation');
    Route::put('/disabled', 'UserController@disable');
    Route::put('/', 'UserController@edit');
});
/**
 * all of the route about content configration.
 */
Route::group(['middleware' => 'auth', 'prefix' => 'content'], function () {
    Route::get('/', 'ContentController@index');
    Route::post('/', 'ContentController@create');
    Route::put('/', 'ContentController@edit');
    Route::delete('/', 'ContentController@remove');
    Route::post('/getOldData', 'ContentController@getOldData');
    Route::post('/multiOperation', 'ContentController@multiOperation');
});
/**
 * all of the route about menus configration.
 */
Route::group(['middleware' => 'auth', 'prefix' => 'menu'], function () {
    Route::get('/', 'CategoryController@index');
    Route::post('/', 'CategoryController@create');
    Route::delete('/', 'CategoryController@removed');
    Route::put('/disabled', 'CategoryController@disabled');
    Route::put('/enabled', 'CategoryController@disabled');
    Route::put('/', 'CategoryController@edit');
    Route::delete('/multiOperation', 'CategoryController@multiOperation');
    Route::post('/subMenu','CategoryController@subMenu');
});