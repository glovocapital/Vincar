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
Route::get('vin/cargamasiva','VinController@cargamasiva')->name('vin.cargamasiva');
Route::post('vin/loadexcel','VinController@loadexcel')->name('vin.loadexcel');



//Rutas mantenedor inspeccion
Route::get('inspeccion', 'InspeccionController@index')->name('inspeccion.index');
Route::get('inspeccion/create','InspeccionController@create')->name('inspeccion.create');
Route::get('inspeccion/create_dano/{id_inspeccion}','InspeccionController@createDano')->name('inspeccion.create_dano');
Route::get('inspeccion/{id_inspeccion}/edit','InspeccionController@edit')->name('inspeccion.edit');
Route::get('inspeccion/{id_dano_pieza}/edit_dano','InspeccionController@editDano')->name('inspeccion.edit_dano');
Route::post('inspeccion','InspeccionController@store')->name('inspeccion.store');
Route::post('inspeccion/store_dano','InspeccionController@storeDano')->name('inspeccion.store_dano');
Route::patch('inspeccion/{id}/update','InspeccionController@update')->name('inspeccion.update');
Route::get('inspeccion/{id}/delete','InspeccionController@destroy')->name('inspeccion.destroy');
Route::get('inspeccion/{id}/delete_dano','InspeccionController@destroyDano')->name('inspeccion.destroy_dano');
Route::get('inspeccion/obtener_subcategorias_pieza/{id_categoria}', ['as' => 'inspeccion.subcategorias', 'uses' => 'InspeccionController@subcategorias']);
Route::get('inspeccion/obtener_piezas/{id_subcategoria}', ['as' => 'inspeccion.piezas', 'uses' => 'InspeccionController@piezas']);

//Rutas mantenedor patios
Route::get('patio', 'PatioController@index')->name('patio.index');
Route::get('patio/create','PatioController@create')->name('patio.create');
Route::get('patio/{id}/edit','PatioController@edit')->name('patio.edit');
Route::post('patio','PatioController@store')->name('patio.store');
Route::patch('patio/{id}/update','PatioController@update')->name('patio.update');
Route::get('patio/{id}/delete','PatioController@destroy')->name('patio.destroy');
Route::get('patio/cargar_patios','PatioController@cargarPatios')->name('patio.cargar_patios');
Route::post('patio/store_patios','PatioController@storePatios')->name('patio.store_patios');

//Manejo de Thumbnail de imágenes
Route::resource('thumbnail', 'ThumbnailController');

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
Route::get('camiones/{id}/download','CamionesController@download')->name('camiones.download');

//ruta mantenedor de remolques
Route::get('remolque', 'RemolqueController@index')->name('remolque.index');
Route::get('remolque/create','RemolqueController@create')->name('remolque.create');
Route::get('remolque/{id}/edit','RemolqueController@edit')->name('remolque.edit');
Route::post('remolque','RemolqueController@store')->name('remolque.store');
Route::patch('remolque/{id}/update','RemolqueController@update')->name('remolque.update');
Route::get('remolque/{id}/delete','RemolqueController@destroy')->name('remolque.destroy');
Route::get('remolque/{id}/download','RemolqueController@download')->name('remolque.download');

//ruta mantenedor de productos
Route::get('productos', 'ProductoController@index')->name('productos.index');
Route::get('productos/create','ProductoController@create')->name('productos.create');
Route::get('productos/{id}/edit','ProductoController@edit')->name('productos.edit');
Route::post('productos','ProductoController@store')->name('productos.store');
Route::patch('productos/{id}/update','ProductoController@update')->name('productos.update');
Route::get('productos/{id}/delete','ProductoController@destroy')->name('productos.destroy');

//ruta mantenedor de servicios
Route::get('servicios', 'ServicioController@index')->name('servicios.index');
Route::get('servicios/create','ServicioController@create')->name('servicios.create');
Route::get('servicios/{id}/edit','ServicioController@edit')->name('servicios.edit');
Route::post('servicios','ServicioController@store')->name('servicios.store');
Route::patch('servicios/{id}/update','ServicioController@update')->name('servicios.update');
Route::get('servicios/{id}/delete','ServicioController@destroy')->name('servicios.destroy');

// ruta mantenedor conductores
Route::get('conductores', 'ConductorController@index')->name('conductores.index');
Route::get('conductores/create','ConductorController@create')->name('conductores.create');
Route::get('conductores/{id}/edit','ConductorController@edit')->name('conductores.edit');
Route::post('conductores','ConductorController@store')->name('conductores.store');
Route::patch('conductores/{id}/update','ConductorController@update')->name('conductores.update');
Route::get('conductores/{id}/delete','ConductorController@destroy')->name('conductores.destroy');
Route::get('conductores/{id}/download','ConductorController@download')->name('conductores.download');

// ruta mantenedor marcas
Route::get('marcas', 'MarcaController@index')->name('marcas.index');
Route::get('marcas/create','MarcaController@create')->name('marcas.create');
Route::get('marcas/{id}/edit','MarcaController@edit')->name('marcas.edit');
Route::post('marcas','MarcaController@store')->name('marcas.store');
Route::patch('marcas/{id}/update','MarcaController@update')->name('marcas.update');
Route::get('marcas/{id}/delete','MarcaController@destroy')->name('marcas.destroy');

// ruta mantenedor modelos
Route::get('modelos', 'ModeloController@index')->name('modelos.index');
Route::get('modelos/create','ModeloController@create')->name('modelos.create');
Route::get('modelos/{id}/edit','ModeloController@edit')->name('modelos.edit');
Route::post('modelos','ModeloController@store')->name('modelos.store');
Route::patch('modelos/{id}/update','ModeloController@update')->name('modelos.update');
Route::get('modelos/{id}/delete','ModeloController@destroy')->name('modelos.destroy');


});
