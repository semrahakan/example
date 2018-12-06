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


//Route::get('/translate', 'TranslateController@index');

Route::get('/',[
			'as' => 'home',
			'uses' => 'TranslateController@index'
	] );

Route::post('/post',[
                'as' => 'translate',
                'uses' => 'TranslateController@post'
            ]);