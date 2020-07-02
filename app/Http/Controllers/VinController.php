<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\CheckSession;
use App\User;
use App\Empresa;
use App\Imports\VinsCollectionImport;
use App\Imports\VinsImport;
use App\Patio;
use App\TipoCampania;
use App\Vin;
use App\Campania;
use App\Exports\BusquedaVinsExport;
use App\Exports\VinEntregadosExport;
Use App\Guia;
Use App\GuiaVins;
use App\Marca;
use App\UbicPatio;
use Auth;
use Illuminate\Support\Facades\Crypt;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Collection as Collection;
use Carbon\Carbon;





class VinController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware(PreventBackHistory::class);
        $this->middleware(CheckSession::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {


        /** Tareas creadas para mostrarse */
         $vin_agendados = Vin::where('vin_predespacho', true)
         ->join('users','vins.user_id','=','users.user_id')
         ->join('empresas','users.empresa_id','=','empresas.empresa_id')
         ->orderBy('vin_fecha_entrega')
         ->get();


        $vin_entregados_dia = Vin::where('vin_estado_inventario_id', 8)
            ->join('users','vins.user_id','=','users.user_id')
            ->join('empresas','users.empresa_id','=','empresas.empresa_id')
            ->where('vins.updated_at',  '>', Carbon::now()->subDays(1)->toDateTimeString())
            ->orderBy('vin_fecha_entrega')
            ->get();

        $vin_entregados = Vin::where('vin_estado_inventario_id', 8)
            ->join('users','vins.user_id','=','users.user_id')
            ->join('empresas','users.empresa_id','=','empresas.empresa_id')
            ->orderBy('vin_fecha_entrega')
            ->get();





        /** Búsqueda de vins para la cabecera de la vista de planificación */
        $vins = Vin::all();

        $tipo_campanias_array = TipoCampania::select('tipo_campania_id', 'tipo_campania_descripcion')
            ->where('deleted_at', null)
            ->pluck('tipo_campania_descripcion', 'tipo_campania_id');

        $patios = DB::table('patios')
            ->select('patio_id', 'patio_nombre')
            ->pluck('patio_nombre', 'patio_id');

        $users = User::select(DB::raw("CONCAT(user_nombre,' ', user_apellido) AS user_nombres"), 'user_id')
            ->orderBy('user_id')
            ->where('deleted_at', null)
            ->pluck('user_nombres', 'user_id')
            ->all();

        $empresas = Empresa::select('empresa_id', 'empresa_razon_social')
            ->orderBy('empresa_id')
            ->where('deleted_at', null)
            ->pluck('empresa_razon_social', 'empresa_id')
            ->all();

        $estadosInventario = DB::table('vin_estado_inventarios')
            ->select('vin_estado_inventario_id', 'vin_estado_inventario_desc')
            ->pluck('vin_estado_inventario_desc', 'vin_estado_inventario_id');

        $subEstadosInventario = DB::table('vin_sub_estado_inventarios')
            ->select('vin_sub_estado_inventario_id', 'vin_sub_estado_inventario_desc')
            ->pluck('vin_sub_estado_inventario_desc', 'vin_sub_estado_inventario_id');

        $marcas = DB::table('marcas')
            ->select('marca_id', 'marca_nombre')
            ->where('deleted_at', null)
            ->pluck('marca_nombre', 'marca_id');

        $tabla_vins = [];


        return view('vin.index', compact( 'tabla_vins','users','empresas', 'estadosInventario', 'subEstadosInventario', 'patios', 'marcas', 'vin_entregados', 'vin_entregados_dia','vin_agendados'));
    }



    public function index_json(Request $request)
    {
        if($request->ajax())
        {
            /** Búsqueda de vins para la cabecera de la vista de planificación */
            $vins = Vin::all();

            $tipo_campanias_array = TipoCampania::select('tipo_campania_id', 'tipo_campania_descripcion')
                ->where('deleted_at', null)
                ->pluck('tipo_campania_descripcion', 'tipo_campania_id');

            $patios = DB::table('patios')
                ->select('patio_id', 'patio_nombre')
                ->pluck('patio_nombre', 'patio_id');

            $users = User::select(DB::raw("CONCAT(user_nombre,' ', user_apellido) AS user_nombres"), 'user_id')
                ->orderBy('user_id')
                ->where('deleted_at', null)
                ->pluck('user_nombres', 'user_id')
                ->all();

            $empresas = Empresa::select('empresa_id', 'empresa_razon_social')
                ->orderBy('empresa_id')
                ->where('deleted_at', null)
                ->pluck('empresa_razon_social', 'empresa_id')
                ->all();

            $estadosInventario = DB::table('vin_estado_inventarios')
                ->select('vin_estado_inventario_id', 'vin_estado_inventario_desc')
                ->pluck('vin_estado_inventario_desc', 'vin_estado_inventario_id');

            $subEstadosInventario = DB::table('vin_sub_estado_inventarios')
                ->select('vin_sub_estado_inventario_id', 'vin_sub_estado_inventario_desc')
                ->pluck('vin_sub_estado_inventario_desc', 'vin_sub_estado_inventario_id');

            $marcas = DB::table('marcas')
                ->select('marca_id', 'marca_nombre')
                ->where('deleted_at', null)
                ->pluck('marca_nombre', 'marca_id');

            $tabla_vins = [];


            /** A partir de aqui las consultas del cuadro de busqueda */
            if($request->has('empresa_id') || $request->has('vin_numero') || $request->has('estadoinventario_id') || $request->has('patio_id') || $request->has('marca_id')){

                $estado = DB::table('vin_estado_inventarios')
                    ->where('vin_estado_inventario_id',$request->estadoinventario_id)
                    ->get();

                if(!empty($estado[0]->vin_estado_inventario_id)){
                    $estado_id = $estado[0]->vin_estado_inventario_id;
                }else{
                    $estado_id = 0;
                }

                $marca = Marca::find($request->marca_id);

                if($marca)
                {
                    $marca_nombre = $marca->marca_nombre;
                }else{
                    $marca_nombre = 'Sin marca';
                }

                $user = DB::table('users')
                    ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                    ->where('empresas.empresa_id',$request->empresa_id)
                    ->get();

                if(!empty($user[0]->empresa_id))
                {
                    $user_empresa_id = $user[0]->empresa_id;
                }else{
                    $user_empresa_id = 0;
                }

                $patio = DB::table('patios')
                    ->where('patio_id', $request->patio_id)
                    ->get();

                if(!empty($patio[0]->patio_nombre))
                {
                    $patio_id = $patio[0]->patio_id;
                }else{
                    $patio_id = 0;
                }

                if(!empty($request->vin_numero)){
                    foreach(explode(PHP_EOL,$request->vin_numero) as $row){
                        $arreglo_vins[] = trim($row);
                    }

                    $message = [];

                    foreach($arreglo_vins as $v){
                        $validate = DB::table('vins')
                            ->where('vin_codigo', $v)
                            ->orWhere('vin_patente', $v)
                            ->exists();

                        if($validate == true){
                            $query = DB::table('vins')
                                ->join('users','users.user_id','=','vins.user_id')
                                ->join('vin_estado_inventarios','vins.vin_estado_inventario_id','=','vin_estado_inventarios.vin_estado_inventario_id')
                                ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                                ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                                    'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_fecha_entrega', 'vin_estado_inventario_desc')
                                ->where('vin_codigo',$v)
                                ->orWhere('vin_patente', $v);

                            if($user_empresa_id > 0){
                                $query->where('empresas.empresa_id',$user_empresa_id);
                            }

                            if($marca_nombre != 'Sin marca'){
                                $query->where('vin_marca',$marca->marca_id);
                                //$query->WhereRaw('upper(vin_marca) like(?)',strtoupper($marca_nombre));
                            }

                            if($estado_id > 0){
                                $query->where('vins.vin_estado_inventario_id', $estado_id);

                                if($estado_id == 4 || $estado_id == 5 || $estado_id == 6) {
                                    if($patio_id > 0){
                                        $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                            ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                            ->join('patios','bloques.patio_id','=','patios.patio_id')
                                            ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                                                'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_estado_inventario_desc',
                                                'patio_nombre', 'bloque_nombre', 'ubic_patio_id', 'ubic_patio_fila',
                                                'ubic_patio_columna','vin_fecha_entrega')
                                            ->where('patios.patio_id', $patio_id);
                                    } else {
                                        $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                            ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                            ->join('patios','bloques.patio_id','=','patios.patio_id')
                                            ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                                                'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_estado_inventario_desc','patio_nombre', 'bloque_nombre',
                                                'ubic_patio_id',  'ubic_patio_fila', 'ubic_patio_columna','vin_fecha_entrega');
                                    }
                                }
                            } else {
                                $vin_estado_id = Vin::where('vin_codigo', $v)
                                    ->orWhere('vin_patente', $v)
                                    ->value('vin_estado_inventario_id');

                                $query->where('vins.vin_estado_inventario_id', $vin_estado_id);

                                if($vin_estado_id == 4 || $vin_estado_id == 5 || $vin_estado_id == 6) {
                                    if($patio_id > 0){
                                        $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                            ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                            ->join('patios','bloques.patio_id','=','patios.patio_id')
                                            ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                                                'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_fecha_entrega', 'vin_estado_inventario_desc',
                                                'patio_nombre', 'bloque_nombre', 'ubic_patio_id', 'ubic_patio_fila',
                                                'ubic_patio_columna')
                                            ->where('patios.patio_id', $patio_id);
                                    } else {
                                        $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                            ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                            ->join('patios','bloques.patio_id','=','patios.patio_id')
                                            ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                                                'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_fecha_entrega', 'vin_estado_inventario_desc',
                                                'patio_nombre', 'bloque_nombre', 'ubic_patio_id', 'ubic_patio_fila',
                                                'ubic_patio_columna');
                                    }
                                }
                            }

                            array_push($tabla_vins, $query->first());
                        } else {
                            if(count($arreglo_vins) >= 1){
                                $message[$v] = "Vin o patente: " . $v . " no se encuentra en la lista";
                            } else {
                                $query = DB::table('vins')
                                    ->join('users','users.user_id','=','vins.user_id')
                                    ->join('vin_estado_inventarios','vins.vin_estado_inventario_id','=','vin_estado_inventarios.vin_estado_inventario_id')
                                    ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                                    ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                                        'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_fecha_entrega', 'vin_estado_inventario_desc');

                                if($user_empresa_id > 0){
                                    $query->where('empresas.empresa_id',$user_empresa_id);
                                }

                                if($marca_nombre != 'Sin marca'){
                                    $query->where('vin_marca',$marca->marca_id);
                                    //$query->WhereRaw('upper(vin_marca) like(?)',strtoupper($marca_nombre));
                                }

                                if($estado_id > 0){
                                    $query->where('vins.vin_estado_inventario_id', $estado_id);

                                    if($estado_id == 4 || $estado_id == 5 || $estado_id == 6) {
                                        if($patio_id > 0){
                                            $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                                ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                                ->join('patios','bloques.patio_id','=','patios.patio_id')
                                                ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                                                    'empresas.empresa_razon_social', 'vin_fec_ingreso','vin_fecha_entrega', 'vin_estado_inventario_desc',
                                                    'patio_nombre', 'bloque_nombre', 'ubic_patio_id', 'ubic_patio_fila',
                                                    'ubic_patio_columna')
                                                ->where('patios.patio_id', $patio_id);
                                        } else {
                                            $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                                ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                                ->join('patios','bloques.patio_id','=','patios.patio_id')
                                                ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                                                    'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_fecha_entrega','vin_estado_inventario_desc',
                                                    'patio_nombre', 'bloque_nombre', 'ubic_patio_id', 'ubic_patio_fila',
                                                    'ubic_patio_columna');
                                        }
                                    }
                                }

                                $tabla_vins = $query->get();
                            }
                        }
                    }
                }else{
                    $query = DB::table('vins')
                        ->join('users','users.user_id','=','vins.user_id')
                        ->join('vin_estado_inventarios','vins.vin_estado_inventario_id','=','vin_estado_inventarios.vin_estado_inventario_id')
                        ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                        ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                            'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_fecha_entrega', 'vin_estado_inventario_desc');

                    if($user_empresa_id > 0){
                        $query->where('empresas.empresa_id',$user_empresa_id);
                    }


                    if($marca_nombre != 'Sin marca'){
                        $query->where('vin_marca',$marca->marca_id);
                        //$query->WhereRaw('upper(vin_marca) like(?)',strtoupper($marca_nombre));
                    }

                    if($estado_id > 0){
                        $query->where('vins.vin_estado_inventario_id', $estado_id);

                        if($estado_id == 4 || $estado_id == 5 || $estado_id == 6) {
                            if($patio_id > 0){
                                $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                    ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                    ->join('patios','bloques.patio_id','=','patios.patio_id')
                                    ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                                        'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_fecha_entrega','vin_estado_inventario_desc',
                                        'patio_nombre', 'bloque_nombre', 'ubic_patio_id', 'ubic_patio_fila',
                                        'ubic_patio_columna')
                                    ->where('patios.patio_id', $patio_id);
                            } else {
                                $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                    ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                    ->join('patios','bloques.patio_id','=','patios.patio_id')
                                    ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                                        'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_fecha_entrega','vin_estado_inventario_desc',
                                        'patio_nombre', 'bloque_nombre', 'ubic_patio_id', 'ubic_patio_fila',
                                        'ubic_patio_columna');
                            }
                        }
                    }

                    $tabla_vins = $query->get();
                }
            }
        }



        foreach($tabla_vins as $vins){

            $guia = DB::table('guia_vins')
                ->join('guias','guia_vins.guia_id','guias.guia_id')
                ->select('guias.guia_ruta')
                ->where('guia_vins.vin_id', $vins->vin_id)
                ->first();



            if($guia){
                $vins->vin_downloadGuiaN =  "Guia Cargada";
            }else{
                $vins->vin_downloadGuiaN =  "Sin Guia";
            }

            $vins->vin_downloadGuia =  route('vin.downloadGuia', Crypt::encrypt($vins->vin_id));
            $vins->vin_encrypt =  Crypt::encrypt($vins->vin_id);
            $vins->vin_guia =  route('vin.guia', Crypt::encrypt($vins->vin_id));
            $vins->vin_editarestado =  route('vin.editarestado', Crypt::encrypt($vins->vin_id));
            $vins->vin_edit =  route('vin.edit', Crypt::encrypt($vins->vin_id));
            $vins->rol_id = auth()->user()->rol_id;
            $vins->vin_marca = Vin::find($vins->vin_id)->oneMarca->marca_nombre;
            $vins->vin_fecha_entrega = "";



        }

        return response()->json(
            $tabla_vins
        );

    }


    public function index3(Request $request)
    {



        /** Búsqueda de vins para la cabecera de la vista de planificación */
        $vins = Vin::all();

        $tipo_campanias_array = TipoCampania::select('tipo_campania_id', 'tipo_campania_descripcion')
            ->where('deleted_at', null)
            ->pluck('tipo_campania_descripcion', 'tipo_campania_id');

        $patios = DB::table('patios')
            ->select('patio_id', 'patio_nombre')
            ->pluck('patio_nombre', 'patio_id');

        $users = User::select(DB::raw("CONCAT(user_nombre,' ', user_apellido) AS user_nombres"), 'user_id')
            ->orderBy('user_id')
            ->where('deleted_at', null)
            ->pluck('user_nombres', 'user_id')
            ->all();

        $empresas = Empresa::select('empresa_id', 'empresa_razon_social')
            ->orderBy('empresa_id')
            ->where('deleted_at', null)
            ->pluck('empresa_razon_social', 'empresa_id')
            ->all();

        $estadosInventario = DB::table('vin_estado_inventarios')
            ->select('vin_estado_inventario_id', 'vin_estado_inventario_desc')
            ->pluck('vin_estado_inventario_desc', 'vin_estado_inventario_id');

        $subEstadosInventario = DB::table('vin_sub_estado_inventarios')
            ->select('vin_sub_estado_inventario_id', 'vin_sub_estado_inventario_desc')
            ->pluck('vin_sub_estado_inventario_desc', 'vin_sub_estado_inventario_id');

        $marcas = DB::table('marcas')
            ->select('marca_id', 'marca_nombre')
            ->where('deleted_at', null)
            ->pluck('marca_nombre', 'marca_id');

        $tabla_vins = [];


        /** A partir de aqui las consultas del cuadro de busqueda */
        if($request->has('empresa_id') || $request->has('vin_numero') || $request->has('estadoinventario_id') || $request->has('patio_id') || $request->has('marca_id')){

            $estado = DB::table('vin_estado_inventarios')
                ->where('vin_estado_inventario_id',$request->estadoinventario_id)
                ->get();

            if(!empty($estado[0]->vin_estado_inventario_id)){
                $estado_id = $estado[0]->vin_estado_inventario_id;
            }else{
                $estado_id = 0;
            }

            $marca = Marca::find($request->marca_id);

            if($marca)
            {
                $marca_nombre = $marca->marca_nombre;
            }else{
                $marca_nombre = 'Sin marca';
            }

            $user = DB::table('users')
                ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                ->where('empresas.empresa_id',$request->empresa_id)
                ->get();

            if(!empty($user[0]->empresa_id))
            {
                $user_empresa_id = $user[0]->empresa_id;
            }else{
                $user_empresa_id = 0;
            }

            $patio = DB::table('patios')
                ->where('patio_id', $request->patio_id)
                ->get();

            if(!empty($patio[0]->patio_nombre))
            {
                $patio_id = $patio[0]->patio_id;
            }else{
                $patio_id = 0;
            }

            if(!empty($request->vin_numero)){
                foreach(explode(PHP_EOL,$request->vin_numero) as $row){
                    $arreglo_vins[] = trim($row);
                }

                $message = [];

                foreach($arreglo_vins as $v){
                    $validate = DB::table('vins')
                        ->where('vin_codigo', $v)
                        ->orWhere('vin_patente', $v)
                        ->exists();

                    if($validate == true){
                        $query = DB::table('vins')
                            ->join('users','users.user_id','=','vins.user_id')
                            ->join('vin_estado_inventarios','vins.vin_estado_inventario_id','=','vin_estado_inventarios.vin_estado_inventario_id')
                            ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                            ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                                'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_fecha_entrega', 'vin_estado_inventario_desc')
                            ->where('vin_codigo',$v)
                            ->orWhere('vin_patente', $v);

                        if($user_empresa_id > 0){
                            $query->where('empresas.empresa_id',$user_empresa_id);
                        }

                        if($marca_nombre != 'Sin marca'){
                            $query->where('vin_marca',$marca->marca_id);
                            //$query->WhereRaw('upper(vin_marca) like(?)',strtoupper($marca_nombre));
                        }

                        if($estado_id > 0){
                            $query->where('vins.vin_estado_inventario_id', $estado_id);

                            if($estado_id == 4 || $estado_id == 5 || $estado_id == 6) {
                                if($patio_id > 0){
                                    $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                        ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                        ->join('patios','bloques.patio_id','=','patios.patio_id')
                                        ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                                            'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_fecha_entrega', 'vin_estado_inventario_desc',
                                            'patio_nombre', 'bloque_nombre', 'ubic_patio_id', 'ubic_patio_fila',
                                            'ubic_patio_columna')
                                        ->where('patios.patio_id', $patio_id);
                                } else {
                                    $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                        ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                        ->join('patios','bloques.patio_id','=','patios.patio_id')
                                        ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                                            'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_fecha_entrega','vin_estado_inventario_desc','patio_nombre', 'bloque_nombre',
                                            'ubic_patio_id',  'ubic_patio_fila', 'ubic_patio_columna');
                                }
                            }
                        } else {
                            $vin_estado_id = Vin::where('vin_codigo', $v)
                                ->orWhere('vin_patente', $v)
                                ->value('vin_estado_inventario_id');

                            $query->where('vins.vin_estado_inventario_id', $vin_estado_id);

                            if($vin_estado_id == 4 || $vin_estado_id == 5 || $vin_estado_id == 6) {
                                if($patio_id > 0){
                                    $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                        ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                        ->join('patios','bloques.patio_id','=','patios.patio_id')
                                        ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                                            'empresas.empresa_razon_social', 'vin_fec_ingreso','vin_fecha_entrega', 'vin_estado_inventario_desc',
                                            'patio_nombre', 'bloque_nombre', 'ubic_patio_id', 'ubic_patio_fila',
                                            'ubic_patio_columna')
                                        ->where('patios.patio_id', $patio_id);
                                } else {
                                    $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                        ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                        ->join('patios','bloques.patio_id','=','patios.patio_id')
                                        ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                                            'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_fecha_entrega','vin_estado_inventario_desc',
                                            'patio_nombre', 'bloque_nombre', 'ubic_patio_id', 'ubic_patio_fila',
                                            'ubic_patio_columna');
                                }
                            }
                        }

                        array_push($tabla_vins, $query->first());
                    } else {
                        if(count($arreglo_vins) >= 1){
                            $message[$v] = "Vin o patente: " . $v . " no se encuentra en la lista";
                        } else {
                            $query = DB::table('vins')
                                ->join('users','users.user_id','=','vins.user_id')
                                ->join('vin_estado_inventarios','vins.vin_estado_inventario_id','=','vin_estado_inventarios.vin_estado_inventario_id')
                                ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                                ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                                    'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_fecha_entrega', 'vin_estado_inventario_desc');

                            if($user_empresa_id > 0){
                                $query->where('empresas.empresa_id',$user_empresa_id);
                            }

                            if($marca_nombre != 'Sin marca'){
                                $query->where('vin_marca',$marca->marca_id);
                                //$query->WhereRaw('upper(vin_marca) like(?)',strtoupper($marca_nombre));
                            }

                            if($estado_id > 0){
                                $query->where('vins.vin_estado_inventario_id', $estado_id);

                                if($estado_id == 4 || $estado_id == 5 || $estado_id == 6) {
                                    if($patio_id > 0){
                                        $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                            ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                            ->join('patios','bloques.patio_id','=','patios.patio_id')
                                            ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                                                'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_fecha_entrega','vin_estado_inventario_desc',
                                                'patio_nombre', 'bloque_nombre', 'ubic_patio_id', 'ubic_patio_fila',
                                                'ubic_patio_columna')
                                            ->where('patios.patio_id', $patio_id);
                                    } else {
                                        $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                            ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                            ->join('patios','bloques.patio_id','=','patios.patio_id')
                                            ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                                                'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_fecha_entrega', 'vin_estado_inventario_desc',
                                                'patio_nombre', 'bloque_nombre', 'ubic_patio_id', 'ubic_patio_fila',
                                                'ubic_patio_columna');
                                    }
                                }
                            }

                            $tabla_vins = $query->get();
                        }
                    }
                }
            }else{
                $query = DB::table('vins')
                    ->join('users','users.user_id','=','vins.user_id')
                    ->join('vin_estado_inventarios','vins.vin_estado_inventario_id','=','vin_estado_inventarios.vin_estado_inventario_id')
                    ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                    ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                        'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_fecha_entrega', 'vin_estado_inventario_desc');

                if($user_empresa_id > 0){
                    $query->where('empresas.empresa_id',$user_empresa_id);
                }

                if($marca_nombre != 'Sin marca'){
                    $query->where('vin_marca',$marca->marca_id);
                    //$query->WhereRaw('upper(vin_marca) like(?)',strtoupper($marca_nombre));
                }

                if($estado_id > 0){
                    $query->where('vins.vin_estado_inventario_id', $estado_id);

                    if($estado_id == 4 || $estado_id == 5 || $estado_id == 6) {
                        if($patio_id > 0){
                            $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                ->join('patios','bloques.patio_id','=','patios.patio_id')
                                ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                                    'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_fecha_entrega','vin_estado_inventario_desc',
                                    'patio_nombre', 'bloque_nombre', 'ubic_patio_id', 'ubic_patio_fila',
                                    'ubic_patio_columna')
                                ->where('patios.patio_id', $patio_id);
                        } else {
                            $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                ->join('patios','bloques.patio_id','=','patios.patio_id')
                                ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                                    'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_fecha_entrega','vin_estado_inventario_desc',
                                    'patio_nombre', 'bloque_nombre', 'ubic_patio_id', 'ubic_patio_fila',
                                    'ubic_patio_columna');
                        }
                    }
                }

                $tabla_vins = $query->get();
            }
        }

        // dd($tabla_vins);

        return view('vin.index', compact('tabla_vins', 'users','empresas', 'estadosInventario', 'subEstadosInventario', 'patios', 'marcas'));
    }


    public function index2(Request $request)
    {

        $user_empresa_id  = Auth::user()->belongsToEmpresa->empresa_id;

        $estadosInventario = DB::table('vin_estado_inventarios')
            ->select('vin_estado_inventario_id', 'vin_estado_inventario_desc')
            ->pluck('vin_estado_inventario_desc', 'vin_estado_inventario_id');

        $subEstadosInventario = DB::table('vin_sub_estado_inventarios')
            ->select('vin_sub_estado_inventario_id', 'vin_sub_estado_inventario_desc')
            ->pluck('vin_sub_estado_inventario_desc', 'vin_sub_estado_inventario_id');

        $marcas = DB::table('marcas')
            ->select('marca_id', 'marca_nombre')
            ->where('deleted_at', null)
            ->pluck('marca_nombre', 'marca_id');

        $tipo_campanias_array = TipoCampania::select('tipo_campania_id', 'tipo_campania_descripcion')
            ->where('deleted_at', null)
            ->pluck('tipo_campania_descripcion', 'tipo_campania_id');

        $patios = DB::table('patios')
            ->select('patio_id', 'patio_nombre')
            ->pluck('patio_nombre', 'patio_id');



        /** Búsqueda de los Vins */

        if(!($request->has('vin_numero') || $request->has('estadoinventario_id') || $request->has('patio_id') || $request->has('marca_id'))){
            /** Búsqueda de vins para la cabecera de la vista de solicitud de campañas */
            $tabla_vins = Vin::join('users','users.user_id','=','vins.user_id')
                ->join('empresas','empresas.empresa_id','=','users.empresa_id')
                ->join('ubic_patios', 'ubic_patios.vin_id', '=', 'vins.vin_id')
                ->join('bloques', 'bloques.bloque_id', '=', 'ubic_patios.bloque_id')
                ->join('patios', 'patios.patio_id', '=', 'bloques.patio_id')
                ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                    'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_fecha_entrega','vin_estado_inventario_desc', 'patio_nombre', 'bloque_nombre',
                    'ubic_patio_id', 'ubic_patio_fila', 'ubic_patio_columna')
                ->orderByRaw('ubic_patio_fila, ubic_patio_columna ASC')
                ->where('users.empresa_id', $user_empresa_id )
                ->get();
        } else {
            /** A partir de aqui las consultas del cuadro de busqueda */

            $estado = DB::table('vin_estado_inventarios')
                ->where('vin_estado_inventario_id', $request->estadoinventario_id)
                ->get();

            if(!empty($estado[0]->vin_estado_inventario_id)){
                $estado_id = $estado[0]->vin_estado_inventario_id;
            }else{
                $estado_id = 0;
            }

            $marca = Marca::find($request->marca_id);

            if($marca)
            {
                $marca_nombre = $marca->marca_nombre;
            }else{
                $marca_nombre = 'Sin marca';
            }

            $patio = DB::table('patios')
                ->where('patio_id', $request->patio_id)
                ->get();

            if(!empty($patio[0]->patio_nombre))
            {
                $patio_id = $patio[0]->patio_id;
            }else{
                $patio_id = 0;
            }

            if(!empty($request->vin_numero)){
                $tabla_vins = [];

                foreach(explode(PHP_EOL,$request->vin_numero) as $row){
                    $arreglo_vins[] = trim($row);
                }

                $message = [];


                foreach($arreglo_vins as $v){

                    $validate = DB::table('vins')
                        ->where('vin_codigo', $v)
                        ->orWhere('vin_patente', $v)
                        ->exists();

                    if($validate == true){
                        $query = DB::table('vins')
                            ->join('users','users.user_id','=','vins.user_id')
                            ->join('vin_estado_inventarios','vins.vin_estado_inventario_id','=','vin_estado_inventarios.vin_estado_inventario_id')
                            ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                            ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                                'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_fecha_entrega', 'vin_estado_inventario_desc')
                            ->where('vin_codigo',$v)
                            ->orWhere('vin_patente',$v)
                            ->where('empresas.empresa_id', $user_empresa_id);

                        if($marca_nombre != 'Sin marca'){
                            $query->where('vin_marca',$marca->marca_id);
                            //$query->WhereRaw('upper(vin_marca) like(?)',strtoupper($marca_nombre));
                        }

                        if($estado_id > 0){
                            $query->where('vins.vin_estado_inventario_id', $estado_id);

                            if($estado_id == 4 || $estado_id == 5 || $estado_id == 6) {
                                $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                    ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                    ->join('patios','bloques.patio_id','=','patios.patio_id')
                                    ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                                        'empresas.empresa_razon_social', 'vin_fec_ingreso','vin_fecha_entrega',  'vin_estado_inventario_desc', 'patio_nombre', 'bloque_nombre',
                                        'ubic_patio_id', 'ubic_patio_fila', 'ubic_patio_columna')
                                    ->where('patios.patio_id', $patio_id);
                            }
                        } else {
                            $vin_estado_id = Vin::where('vin_codigo', $v)
                                ->orWhere('vin_patente', $v)
                                ->value('vin_estado_inventario_id');

                            $query->where('vins.vin_estado_inventario_id', $vin_estado_id);

                            if($vin_estado_id == 4 || $vin_estado_id == 5 || $vin_estado_id == 6) {
                                if($patio_id > 0){
                                    $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                        ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                        ->join('patios','bloques.patio_id','=','patios.patio_id')
                                        ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                                            'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_fecha_entrega','vin_estado_inventario_desc', 'patio_nombre', 'bloque_nombre',
                                            'ubic_patio_id', 'ubic_patio_fila', 'ubic_patio_columna')
                                        ->where('patios.patio_id', $patio_id);
                                } else {
                                    $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                        ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                        ->join('patios','bloques.patio_id','=','patios.patio_id')
                                        ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                                            'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_fecha_entrega','vin_estado_inventario_desc', 'patio_nombre', 'bloque_nombre',
                                            'ubic_patio_id', 'ubic_patio_fila', 'ubic_patio_columna');
                                }
                            }
                        }

                        array_push($tabla_vins, $query->first());

                        /***************************************************************************
                         * } else {
                         * if(count($arreglo_vins) >= 1){
                         * $message[$v] = "Vin o patente: " . $v . " no se encuentra en la lista";
                         * }
                         **************************************************************************/

                    } else {



                        $query = DB::table('vins')
                            ->join('users','users.user_id','=','vins.user_id')
                            ->join('vin_estado_inventarios','vins.vin_estado_inventario_id','=','vin_estado_inventarios.vin_estado_inventario_id')
                            ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                            ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                                'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_fecha_entrega', 'vin_estado_inventario_desc')
                            ->where('vins.user_id',$user_empresa_id);

                        if($marca_nombre != 'Sin marca'){
                            $query->where('vin_marca',$marca->marca_id);
                            //$query->WhereRaw('upper(vin_marca) like(?)',strtoupper($marca_nombre));
                        }

                        if($estado_id > 0){
                            $query->where('vins.vin_estado_inventario_id', $estado_id);

                            if($estado_id == 4 || $estado_id == 5 || $estado_id == 6) {
                                if($patio_id > 0){
                                    $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                        ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                        ->join('patios','bloques.patio_id','=','patios.patio_id')
                                        ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                                            'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_fecha_entrega', 'vin_estado_inventario_desc', 'patio_nombre', 'bloque_nombre',
                                            'ubic_patio_id', 'ubic_patio_fila', 'ubic_patio_columna')
                                        ->where('patios.patio_id', $patio_id);
                                } else {
                                    $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                        ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                        ->join('patios','bloques.patio_id','=','patios.patio_id')
                                        ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                                            'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_fecha_entrega', 'vin_estado_inventario_desc', 'patio_nombre', 'bloque_nombre',
                                            'ubic_patio_id', 'ubic_patio_fila', 'ubic_patio_columna');
                                }
                            }
                        }

                        $tabla_vins = $query->get();
                    }
                }
            }else{
                $query = DB::table('vins')
                    ->join('users','users.user_id','=','vins.user_id')
                    ->join('vin_estado_inventarios','vins.vin_estado_inventario_id','=','vin_estado_inventarios.vin_estado_inventario_id')
                    ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                    ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                        'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_fecha_entrega','vin_estado_inventario_desc')
                    ->where('empresas.empresa_id', $user_empresa_id);

                if($marca_nombre != 'Sin marca'){
                    $query->where('vin_marca',$marca->marca_id);
                    //$query->WhereRaw('upper(vin_marca) like(?)',strtoupper($marca_nombre));
                }

                if($estado_id > 0){
                    $query->where('vins.vin_estado_inventario_id', $estado_id);

                    if($estado_id == 4 || $estado_id == 5 || $estado_id == 6) {
                        if($patio_id > 0){
                            $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                ->join('patios','bloques.patio_id','=','patios.patio_id')
                                ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                                    'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_fecha_entrega','vin_estado_inventario_desc', 'patio_nombre', 'bloque_nombre',
                                    'ubic_patio_id', 'ubic_patio_fila', 'ubic_patio_columna')
                                ->where('patios.patio_id', $patio_id);
                        } else {
                            $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                ->join('patios','bloques.patio_id','=','patios.patio_id')
                                ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                                    'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_fecha_entrega','vin_estado_inventario_desc', 'patio_nombre', 'bloque_nombre',
                                    'ubic_patio_id', 'ubic_patio_fila', 'ubic_patio_columna');
                        }
                    }
                }

                $tabla_vins = $query->get();
            }
        }
        return view('vin.index', compact('tabla_vins', 'estadosInventario', 'subEstadosInventario', 'patios', 'marcas'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $vins = Vin::all();

        $vins = Vin::all();

        $users = User::select(DB::raw("CONCAT(user_nombre,' ', user_apellido) AS user_nombres"), 'user_id')
            ->orderBy('user_id')
            ->where('deleted_at', null)
            ->pluck('user_nombres', 'user_id')
            ->all();

        $empresas = Empresa::select('empresa_id', 'empresa_razon_social')
            ->orderBy('empresa_id')
            ->where('deleted_at', null)
            ->pluck('empresa_razon_social', 'empresa_id')
            ->all();

        $estadosInventario = DB::table('vin_estado_inventarios')
            ->select('vin_estado_inventario_id', 'vin_estado_inventario_desc')
            ->pluck('vin_estado_inventario_desc', 'vin_estado_inventario_id');

        $subEstadosInventario = DB::table('vin_sub_estado_inventarios')
            ->select('vin_sub_estado_inventario_id', 'vin_sub_estado_inventario_desc')
            ->pluck('vin_sub_estado_inventario_desc', 'vin_sub_estado_inventario_id');

        $marcas = DB::table('marcas')
            ->select('marca_id', 'marca_nombre')
            ->where('deleted_at', null)
            ->pluck('marca_nombre', 'marca_id');

        $patios = DB::table('patios')
            ->select('patio_id', 'patio_nombre')
            ->pluck('patio_nombre', 'patio_id');


        return view('vin.create', compact(/*'vins', */'users','empresas', 'estadosInventario', 'subEstadosInventario'));
    }

    public function empresa(Request $request, $id_empresa){
        try {
            $empresa_id = Crypt::decrypt($id_empresa);
        } catch (DecryptException $e) {
            abort(404);
        }

        if ($request->ajax()){
            $users = DB::table('users')
                ->where('users.user_estado', '=', 1)
                ->where('users.deleted_at', '=', null)
                ->where('users.empresa_id', '=', $empresa_id)
                ->select(DB::raw("CONCAT(users.user_nombre,' ',users.user_apellido) AS user_nombres"), 'users.user_id')
                ->orderBy('users.user_id')
                ->where('deleted_at', null)
                ->pluck('user_nombres', 'users.user_id');

            $ids = DB::table('users')
                ->where('users.user_estado', '=', 1)
                ->where('users.deleted_at', '=', null)
                ->where('users.empresa_id', '=', $empresa_id)
                ->select(DB::raw("CONCAT(users.user_nombre,' ',users.user_apellido) AS user_nombres"), 'users.user_id')
                ->orderBy('users.user_id')
                ->where('deleted_at', null)
                ->pluck('users.user_id', 'user_nombres');

            return response()->json([
                'success' => true,
                'message' => "Data de usuarios por empresa disponible",
                'ids' => $ids,
                'users' => $users,
            ]);
        }
    }

    public function estadoInventario(Request $request, $id_estado_inventario){
        try {
            $estado_inventario_id = Crypt::decrypt($id_estado_inventario);
        } catch (DecryptException $e) {
            abort(404);
        }

        if ($request->ajax()){
            if($estado_inventario_id == 4 || $estado_inventario_id == 5){
                $subEstados = DB::table('vin_sub_estado_inventarios')
                    ->where('vin_sub_estado_inventarios.vin_estado_inventario_id', '=', $estado_inventario_id)
                    ->select('vin_sub_estado_inventarios.vin_sub_estado_inventario_id', 'vin_sub_estado_inventarios.vin_sub_estado_inventario_desc')
                    ->orderBy('vin_sub_estado_inventarios.vin_sub_estado_inventario_id')
                    ->pluck('vin_sub_estado_inventarios.vin_sub_estado_inventario_desc', 'vin_sub_estado_inventarios.vin_sub_estado_inventario_id');

                $ids = DB::table('vin_sub_estado_inventarios')
                    ->where('vin_sub_estado_inventarios.vin_estado_inventario_id', '=', $estado_inventario_id)
                    ->select('vin_sub_estado_inventarios.vin_sub_estado_inventario_id', 'vin_sub_estado_inventarios.vin_sub_estado_inventario_desc')
                    ->orderBy('vin_sub_estado_inventarios.vin_sub_estado_inventario_id')
                    ->pluck('vin_sub_estado_inventarios.vin_sub_estado_inventario_id', 'vin_sub_estado_inventarios.vin_sub_estado_inventario_desc');

                return response()->json([
                    'success' => true,
                    'message' => "Data de Sub Estados de Inventario disponible",
                    'ids' => $ids,
                    'subEstados' => $subEstados,
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => "No hay data de inventario para esta opción",
                    'ids' => null,
                    'subEstados' => null,
                ]);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = DB::table('vins')->where('vin_codigo', $request->vin_codigo)->exists();
        $fecha = date('Y-m-d');
        $user = User::find(Auth::id());

        if($validate == true)
        {
            flash('El código '.$request->vin_codigo.'  ya existe en la base de datos')->warning();
            return redirect('/vin');
        }else

            $id_estado_inventario =  Crypt::decrypt($request->vin_estado_inventario_id);

        try {
            DB::beginTransaction();

            $vin = new Vin();
            $vin->vin_codigo = $request->vin_codigo;
            $vin->vin_patente = $request->vin_patente;
            $vin->vin_marca = $request->vin_marca;
            $vin->vin_modelo = $request->vin_modelo;
            $vin->vin_color = $request->vin_color;
            $vin->vin_motor = $request->vin_motor;
            $vin->vin_segmento = $request->vin_segmento;
            $vin->vin_fec_ingreso = $request->vin_fec_ingreso;
            $vin->user_id = (int)$request->user_id;
            $vin->vin_estado_inventario_id = $id_estado_inventario;
            $vin->vin_sub_estado_inventario_id = $request->vin_sub_estado_inventario_id;

            $vin->save();

            // Guardar historial del cambio
            DB::insert('INSERT INTO historico_vins
                (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                [
                    $vin->vin_id,
                    $vin->vin_estado_inventario_id,
                    $fecha,
                    $user->user_id,
                    null,
                    null,
                    $user->belongsToEmpresa->empresa_id,
                    "VIN Creado.",
                    "Origen Externo.",
                    "Patio: BLoque y Ubicación por asignar."
                ]
            );

            DB::commit();

            flash('El VIN se registró correctamente.')->success();
            return redirect('vin');

        }catch (\Exception $e) {
            DB::rollBack();

            flash('Error registrando el VIN.')->error();
            flash($e->getMessage())->error();
            return redirect('vin');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vin_id =  Crypt::decrypt($id);
        $vin = Vin::findOrfail($vin_id);



        $users = User::select(DB::raw("CONCAT(user_nombre,' ', user_apellido) AS user_nombres"), 'user_id')
            ->orderBy('user_id')
            ->where('deleted_at', null)
            ->pluck('user_nombres', 'user_id')
            ->all();

        $marcas = DB::table('marcas')
            ->select('marca_id','marca_nombre')
            ->pluck('marca_nombre','marca_id');

        $estadosInventario = DB::table('vin_estado_inventarios')
            ->select('vin_estado_inventario_id', 'vin_estado_inventario_desc')
            ->pluck('vin_estado_inventario_desc', 'vin_estado_inventario_id');

        $subEstadosInventario = DB::table('vin_sub_estado_inventarios')
            ->select('vin_sub_estado_inventario_id', 'vin_sub_estado_inventario_desc')
            ->pluck('vin_sub_estado_inventario_desc', 'vin_sub_estado_inventario_id');

        return view('vin.edit', compact('vin', 'users', 'marcas', 'estadosInventario', 'subEstadosInventario'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $vin_id =  Crypt::decrypt($id);
        $fecha = date('Y-m-d');
        $user = User::find(Auth::id());

        try {
            DB::beginTransaction();

            $vin = Vin::findOrfail($vin_id);
          //  dd($vin);

            $estado_previo = $vin->vin_estado_inventario_id;
            $estado_nuevo = Crypt::decrypt($request->vin_estado_inventario_id);

            $vin->vin_codigo = $request->vin_codigo;
            $vin->vin_patente = $request->vin_patente;
            $vin->vin_marca = $request->vin_marca;
            $vin->vin_modelo = $request->vin_modelo;
            $vin->vin_color = $request->vin_color;
            $vin->vin_motor = $request->vin_motor;
            $vin->vin_segmento = $request->vin_segmento;
            $vin->vin_fec_ingreso = $request->vin_fec_ingreso;
            $vin->user_id = (int)$request->user_id;
            $vin->vin_sub_estado_inventario_id = $request->vin_sub_estado_inventario_id;

            if($estado_previo != $estado_nuevo){
                // Pasar el VIN de estado "Anunciado" a estado "Arribado"
                if($estado_previo == 1 && $estado_nuevo == 2){
                    $vin->vin_estado_inventario_id = $estado_nuevo;
                    $vin->save();

                    // Guardar historial del cambio
                    DB::insert('INSERT INTO historico_vins
                        (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                        origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                        [
                            $vin->vin_id,
                            $estado_nuevo,
                            $fecha,
                            $user->user_id,
                            null,
                            null,
                            $user->belongsToEmpresa->empresa_id,
                            "VIN Arribado.",
                            "Origen Externo: Puerto.",
                            "Patio: BLoque y Ubicación por asignar."
                        ]
                    );
                } else if($estado_nuevo == 7 || $estado_nuevo == 8) {    // Pasar el VIN desde cualquier estado a "Suprimido" o "Entregado"
                    $bloque = null;

                    if($estado_previo == 4 || $estado_previo == 5 || $estado_previo == 6){ //VIN previamente en patio
                        $ubic_patio = UbicPatio::where('vin_id', $vin->vin_id)->first();

                        if(isset($ubic_patio->ubic_patio_id)) // Liberar ubicación ocupada
                        {
                            $ubic_patio->vin_id = null;
                            $ubic_patio->ubic_patio_ocupada = false;
                            $ubic_patio->save();
                            $bloque = $ubic_patio->bloque_id;
                        }
                    }

                    $vin->vin_estado_inventario_id = $estado_nuevo;
                    $vin->save();

                    if($estado_nuevo == 8){ // Estado nuevo VIN Entregado
                        // Guardar historial del cambio
                        if($bloque != null){
                            DB::insert('INSERT INTO historico_vins
                                (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                                origen_id, destino_id, empresa_id, historico_vin_descripcion, destino_texto)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
                                [
                                    $vin->vin_id,
                                    $estado_nuevo,
                                    $fecha,
                                    $user->user_id,
                                    $bloque,
                                    null,
                                    $user->belongsToEmpresa->empresa_id,
                                    "VIN Entregado.",
                                    "Destino externo. VIN entregado."
                                ]
                            );
                        } else {
                            DB::insert('INSERT INTO historico_vins
                                (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                                origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                                [
                                    $vin->vin_id,
                                    $estado_nuevo,
                                    $fecha,
                                    $user->user_id,
                                    null,
                                    null,
                                    $user->belongsToEmpresa->empresa_id,
                                    "VIN Entregado.",
                                    "Sin ubicación en bloque para la entrega.",
                                    "Destino externo. VIN entregado."
                                ]
                            );
                        }
                    } else{ // Estado nuevo VIN Suprimido
                        if($bloque != null){
                            DB::insert('INSERT INTO historico_vins
                                (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                                origen_id, destino_id, empresa_id, historico_vin_descripcion, destino_texto)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
                                [
                                    $vin->vin_id,
                                    $estado_nuevo,
                                    $fecha,
                                    $user->user_id,
                                    $bloque,
                                    null,
                                    $user->belongsToEmpresa->empresa_id,
                                    "VIN Suprimido.",
                                    "VIN Suprimido. Sin disponibilidad ni ubicación física."
                                ]
                            );
                        } else {
                            DB::insert('INSERT INTO historico_vins
                                (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                                origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                                [
                                    $vin->vin_id,
                                    $estado_nuevo,
                                    $fecha,
                                    $user->user_id,
                                    null,
                                    null,
                                    $user->belongsToEmpresa->empresa_id,
                                    "VIN Suprimido.",
                                    "Sin ubicación previa en bloque.",
                                    "VIN Suprimido. Sin disponibilidad ni ubicación física."
                                ]
                            );
                        }
                    }
                }

            } else{
                $vin->vin_estado_inventario_id = $estado_previo;
                $vin->save();
            }

            DB::commit();

            flash('VIN actualizado correctamente.')->success();
            return redirect('vin');

        }catch (\Exception $e) {
            DB::rollBack();

            flash('Error al actualizar el VIN.')->error();
            flash($e->getMessage())->error();
            return redirect('vin');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vin_id =  Crypt::decrypt($id);

        try {
            $vin = Vin::findOrfail($vin_id)->delete();

            flash('Los datos del VIN han sido eliminados satisfactoriamente.')->success();
            return redirect('vin');
        }catch (\Exception $e) {

            flash('Error al intentar eliminar los datos del VIN.')->error();
            //flash($e->getMessage())->error();
            return redirect('vin');
        }
    }

    public function cargamasiva(){

        $users = User::select(DB::raw("CONCAT(user_nombre,' ', user_apellido) AS user_nombres"), 'user_id')
            ->orderBy('user_id')
            ->where('deleted_at', null)
            ->pluck('user_nombres', 'user_id')
            ->all();

        $empresas = Empresa::select('empresa_id', 'empresa_razon_social')
            ->orderBy('empresa_id')
            ->where('deleted_at', null)
            ->pluck('empresa_razon_social', 'empresa_id')
            ->all();

        $estadosInventario = DB::table('vin_estado_inventarios')
            ->select('vin_estado_inventario_id', 'vin_estado_inventario_desc')
            ->pluck('vin_estado_inventario_desc', 'vin_estado_inventario_id');

        $subEstadosInventario = DB::table('vin_sub_estado_inventarios')
            ->select('vin_sub_estado_inventario_id', 'vin_sub_estado_inventario_desc')
            ->pluck('vin_sub_estado_inventario_desc', 'vin_sub_estado_inventario_id');


        return view('vin.cargamasiva');


    }

    public function loadexcel(Request $request)
    {

        global $validar;
        global $columna;
        $columna = '';
        $validar = false;
        $archivo = $_FILES["carga_masiva"]["name"];
        $carbon = new \Carbon\Carbon();
        $date = $carbon->now();

        //list($anio_actual, $mes_actual) = explode('-',$request->fecha_up_hidden);


        $nombre_archivo = $request->file('carga_masiva')->getClientOriginalName();
        $extension = $request->file('carga_masiva')->getClientOriginalExtension();

        $archivo = substr($nombre_archivo, 0, -(strlen('.'.$extension))) .  '.' . $extension;

        if ($extension == 'xlsx' || $extension == 'XLSX' || $extension == 'xls' || $extension == 'XLS') {


            $excelVins = $request->file('carga_masiva');
            $extensionArchivo = $excelVins->extension();
            $path = $excelVins->storeAs(
                'CargaVins',
                "CargaVins" . ' - '.date('Y-m-d').' - '.Auth::id().' - '.\Carbon\Carbon::now()->timestamp.' - '.$request->vin_estado_inventario_id.' - '.$request->vin_sub_estado_inventario_id.'-'. '.'.$extensionArchivo
            );


            (new VinsCollectionImport)->import(request()->file('carga_masiva'));
            flash('Los vins fueron cargados exitosamente.')->success();
            return redirect('vin');

            //(new VinsImport)->import(request());

            //Excel::import(new VinsImport, $path);


        }else
        {
            flash('Formato de archivo invalido.')->danger();
            return redirect('vin');
        }

    }



    public function editarestado($id)
    {
        $vin_id =  Crypt::decrypt($id);
        $vin = Vin::findOrfail($vin_id);

        $user = User::find($vin->user_id)->first();

        $users = User::select(DB::raw("CONCAT(user_nombre,' ', user_apellido) AS user_nombres"), 'user_id')
            ->orderBy('user_id')
            ->where('deleted_at', null)
            ->pluck('user_nombres', 'user_id')
            ->all();

        $empresas = Empresa::select('empresa_id', 'empresa_razon_social')
            ->orderBy('empresa_id')
            ->where('deleted_at', null)
            ->pluck('empresa_razon_social', 'empresa_id')
            ->all();

        $estadosInventario = DB::table('vin_estado_inventarios')
            ->select('vin_estado_inventario_id', 'vin_estado_inventario_desc')
            ->pluck('vin_estado_inventario_desc', 'vin_estado_inventario_id');

        $posiciones_bloques = DB::table('patios')
            ->join('bloques','patios.patio_id','=','bloques.patio_id')
            ->join('ubic_patios','ubic_patios.bloque_id','=','bloques.bloque_id')
            ->where('ubic_patio_ocupada',false)
            ->select('ubic_patio_ocupada')
            ->get();

        return view('vin.cambiaestado', compact('vin', 'user', 'users','empresas', 'estadosInventario'));
    }

    public function cambiaestado(Request $request, $id)
    {
        $vin_id =  Crypt::decrypt($id);
        $fecha = date('Y-m-d');

        $user = User::find(Auth::id());

        try {

            DB::beginTransaction();

            $vin = Vin::findOrfail($vin_id);

            $estado_previo = $vin->vin_estado_inventario_id;
            $estado_nuevo = $request->vin_estado_inventario_id;

            // Pasar el VIN de estado "Anunciado" a estado "Arribado"
            if($estado_previo == 1 && $estado_nuevo == 2){
                $vin->vin_estado_inventario_id = $estado_nuevo;
                $vin->save();

                // Guardar historial del cambio
                DB::insert('INSERT INTO historico_vins
                    (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                    origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                    [
                        $vin->vin_id,
                        $estado_nuevo,
                        $fecha,
                        $user->user_id,
                        null,
                        null,
                        $user->belongsToEmpresa->empresa_id,
                        "VIN Arribado.",
                        "Origen Externo: Puerto.",
                        "Patio: BLoque y Ubicación por asignar."
                    ]
                );
            } else if($estado_nuevo == 7 || $estado_nuevo == 8) {    // Pasar el VIN desde cualquier estado a "Suprimido" o "Entregado"
                $bloque = null;

                if($estado_previo == 4 || $estado_previo == 5 || $estado_previo == 6){ //VIN previamente en patio
                    $ubic_patio = UbicPatio::where('vin_id', $vin->vin_id)->first();

                    if(isset($ubic_patio->ubic_patio_id)) // Liberar ubicación ocupada
                    {
                        $ubic_patio->vin_id = null;
                        $ubic_patio->ubic_patio_ocupada = false;
                        $ubic_patio->save();
                        $bloque = $ubic_patio->bloque_id;
                    }
                }

                $vin->vin_estado_inventario_id = $estado_nuevo;
                $vin->save();

                if($estado_nuevo == 8){ // Estado nuevo VIN Entregado
                    // Guardar historial del cambio
                    if($bloque != null){
                        DB::insert('INSERT INTO historico_vins
                            (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                            origen_id, destino_id, empresa_id, historico_vin_descripcion, destino_texto)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
                            [
                                $vin->vin_id,
                                $estado_nuevo,
                                $fecha,
                                $user->user_id,
                                $bloque,
                                null,
                                $user->belongsToEmpresa->empresa_id,
                                "VIN Entregado.",
                                "Destino externo. VIN entregado."
                            ]
                        );
                    } else {
                        DB::insert('INSERT INTO historico_vins
                            (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                            origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                            [
                                $vin->vin_id,
                                $estado_nuevo,
                                $fecha,
                                $user->user_id,
                                null,
                                null,
                                $user->belongsToEmpresa->empresa_id,
                                "VIN Entregado.",
                                "Sin ubicación en bloque para la entrega.",
                                "Destino externo. VIN entregado."
                            ]
                        );
                    }
                } else { // Estado nuevo VIN Suprimido
                    if($bloque != null){
                        DB::insert('INSERT INTO historico_vins
                            (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                            origen_id, destino_id, empresa_id, historico_vin_descripcion, destino_texto)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
                            [
                                $vin->vin_id,
                                $estado_nuevo,
                                $fecha,
                                $user->user_id,
                                $bloque,
                                null,
                                $user->belongsToEmpresa->empresa_id,
                                "VIN Suprimido.",
                                "Destino externo. VIN entregado."
                            ]
                        );
                    } else {
                        DB::insert('INSERT INTO historico_vins
                            (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                            origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                            [
                                $vin->vin_id,
                                $estado_nuevo,
                                $fecha,
                                $user->user_id,
                                $bloque,
                                null,
                                $user->belongsToEmpresa->empresa_id,
                                "VIN Suprimido.",
                                "Sin ubicación previa en bloque.",
                                "Destino externo. VIN entregado."
                            ]
                        );
                    }
                }
            } else if($estado_previo == 8 && $estado_nuevo == 1){
                $vin->vin_estado_inventario_id = $estado_nuevo;
                $vin->save();

                // Guardar historial del cambio
                DB::insert('INSERT INTO historico_vins
                    (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                    origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                    [
                        $vin->vin_id,
                        $estado_nuevo,
                        $fecha,
                        $user->user_id,
                        null,
                        null,
                        $user->belongsToEmpresa->empresa_id,
                        "VIN nuevamente Anunciado luego de haber sido entregado.",
                        "Sin ubicación previa en bloque. Reingreso de VIN.",
                        "Patio: BLoque y Ubicación por asignar."
                    ]
                );
            }

            DB::commit();

        }catch (\Exception $e) {
            DB::rollBack();
            flash('Error al actualizar el estado del VIN.')->error();
            flash($e->getMessage())->error();
            return redirect('vin');
        }
        flash('Estado actualizado correctamente.')->success();
        return redirect('vin');
    }

    public function downloadFile()
    {
        return Storage::response("PlanillasDescargas/CargaVin.xlsx");
    }

    public function guia($id)
    {
        $vin_id =  Crypt::decrypt($id);
        $vin = Vin::findOrfail($vin_id);

        return view('vin.addguia', compact('vin'));
    }

    public function addguia(Request $request, $id)
    {
        $vin_id =  Crypt::decrypt($id);
        $vin = Vin::findOrfail($vin_id);

        $fecha = date('Y-m-d');

        $empresa = DB::table('empresas')
            ->join('users', 'users.empresa_id','=','empresas.empresa_id')
            ->where('users.user_id',$vin->user_id)
            ->select('empresas.empresa_id')
            ->first();


        $guiaVin = $request->file('guia_vin');
        $extensionGuia = $guiaVin->extension();
        $path = $guiaVin->storeAs(
            'GuiaVin',
            "foto de documento ".'- '.Auth::id().' - '.date('Y-m-d').' - '.\Carbon\Carbon::now()->timestamp.'.'.$extensionGuia
        );

        try {


            $guia = new Guia();
            $guia->guia_ruta = $path;
            $guia->guia_fecha = $fecha;
            $guia->empresa_id = $empresa->empresa_id;

            $guia->save();

            $guia_vin = new GuiaVins();
            $guia_vin->vin_id = $vin_id;
            $guia_vin->guia_id = $guia->guia_id;
            $guia_vin->save();


            flash('La guia fue almacenada correctamente.')->success();
            return redirect('vin');

        }catch (\Exception $e) {


            flash('Error registrando de la guia.')->error();
            flash($e->getMessage())->error();
            return redirect('vin');
        }

    }

    public function downloadGuia($id)
    {
        $vin_id =  Crypt::decrypt($id);
        $vin = Vin::findOrfail($vin_id);

        $guia = DB::table('vins')
            ->join('guia_vins','guia_vins.vin_id','vins.vin_id')
            ->join('guias','guia_vins.guia_id','guias.guia_id')
            ->select('guias.guia_ruta')
            ->where('guia_vins.vin_id', $vin_id)
            ->get();

        if(!empty($guia[0]->guia_ruta))
        {

            return Storage::download($guia[0]->guia_ruta);

        }else{
            flash('El vin no tiene guia asociada.')->error();
            return redirect('vin');

        }


    }

    public function storeModalTareaLotes(Request $request)
    {
        $guiaVin = $request->file('guia_vin');
        $extensionGuia = $guiaVin->extension();
        $path = $guiaVin->storeAs(
            'GuiaVin',
            "foto de documento ".'- '.Auth::id().' - '.date('Y-m-d').' - '.\Carbon\Carbon::now()->timestamp.'.'.$extensionGuia
        );

            $i =0;
            foreach( $request->vin_ids as $vinid){
                $empresa = DB::table('vins')
                    ->join('users', 'vins.user_id','=','users.user_id')
                    ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                    ->where('vins.vin_id',$vinid)
                    ->select('empresas.empresa_id')
                    ->first();

                $empresa_id[$i] = $empresa->empresa_id;
                $i++;

                if($empresa_id[0] != $empresa->empresa_id){
                    flash('Error existen VIN de diferentes empresas.')->error();
                    return redirect()->route('vin.index');
                }


            }

        $fecha = date('Y-m-d');
        try {

            DB::beginTransaction();

            $guia = new Guia();
            $guia->guia_ruta = $path;
            $guia->guia_fecha = $fecha;
            $guia->empresa_id = $empresa_id[0];
            $guia->save();

            foreach($request->vin_ids as $vin_id){


                $guia_vin = new GuiaVins();
                $guia_vin->vin_id = $vin_id;
                $guia_vin->guia_id = $guia->guia_id;
                $guia_vin->save();

            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            flash('Error asignando guias.')->error();
            return redirect()->route('vin.index');
        }
        flash('Guias cargadas con éxito.')->success();
        return redirect()->route('vin.index');
        }


    public function storeModalCambiaEstado(Request $request)
    {


        //$fecha = Carbon::now();
        $fecha = date('Y-m-d');

        $user = User::find(Auth::id());

        try {

            DB::beginTransaction();

            $guardados=0;
            foreach($request->vin_ids as $vin_id){
                $vin = Vin::findOrfail($vin_id);

                $estado_previo = $vin->vin_estado_inventario_id;
                $estado_nuevo = $request->vin_estado_inventario_id;

                // Pasar el VIN de estado "Anunciado" a estado "Arribado"
                if($estado_previo == 1 && $estado_nuevo == 2){
                    $vin->vin_estado_inventario_id = $estado_nuevo;
                    $vin->save();
                    $guardados++;

                    // Guardar historial del cambio
                    DB::insert('INSERT INTO historico_vins
                        (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                        origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                        [
                            $vin->vin_id,
                            $estado_nuevo,
                            $fecha,
                            $user->user_id,
                            null,
                            null,
                            $user->belongsToEmpresa->empresa_id,
                            "VIN Arribado.",
                            "Origen Externo: Puerto.",
                            "Patio: BLoque y Ubicación por asignar."
                        ]
                    );
                } else if($estado_nuevo == 7 || $estado_nuevo == 8) {    // Pasar el VIN desde cualquier estado a "Suprimido" o "Entregado"
                    $bloque = null;

                    if($estado_previo == 4 || $estado_previo == 5 || $estado_previo == 6){ //VIN previamente en patio
                        $ubic_patio = UbicPatio::where('vin_id', $vin->vin_id)->first();

                        if(isset($ubic_patio->ubic_patio_id)) // Liberar ubicación ocupada
                        {
                            $ubic_patio->vin_id = null;
                            $ubic_patio->ubic_patio_ocupada = false;
                            $ubic_patio->save();
                            $bloque = $ubic_patio->bloque_id;
                        }
                    }

                    $vin->vin_estado_inventario_id = $estado_nuevo;
                    $vin->save();
                    $guardados++;

                    if($estado_nuevo == 8){ // Estado nuevo VIN Entregado
                        // Guardar historial del cambio
                        if($bloque != null){
                            DB::insert('INSERT INTO historico_vins
                                (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                                origen_id, destino_id, empresa_id, historico_vin_descripcion, destino_texto)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
                                [
                                    $vin->vin_id,
                                    $estado_nuevo,
                                    $fecha,
                                    $user->user_id,
                                    $bloque,
                                    null,
                                    $user->belongsToEmpresa->empresa_id,
                                    "VIN Entregado.",
                                    "Destino externo. VIN entregado."
                                ]
                            );
                        } else {
                            DB::insert('INSERT INTO historico_vins
                                (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                                origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                                [
                                    $vin->vin_id,
                                    $estado_nuevo,
                                    $fecha,
                                    $user->user_id,
                                    null,
                                    null,
                                    $user->belongsToEmpresa->empresa_id,
                                    "VIN Entregado.",
                                    "Sin ubicación en bloque para la entrega.",
                                    "Destino externo. VIN entregado."
                                ]
                            );
                        }
                    } else{ // Estado nuevo VIN Suprimido
                        if($bloque != null){
                            DB::insert('INSERT INTO historico_vins
                                (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                                origen_id, destino_id, empresa_id, historico_vin_descripcion, destino_texto)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
                                [
                                    $vin->vin_id,
                                    $estado_nuevo,
                                    $fecha,
                                    $user->user_id,
                                    $bloque,
                                    null,
                                    $user->belongsToEmpresa->empresa_id,
                                    "VIN Suprimido.",
                                    "Destino externo. VIN entregado."
                                ]
                            );
                        } else {
                            DB::insert('INSERT INTO historico_vins
                                (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                                origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                                [
                                    $vin->vin_id,
                                    $estado_nuevo,
                                    $fecha,
                                    $user->user_id,
                                    $bloque,
                                    null,
                                    $user->belongsToEmpresa->empresa_id,
                                    "VIN Suprimido.",
                                    "Sin ubicación previa en bloque.",
                                    "Destino externo. VIN entregado."
                                ]
                            );
                        }
                    }
                } else if($estado_previo == 8 && $estado_nuevo == 1){
                    $vin->vin_estado_inventario_id = $estado_nuevo;
                    $vin->save();
                    $guardados++;

                    DB::insert('INSERT INTO historico_vins
                        (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                        origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                        [
                            $vin->vin_id,
                            $estado_nuevo,
                            $fecha,
                            $user->user_id,
                            null,
                            null,
                            $user->belongsToEmpresa->empresa_id,
                            "VIN nuevamente Anunciado luego de haber sido entregado.",
                            "Sin ubicación previa en bloque. Reingreso de VIN.",
                            "Patio: BLoque y Ubicación por asignar."
                        ]
                    );
                }
            }

            DB::commit();


            if($request->ajax())
                if($guardados>0)
                    return response()->json(
                        Array("error"=>0,"mensaje"=>"Guardado con Èxito")
                    );
               else
                   return response()->json(
                       Array("error"=>1,"mensaje"=>"Guardado Incompleto")
                   );
            else{
                flash('Estados cambiados con éxito.')->success();
                return redirect()->route('vin.index');
            }

        } catch (\Throwable $th) {
            DB::rollBack();
            if($request->ajax())
                return response()->json(
                    Array("error"=>1,"mensaje"=>"Error al cambiar estado")
                );
            else{
                flash('Error al cambiar estados.')->error();
                return redirect()->route('vin.index');
            }
        }


    }



    public function exportResultadoBusquedaVins(Request $request)
    {
        $array_vins = [];
        foreach($request->vin_ids as $vin_id){
            $vin = Vin::find($vin_id);

            $vinExport = new Vin();
            $vinExport->vin_id = $vin->vin_id;
            $vinExport->vin_codigo = $vin->vin_codigo;
            $vinExport->vin_patente = $vin->vin_patente;
            $vinExport->vin_marca = $vin->oneMarca->marca_nombre;
            $vinExport->vin_modelo = $vin->vin_modelo;
            $vinExport->vin_color = $vin->vin_color;
            $vinExport->vin_motor = $vin->vin_motor;
            $vinExport->vin_segmento = $vin->vin_segmento;
            $vinExport->vin_fec_ingreso = $vin->vin_fec_ingreso;
            $vinExport->user_id = $vin->oneUser->belongsToEmpresa->empresa_razon_social;
            $vinExport->vin_estado_inventario_id = $vin->oneVinEstadoInventario();
            $vinExport->vin_fecha_entrega = $vin->vin_fecha_entrega;
            $vinExport->vin_fecha_agendado = $vin->vin_fecha_agendado;

            if($vin->vin_estado_inventario_id == 4 || $vin->vin_estado_inventario_id == 5 || $vin->vin_estado_inventario_id == 6){
                $vinUbic = DB::table('ubic_patios')
                    ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                    ->join('patios','bloques.patio_id','=','patios.patio_id')
                    ->where('ubic_patios.vin_id', $vin_id)
                    ->get();

                $vinExport->patio_nombre = $vinUbic[0]->patio_nombre;
                $vinExport->bloque_nombre = $vinUbic[0]->bloque_nombre;
                $vinExport->ubic_patio_fila = $vinUbic[0]->ubic_patio_fila;
                $vinExport->ubic_patio_columna = $vinUbic[0]->ubic_patio_columna;

            } else {
                $vinExport->patio_nombre = null;
                $vinExport->bloque_nombre = null;
                $vinExport->ubic_patio_fila = null;
                $vinExport->ubic_patio_columna = null;
            }

            array_push($array_vins, $vinExport);
        }

        // dd($array_vins);

        return Excel::download(new BusquedaVinsExport($array_vins), 'export_busqueda_vins.xlsx');
    }



    public function desagendado($id)
    {

        $vin_id =  Crypt::decrypt($id);
        $vin = Vin::findOrfail($vin_id);

        try{
            $vin->vin_predespacho = false;
            $vin->vin_fecha_agendado  = null;
            $vin->save();

           DB::commit();

        }  catch (\Throwable $th) {
            DB::rollBack();
            flash('Error desagendado el VIN.')->error();
            return redirect()->route('vin.index');
        }
        flash('VIN desagendado correctamente.')->success();
        return redirect()->route('vin.index');

    }


    public function predespacho(Request $request)
    {
        //$fecha = Carbon::now();
        $fecha = date('Y-m-d');

        $user = User::find(Auth::id());

        try {

            DB::beginTransaction();

            $guardados=0;
            foreach($request->vin_ids as $vin_id){
                $vin = Vin::findOrfail($vin_id);

                $estado_estado_inventario = $vin->vin_estado_inventario_id;


                // Colocar el check para predespacho del VIN
                if($request->predespacho == 1 ){
                    $vin->vin_predespacho = true;
                    $vin->vin_fecha_agendado  = $request->vin_fecha_despacho;
                    $vin->save();
                    $guardados++;

                    // Guardar historial del cambio
                    DB::insert('INSERT INTO historico_vins
                        (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                        origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                        [
                            $vin->vin_id,
                            $estado_estado_inventario,
                            $fecha,
                            $user->user_id,
                            null,
                            null,
                            $user->belongsToEmpresa->empresa_id,
                            "Cambio de cliente del VIN. ",
                            "Cambio de cliente del VIN. ",
                            "Cambio de cliente del VIN. "
                        ]
                    );
                }

            }
            DB::commit();


            if($request->ajax())
                if($guardados>0)
                    return response()->json(
                        Array("error"=>0,"mensaje"=>"Guardado con Èxito")
                    );
               else
                   return response()->json(
                       Array("error"=>1,"mensaje"=>"Guardado Incompleto")
                   );
            else{
                flash('Estados cambiados con éxito.')->success();
                return redirect()->route('vin.index');
            }
        }
        catch (\Throwable $th) {
            DB::rollBack();
            if($request->ajax())
                return response()->json(
                    Array("error"=>1,"mensaje"=>"Error al cambiar estado")
                );
            else{
                flash('Error al cambiar estados.')->error();
                return redirect()->route('vin.index');
            }
        }
    }

    public function  entregaExportResultadoBusquedaVins(Request $request)
    {

        $vin_request = json_decode($request->resultado_busqueda);



        $array_vins = [];
        foreach($vin_request as $vin_id){

            $vin = Vin::find($vin_id->vin_id);

            $vin = DB::table('vins')
            ->join('users','vins.user_id','=','users.user_id')
            ->join('empresas','empresas.empresa_id','=','users.empresa_id')
            ->join('entregas','entregas.vin_id','=','vins.vin_id')
            ->select('vins.vin_codigo','vins.vin_patente','vins.vin_color','vin_fec_ingreso', 'vins.vin_fecha_agendado', 'entregas.entrega_fecha','empresas.empresa_razon_social')
            ->whereNotNull('entregas.entrega_fecha')
            ->first();

            if($vin){
                array_push($array_vins, $vin);
            }


        }


        return Excel::download(new VinEntregadosExport($array_vins), 'historico_entregados.xlsx');
    }


    public function traspasovin()
    {
        $empresas = Empresa::select('empresa_id', 'empresa_razon_social')
            ->orderBy('empresa_id')
            ->where('deleted_at', null)
            ->pluck('empresa_razon_social', 'empresa_id')
            ->all();

        return view('vincambiocliente.index', compact('empresas'));

    }

    public function cambio(Request $request)
    {

        $fecha = date('Y-m-d');

        $user = User::find(Auth::id());

        $emp_id = (int)$request->cliente_nuevo;

        $nuevo_cliente = DB::table('users')
            ->where('empresa_id',$emp_id)
            ->first();

        try {
            DB::beginTransaction();

            $guardados=0;
            foreach($request->vin_ids as $vin_id){
                $vin = Vin::findOrfail($vin_id);
                $estado_estado_inventario = $vin->vin_estado_inventario_id;

                // Colocar el check para predespacho del VIN

                if($nuevo_cliente){
                   // var_dump($nuevo_cliente); exit;
                    $vin->user_id  = $nuevo_cliente->user_id;
                    $vin->save();
                    $guardados++;

                    // Guardar historial del cambio
                    DB::insert('INSERT INTO historico_vins
                        (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                        origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                        [
                            $vin->vin_id,
                            $estado_estado_inventario,
                            $fecha,
                            $user->user_id,
                            null,
                            null,
                            $user->belongsToEmpresa->empresa_id,
                            "Cambio de cliente del VIN. ",
                            "Cambio de cliente del VIN. ",
                            "Cambio de cliente del VIN. "
                        ]
                    );
                }

            }
            DB::commit();


            if($request->ajax())
                if($guardados>0)
                    return response()->json(
                        Array("error"=>0,"mensaje"=>"Cambio de cliente realizado con Èxito")
                    );
               else
                   return response()->json(
                       Array("error"=>1,"mensaje"=>"Guardado Incompleto")
                   );
            else{
                flash('Cambio de cliente realizado con éxito.')->success();
                return redirect()->route('vincambiodecliente.index');
            }
        }
        catch (\Throwable $th) {
            DB::rollBack();

            if($request->ajax())
                return response()->json(
                    Array("error"=>1,"mensaje"=>"Error al cambiar el cliente")
                );
            else{
                flash('Error al cambiar el cliente.')->error();
                return redirect()->route('vincambiodecliente.index');
            }
        }


    }




}


