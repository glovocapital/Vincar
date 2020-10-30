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

use Illuminate\Support\Facades\Storage;

Auth::routes();

Route::group(['middleware' => 'guest'], function () {
    Route::get('/', 'Auth\LoginController@showLoginForm')->name('/');
    Route::any('login', 'Auth\LoginController@login');
});

/***Usuarios logueados****/

Route::group(['middleware' => 'auth'], function () {

    Route::any('logout', 'Auth\LoginController@logout');
    Route::get('home', 'HomeController@index')->name('home');
    Route::get('homeDashboard', 'HomeController@dashboard')->name('home.dashboard');



});

//Jc
Route::get('lang/{lang}', function ($lang) {
    session()->put('lang', $lang);

    return redirect()->back();
})->where([
    'lang' => 'en|es'
]);
/*
Route::get('/', function () {
    return view('welcome');
});
*/

Auth::routes(['register' => false]);

/***** Una vez creado los middleware se dividen las rutas por rol *****/


/********SUPER ADMINISTRADOR Y ADMINISTRADOR ********/

Route::group(['middleware' => ['CheckRol:SuperAdministrador,Administrador']], function () {

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
    Route::get('/fotos/{file}', function ($file) {
        return Storage::response("fotos/$file");
    });

    //Rutas mantenedor patios
    Route::get('patio', 'PatioController@index')->name('patio.index');
    Route::get('patio/create','PatioController@create')->name('patio.create');
    Route::get('patio/{id}/edit','PatioController@edit')->name('patio.edit');
    Route::post('patio','PatioController@store')->name('patio.store');
    Route::patch('patio/{id}/update','PatioController@update')->name('patio.update');
    Route::get('patio/{id}/delete','PatioController@destroy')->name('patio.destroy');
    Route::get('patio/cargar_patios','PatioController@cargarPatios')->name('patio.cargar_patios');
    Route::post('patio/store_patios','PatioController@storePatios')->name('patio.store_patios');
    Route::get('patio/obtener_comunas/{id_region}', ['as' => 'patio.comunas', 'uses' => 'PatioController@comunas']);
    Route::get('patio/download' , 'PatioController@downloadFile')->name('patio.download');

    //Rutas mantenedor bloques
    Route::get('bloque/{id_patio}/index', 'BloqueController@index')->name('bloque.index');
    Route::get('bloque/{id_patio}/create','BloqueController@create')->name('bloque.create');
    Route::get('bloque/{id}/edit','BloqueController@edit')->name('bloque.edit');
    Route::post('bloque','BloqueController@store')->name('bloque.store');
    Route::patch('bloque/{id}/update','BloqueController@update')->name('bloque.update');
    Route::get('bloque/{id}/delete','BloqueController@destroy')->name('bloque.destroy');
    Route::get('bloque/cargar_patios','BloqueController@cargarPatios')->name('bloque.cargar_patios');
    Route::post('bloque/store_patios','BloqueController@storePatios')->name('bloque.store_patios');

    //Rutas mantenedor Ubicación en Bloque o Patio
    Route::get('ubic_patio/{id_bloque}/index', 'UbicPatioController@index')->name('ubic_patio.index');
    Route::get('ubic_patio/{id_bloque}/create','UbicPatioController@create')->name('ubic_patio.create');
    Route::get('ubic_patio/{id}/edit','UbicPatioController@edit')->name('ubic_patio.edit');
    Route::post('ubic_patio/store','UbicPatioController@store')->name('ubic_patio.store');
    Route::patch('ubic_patio/{id}/update','UbicPatioController@update')->name('ubic_patio.update');
    Route::get('ubic_patio/{id}/delete','UbicPatioController@destroy')->name('ubic_patio.destroy');
    Route::get('ubic_patio/cargar_patios','UbicPatioController@cargarPatios')->name('ubic_patio.cargar_patios');
    Route::post('ubic_patio/store_patios','UbicPatioController@storePatios')->name('ubic_patio.store_patios');

    //Manejo de Thumbnail de imágenes
    // Route::resource('thumbnail', 'ThumbnailController');


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
    Route::get('camiones/{id}/trash','CamionesController@trash')->name('camiones.trash');
    Route::get('camiones/{id}/restore','CamionesController@restore')->name('camiones.restore');
    Route::get('camiones/{id}/delete','CamionesController@destroy')->name('camiones.destroy');
    Route::get('camiones/{id}/download','CamionesController@download')->name('camiones.download');

    //ruta mantenedor de remolques
    Route::get('remolque', 'RemolqueController@index')->name('remolque.index');
    Route::get('remolque/create','RemolqueController@create')->name('remolque.create');
    Route::get('remolque/{id}/edit','RemolqueController@edit')->name('remolque.edit');
    Route::post('remolque','RemolqueController@store')->name('remolque.store');
    Route::patch('remolque/{id}/update','RemolqueController@update')->name('remolque.update');
    Route::get('remolque/{id}/trash','RemolqueController@trash')->name('remolque.trash');
    Route::get('remolque/{id}/restore','RemolqueController@restore')->name('remolque.restore');
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
    Route::get('conductores/create_conductor','ConductorController@createUserConductor')->name('conductores.create_conductor');
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

    //Rutas mantenedor país
    Route::get('pais', 'PaisController@index')->name('pais.index');
    Route::get('pais/create','PaisController@create')->name('pais.create');
    Route::get('pais/{id}/edit','PaisController@edit')->name('pais.edit');
    Route::post('pais','PaisController@store')->name('pais.store');
    Route::patch('pais/{id}/update','PaisController@update')->name('pais.update');
    Route::get('pais/{id}/delete','PaisController@destroy')->name('pais.destroy');

    Route::get('kpi', 'KpiController@index')->name('kpi.index');

});


                /********SUPER ADMINISTRADOR,  OPERADOR LOGISTICO Y CUSTOMER ********/

Route::group(['middleware' => ['CheckRol:SuperAdministrador,Operador Logistico,Customer']], function () {

    //Rutas mantenedor vin
    Route::get('vin', 'VinController@index')->name('vin.index');
    Route::post('vin/index2', 'VinController@index2')->name('vin.index2');
    Route::post ('vin/index3', 'VinController@index3')->name('vin.index3');
    Route::get('vin/create','VinController@create')->name('vin.create');
    Route::get('vin/{id}/edit','VinController@edit')->name('vin.edit');
    Route::post('vin','VinController@store')->name('vin.store');
    Route::patch('vin/{id}/update','VinController@update')->name('vin.update');
    Route::get('vin/{id}/delete','VinController@destroy')->name('vin.destroy');
    Route::get('vin/obtener_usuarios_empresa/{id_empresa}', ['as' => 'vin.clientes', 'uses' => 'VinController@empresa']);
    Route::get('vin/obtener_sub_estados/{id_estado_inventario}', ['as' => 'vin.sub_estados', 'uses' => 'VinController@estadoInventario']);
    Route::get('vin/cargamasiva','VinController@cargamasiva')->name('vin.cargamasiva');
    Route::post('vin/loadexcel','VinController@loadexcel')->name('vin.loadexcel');
    Route::get('vin/download' , 'VinController@downloadFile')->name('vin.download');
    Route::post('vin/search','VinController@search')->name('vin.search');
    Route::get('vin/{id}/editarestado','VinController@editarestado')->name('vin.editarestado');
    Route::patch('vin/{id}/cambiaestado','VinController@cambiaestado')->name('vin.cambiaestado');
    Route::get('vin/{id}/guia','VinController@guia')->name('vin.guia');
    Route::patch('vin/{id}/addguia','VinController@addguia')->name('vin.addguia');
    Route::get('vin/{id}/downloadGuia','VinController@downloadGuia')->name('vin.downloadGuia');
    Route::post('vin/modal_tarea_lotes','VinController@storeModalTareaLotes')->name('vin.storeModalTareaLotes');

    Route::post('vin/export', 'VinController@exportResultadoBusquedaVins')->name('vin.exportResultadoBusquedaVins');
    Route::post('vin/export_masivo', 'VinController@exportMasivoResultadoBusquedaVins')->name('vin.exportMasivoResultadoBusquedaVins');

    Route::get('traspasovin','VinController@traspasovin')->name('vin.traspasovin');

    Route::post('traspasovin/cambio','VinController@cambio')->name('vin.cambio');


    //Exportar VIN que fueron entregados
    Route::post('vin/entregaExport', 'VinController@entregaExportResultadoBusquedaVins')->name('vin.entregaExportResultadoBusquedaVins');

    Route::post('vin/modal_cambia_estado','VinController@storeModalCambiaEstado')->name('vin.storeModalCambiaEstado');
    Route::post('vin/predespacho','VinController@predespacho')->name('vin.predespacho');
    Route::post('vin/bloqueaEntrega', 'VinController@bloqueaEntrega')->name('vin.bloquea_entrega');

    Route::post ('vin/index_json', 'VinController@index_json')->name('vin.index_json');
    Route::get('vin/{id}/desagendado','VinController@desagendado')->name('vin.desagendado');

    // Entregas
    Route::get('entrega', 'EntregaController@index')->name('entrega.index');

    // Predespachos
    Route::get('entrega/{id}/info_predespacho', 'EntregaController@infoPredespacho')->name('entrega.infoPredespacho');

    //Rutas mantenedor Histórico de Vins
    Route::get('historico_vin', 'HistoricoVinController@index')->name('historico_vin.index');
    Route::get('historico_vin/create','HistoricoVinController@create')->name('historico_vin.create');
    Route::get('historico_vin/{id}/edit','HistoricoVinController@edit')->name('historico_vin.edit');
    Route::post('historico_vin','HistoricoVinController@store')->name('historico_vin.store');
    Route::patch('historico_vin/{id}/update','HistoricoVinController@update')->name('historico_vin.update');
    Route::get('historico_vin/{id}/delete','HistoricoVinController@destroy')->name('historico_vin.destroy');
    Route::get('historico_vin/historicoVin/{id_vin}','HistoricoVinController@historicoVin')->name('historico_vin.historicoVin');
    Route::post('historico_vin/exportHistoricoLote', 'HistoricoVinController@exportHistoricoVinLote')->name('historico_vin.exportHistoricoVinLote');

     //Jc
     Route::get('patio/vins_patio','PatioController@indexVinsPatio')->name('patio.vins_patio');
     Route::get('patioDashboard', 'PatioController@dashboard')->name('patio.dashboard');
     Route::get('patioBloques', 'PatioController@bloques')->name('patio.bloques');
     Route::get('TodospatioBloques', 'PatioController@Todosbloques')->name('patio.todos_bloques');
     Route::get('VaciarBloques', 'PatioController@Vaciarbloques')->name('patio.vaciar_bloques');

     Route::post('planificacion/obtener_codigos_vins', ['as' => 'planificacion.codigos_vins', 'uses' => 'CampaniaController@vinCodigos']);
     Route::get('campania', 'CampaniaController@index')->name('campania.index');

     //ruta mantenedor tipo de campaña
    Route::get('tipo_campania', 'TipoCampaniaController@index')->name('tipo_campania.index');
    Route::get('tipo_campania/create','TipoCampaniaController@create')->name('tipo_campania.create');
    Route::get('tipo_campania/{id}/edit','TipoCampaniaController@edit')->name('tipo_campania.edit');
    Route::post('tipo_campania','TipoCampaniaController@store')->name('tipo_campania.store');
    Route::patch('tipo_campania/{id}/update','TipoCampaniaController@update')->name('tipo_campania.update');
    Route::get('tipo_campania/{id}/delete','TipoCampaniaController@destroy')->name('tipo_campania.destroy');



    Route::get('solicitud_campania', 'CampaniaController@index2')->name('solicitud_campania.index');

      //Ruta para la funcionalidad por POST
    Route::post('solicitud_campania/index2', 'CampaniaController@index4')->name('solicitud_campania.index2');



    Route::get('campania/{id}/edit','CampaniaController@edit')->name('campania.edit');
    Route::post('campania/modal','CampaniaController@storeModal')->name('campania.storeModal');
    Route::post('campania/modal_tarea','CampaniaController@storeModalTarea')->name('campania.storeModalTarea');
    Route::post('campania/modal_tarea_lotes','CampaniaController@storeModalTareaLotes')->name('campania.storeModalTareaLotes');
    Route::post('campania/modal_campania_lotes','CampaniaController@storeModalCampaniaLotes')->name('campania.storeModalCampaniaLotes');
    Route::patch('campania/{id}/update','CampaniaController@update')->name('campania.update');



    Route::get('planificacion', 'CampaniaController@index3')->name('planificacion.index');

    Route::post('planificacion/export', 'CampaniaController@exportResultadoBusquedaVins')->name('campania.exportResultadoBusquedaVins');

    //Ruta para la funcionalidad por POST
    Route::post('planificacion/index2', 'CampaniaController@index5')->name('planificacion.index2');
    Route::post('planificacion/index5_json', 'CampaniaController@index5_json')->name('planificacion.index5_json');



    Route::get('campania/create','CampaniaController@create')->name('campania.create');

    Route::get('planificacion/{id}/edit','CampaniaController@editTarea')->name('planificacion.edit');
    Route::post('campania','CampaniaController@store')->name('campania.store');
    Route::post('campania/modal_tarea','CampaniaController@storeModalTarea')->name('campania.storeModalTarea');

    Route::patch('planificacion/{id}/update','CampaniaController@updateTarea')->name('planificacion.update');
    Route::get('campania/{id}/delete','CampaniaController@destroy')->name('campania.destroy');
    Route::get('planificacion/{id}/delete','CampaniaController@destroyTarea')->name('planificacion.destroy');


    // Módulo de Transporte: Tours

    Route::get('tour', 'TourController@index')->name('tour.index');
    Route::get('tour/tour', 'TourController@tour')->name('tour.tour');
    Route::post('tour','TourController@store')->name('tour.store');
    Route::get('tour/{id}/edit','TourController@editTour')->name('tour.edit');
    Route::patch('tour/{id}/update','TourController@updateTour')->name('tour.update');
    Route::get('tour/{id}/delete','TourController@trash')->name('tour.delete');
    
    
    // Rutas del tour
    Route::get('tour/addrutas', 'RutaController@addRutas')->name('tour.addrutas');
    Route::post('tour/crearutas', 'RutaController@creaRutas')->name('tour.crearutas');
    Route::get('tour/{id}/editrutas', 'RutaController@editRutas')->name('tour.editrutas');
    Route::patch('tour/{id}/update_rutas','RutaController@updateRutas')->name('tour.update_rutas');
    Route::get('tour/admin_rutas', 'RutaController@adminRutas')->name('tour.admin_rutas');
    // Route::post('tour/crearutas2', 'TourController@crearutas2')->name('tour.crearutas2');


    // Vehículos N/N
    Route::get('vehiculo_nn','VehiculoNNController@index')->name('vehiculo_nn');
    Route::get('vehiculo_nn/{vin_id}/data_vin_nn','VehiculoNNController@dataVinNN')->name('vehiculo_nn.dataVinNn');
    Route::post('vehiculo_nn/registrar_vin','VehiculoNNController@registrarVin')->name('vehiculo_nn.registrarVin');
});



