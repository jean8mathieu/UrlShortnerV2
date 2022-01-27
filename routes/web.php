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

Route::get('/', 'MainController@getIndex')->name("home");


Route::resource('api', 'APIController')
    ->except(['create', 'destroy', 'edit', 'update', 'destroy']);

Route::get('/{shortUrl}', 'APIController@show')->name('redirect');

//Route::post('/api/search/', 'APIController@showUrlInfo')->name('search');


Route::group([
    'prefix' => 'auth'
], function () {
    Route::get('login', 'Auth\LoginController@login')->name('login');
    Route::post('login', 'Auth\LoginController@postLogin')->name('postLogin');
    Route::get('logout', 'Auth\LoginController@logout')->name('logout');
});


Route::group([
    'prefix' => 'admin',
    'middleware' => ['auth']
], function () {
    Route::get('dashboard', 'AdminController@dashboard')->name('dashboard');

    Route::resource('bans', 'BansController')->only(['index', 'create', 'show']);
    Route::resource('forbidden', 'ForbiddenController')->only(['index', 'create', 'show']);

    Route::group([
        'prefix' => 'api'
    ], function(){
        Route::resource('bans', 'BansController')->only(['store', 'update', 'destroy']);
        Route::resource('forbidden', 'ForbiddenController')->only(['store', 'update', 'destroy']);

        Route::post('search', 'APIController@searchAdmin')->name('searchAdmin');

        Route::resource('api', 'APIController')->only(['destroy']);
    });


});
