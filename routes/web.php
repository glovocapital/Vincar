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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');


/***** Una vez creado los middleware se dividen las rutas por rol *****/
//Route::group(['middleware' => ['CheckRol:SuperAdministrador,Administrador']], function () {


Route::get('usuarios', 'UserController@index')->name('usuarios.index');
Route::get('usuarios/create','UserController@create')->name('usuarios.create');

Route::post('usuarios','UserController@store')->name('usuarios.store');
Route::get('usuarios/{id}/edit','UserController@edit')->name('usuarios.edit');
Route::patch('usuarios/{id}/update','UserController@update')->name('usuarios.update');
Route::get('usuarios/{id}/delete','UserController@destroy')->name('usuarios.destroy');
Route::post('desactivarUsuario', 'UserController@desactivarUsuario');

//});
