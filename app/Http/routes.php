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

Route::get('/', function () {
    return redirect('/home');
});

Route::get('/home', 'HomeController@index');

Route::group(['prefix' => 'api', 'namespace' => 'API'], function () {
    Route::resource('games', 'GameController', ['only' => [
        'index', 'show'
    ]]);
});


Route::group(['prefix' => 'api', 'namespace' => 'API', 'middleware' => 'auth:api'], function () {


    Route::resource('games', 'GameController', ['only' => [
        'store', 'update', 'destroy'
    ]]);

    Route::resource('order', 'OrderController', ['only' => [
        'index', 'show', 'store'
    ]]);

    Route::resource('topups', 'TopupController', ['only' => [
        'index', 'store'
    ]]);
});

Route::auth();
Route::get('/topup', 'HomeController@topup');
Route::get('/game/{id}', 'HomeController@game');
Route::get('/topuphistory', 'HomeController@topuphistory');
Route::get('/orderhistory', 'HomeController@orderhistory');
