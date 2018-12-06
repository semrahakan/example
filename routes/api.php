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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//site.dev/api/post
Route::post('/post', 'TranslateApiController@post');

//site.dev/api/index
Route::get('/index','TranslateApiController@index');

//site.dev/api/waiting
Route::post('/waiting','TranslateApiController@getWaitingList');

Route::get('/translated', 'TranslateApiController@translatedWord');

