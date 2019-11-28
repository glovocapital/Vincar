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
Auth::routes();

Route::group(['middleware' => 'guest'], function () {
    Route::get('/', 'Auth\LoginController@showLoginForm')->name('/');
    Route::any('login', 'Auth\LoginController@login');
});

/***Usuarios logueados****/

Route::group(['middleware' => 'auth'], function () {

    Route::any('logout', 'Auth\LoginController@logout');
    Route::get('home', 'HomeController@index')->name('home');



});

/*
Route::get('/', function () {
    return view('welcome');
});
*/

Auth::routes(['register' => false]);



/***** Una vez creado los middleware se dividen las rutas por rol *****/
Route::group(['middleware' => ['CheckRol:SuperAdministrador']], function () {


Route::get('usuarios', 'UserController@index')->name('usuarios.index');
Route::get('usuarios/create','UserController@create')->name('usuarios.create');
Route::get('usuarios/{id}/edit','UserController@edit')->name('usuarios.edit');
Route::post('usuarios','UserController@store')->name('usuarios.store');
Route::patch('usuarios/{id}/update','UserController@update')->name('usuarios.update');
Route::get('usuarios/{id}/delete','UserController@destroy')->name('usuarios.destroy');

Route::get('empresa', 'EmpresaController@index')->name('empresa.index');
Route::get('empresa/create','EmpresaController@create')->name('empresa.create');
Route::get('empresa/{id}/edit','EmpresaController@edit')->name('empresa.edit');
Route::post('empresa','EmpresaController@store')->name('empresa.store');
Route::patch('empresa/{id}/update','EmpresaController@update')->name('empresa.update');
Route::get('empresa/{id}/delete','EmpresaController@destroy')->name('empresa.destroy');

Route::get('vin', 'VinController@index')->name('vin.index');
Route::get('vin/create','VinController@create')->name('vin.create');
Route::get('vin/{id}/edit','VinController@edit')->name('vin.edit');
Route::post('vin','VinController@store')->name('vin.store');
Route::patch('vin/{id}/update','VinController@update')->name('vin.update');
Route::get('vin/{id}/delete','VinController@destroy')->name('vin.destroy');



});
