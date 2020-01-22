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

Route::get('/', 'ApiController@index')->name('/');

Route::post('login','ApiController@login')->name('api.login');

Route::get('badge','ApiController@Badge')->name('api.badge');

Route::get('list','ApiController@Lists')->name('api.list');

Route::get('listvin/{vins_id}','ApiController@ListVIN')->name('api.listvin');

Route::get('listbloq/{patio_id}','ApiController@ListBloques')->name('api.listbloq');

Route::get('listpos/{bloque_id}','ApiController@ListPosicion')->name('api.listpos');


Route::post('guardarposicion','ApiController@CambiarPosicion')->name('api.Ccmbiar_posicion');
