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

Route::get('tareafinalizada/{tarea_id}','ApiController@TareaFinalizada')->name('api.Tarea_Finalizada');


Route::post('listvin','ApiController@ListVIN')->name('api.listvin');

Route::get('listbloq/{patio_id}','ApiController@ListBloques')->name('api.listbloq');

Route::get('listpos/{bloque_id}','ApiController@ListPosicion')->name('api.listpos');


Route::post('guardarposicion','ApiController@CambiarPosicion')->name('api.Cambiar_posicion');

Route::post('dararribo','ApiController@DarArribo')->name('api.Cambiar_arribo');


Route::post('cargainicialinspeccionar','ApiController@CargaInicialInspeccionar')->name('api.Carga_ini_inspeccionar');

Route::post('sindano','ApiController@InpeccionarSinDano')->name('api.sin_dano');

Route::post('condano','ApiController@InpeccionarConDano')->name('api.con_dano');

Route::post('entrega','ApiController@Entregar')->name('api.entrega');

Route::get('buscartransportista/{user_rut}','ApiController@BuscarTransportista')->name('api.buscartransportista');




