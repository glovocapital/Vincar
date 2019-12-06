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

//Rutas mantenedor usuarios
Route::get('usuarios', 'UserController@index')->name('usuarios.index');
Route::get('usuarios/create','UserController@create')->name('usuarios.create');
Route::get('usuarios/{id}/edit','UserController@edit')->name('usuarios.edit');
Route::post('usuarios','UserController@store')->name('usuarios.store');
Route::patch('usuarios/{id}/update','UserController@update')->name('usuarios.update');
Route::get('usuarios/{id}/delete','UserController@destroy')->name('usuarios.destroy');

//Rutas mantenedor empresas
Route::get('empresa', 'EmpresaController@index')->name('empresa.index');
Route::get('empresa/create','EmpresaController@create')->name('empresa.create');
Route::get('empresa/{id}/edit','EmpresaController@edit')->name('empresa.edit');
Route::post('empresa','EmpresaController@store')->name('empresa.store');
Route::patch('empresa/{id}/update','EmpresaController@update')->name('empresa.update');
Route::get('empresa/{id}/delete','EmpresaController@destroy')->name('empresa.destroy');

//Rutas mantenedor vin
Route::get('vin', 'VinController@index')->name('vin.index');
Route::get('vin/create','VinController@create')->name('vin.create');
Route::get('vin/{id}/edit','VinController@edit')->name('vin.edit');
Route::post('vin','VinController@store')->name('vin.store');
Route::patch('vin/{id}/update','VinController@update')->name('vin.update');
Route::get('vin/{id}/delete','VinController@destroy')->name('vin.destroy');
Route::get('vin/obtener_usuarios_empresa/{id_empresa}', ['as' => 'vin.clientes', 'uses' => 'VinController@empresa']);
Route::get('vin/obtener_sub_estados/{id_estado_inventario}', ['as' => 'vin.sub_estados', 'uses' => 'VinController@estadoInventario']);

//Rutas mantenedor inspeccion
Route::get('inspeccion', 'InspeccionController@index')->name('inspeccion.index');
Route::get('inspeccion/create','InspeccionController@create')->name('inspeccion.create');
Route::get('inspeccion/{id}/edit','InspeccionController@edit')->name('inspeccion.edit');
Route::post('inspeccion','InspeccionController@store')->name('inspeccion.store');
Route::patch('inspeccion/{id}/update','InspeccionController@update')->name('inspeccion.update');
Route::get('inspeccion/{id}/delete','InspeccionController@destroy')->name('inspeccion.destroy');
Route::get('inspeccion/obtener_usuarios_empresa/{id_empresa}', ['as' => 'inspeccion.clientes', 'uses' => 'InspeccionController@empresa']);
Route::get('inspeccion/obtener_sub_estados/{id_estado_inventario}', ['as' => 'inspeccion.sub_estados', 'uses' => 'InspeccionController@estadoInventario']);

//Rutas mantenedor país
Route::get('pais', 'PaisController@index')->name('pais.index');
Route::get('pais/create','PaisController@create')->name('pais.create');
Route::get('pais/{id}/edit','PaisController@edit')->name('pais.edit');
Route::post('pais','PaisController@store')->name('pais.store');
Route::patch('pais/{id}/update','PaisController@update')->name('pais.update');
Route::get('pais/{id}/delete','PaisController@destroy')->name('pais.destroy');

//ruta mantenedor tipo de proveedor
Route::get('proveedor', 'TipoProveedorController@index')->name('proveedor.index');
Route::get('proveedor/create','TipoProveedorController@create')->name('proveedor.create');
Route::get('proveedor/{id}/edit','TipoProveedorController@edit')->name('proveedor.edit');
Route::post('proveedor','TipoProveedorController@store')->name('proveedor.store');
Route::patch('proveedor/{id}/update','TipoProveedorController@update')->name('proveedor.update');
Route::get('proveedor/{id}/delete','TipoProveedorController@destroy')->name('proveedor.destroy');

//ruta mantenedor destinos
Route::get('destinos', 'DestinoController@index')->name('destinos.index');
Route::get('destinos/create','DestinoController@create')->name('destinos.create');
Route::get('destinos/{id}/edit','DestinoController@edit')->name('destinos.edit');
Route::post('destinos','DestinoController@store')->name('destinos.store');
Route::patch('destinos/{id}/update','DestinoController@update')->name('destinos.update');
Route::get('destinos/{id}/delete','DestinoController@destroy')->name('destinos.destroy');

//ruta mantenedor camiones
Route::get('camiones', 'CamionesController@index')->name('camiones.index');
Route::get('camiones/create','CamionesController@create')->name('camiones.create');
Route::get('camiones/{id}/edit','CamionesController@edit')->name('camiones.edit');
Route::post('camiones','CamionesController@store')->name('camiones.store');
Route::patch('camiones/{id}/update','CamionesController@update')->name('camiones.update');
Route::get('camiones/{id}/delete','CamionesController@destroy')->name('camiones.destroy');

//ruta mantenedor de remolques
Route::get('remolque', 'RemolqueController@index')->name('remolque.index');
Route::get('remolque/create','RemolqueController@create')->name('remolque.create');
Route::get('remolque/{id}/edit','RemolqueController@edit')->name('remolque.edit');
Route::post('remolque','RemolqueController@store')->name('remolque.store');
Route::patch('remolque/{id}/update','RemolqueController@update')->name('remolque.update');
Route::get('remolque/{id}/delete','RemolqueController@destroy')->name('remolque.destroy');



});
