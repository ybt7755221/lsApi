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
    Route::get('/home', 'HomeController@index');
    Route::post('/removed', array( 'uses'=>isset($_POST['html_id']) && stripos($_POST['html_id'], 'link') > 0 ? 'LinkController@removed' : 'FieldsController@removed' ));
    Route::post('/create', array( 'uses'=>isset($_POST['table_name']) && $_POST['table_name'] == 'link' ? 'LinkController@create' : 'FieldsController@create' ));
    Route::post('/edit', array( 'uses'=>isset($_POST['table_name']) && $_POST['table_name'] == 'link' ? 'LinkController@edit' : 'FieldsController@edit' ));
    Route::post('/home/removed', array( 'uses'=>isset($_POST['html_id']) && stripos($_POST['html_id'], 'link') > 0 ? 'LinkController@removed' : 'FieldsController@removed' ));
    Route::post('/home/create', array( 'uses'=>isset($_POST['table_name']) && $_POST['table_name'] == 'link' ? 'LinkController@create' : 'FieldsController@create' ));
    Route::post('/home/edit', array( 'uses'=>isset($_POST['table_name']) && $_POST['table_name'] == 'link' ? 'LinkController@edit' : 'FieldsController@edit' ));
});
/**
 * all of the route about users configration.
 */
Route::group(['middleware' => 'auth', 'prefix' => 'user'], function () {
    Route::get('/', 'UserController@index');
    Route::post('/create', 'UserController@create');
    Route::post('/removed', 'UserController@remove');
    Route::post('/disabled', 'UserController@disable');
    Route::post('/edit', 'UserController@edit');
    Route::post('/multiOperation', 'UserController@multiOperation');
});
/**
 * all of the route about content configration.
 */
Route::group(['middleware' => 'auth', 'prefix' => 'content'], function () {
    Route::get('/', 'ContentController@index');
    Route::post('/create', 'ContentController@create');
    Route::post('/removed', 'ContentController@remove');
    Route::post('/edit', 'ContentController@edit');
    Route::post('/getOldData', 'ContentController@getOldData');
    Route::post('/removed', 'ContentController@remove');
    Route::post('/multiOperation', 'ContentController@multiOperation');
});
/**
 * all of the route about menus configration.
 */
Route::group(['middleware' => 'auth', 'prefix' => 'menu'], function () {
    Route::get('/', 'CategoryController@index');
    Route::post('/create', 'CategoryController@create');
    Route::post('/removed', 'CategoryController@removed');
    Route::post('/disabled', 'CategoryController@disabled');
    Route::post('/enabled', 'CategoryController@disabled');
    Route::post('/edit', 'CategoryController@edit');
    Route::post('/multiOperation', 'CategoryController@multiOperation');
    Route::post('/subMenu','CategoryController@subMenu');
});