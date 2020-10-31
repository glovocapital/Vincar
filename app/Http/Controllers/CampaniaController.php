<?php

namespace App\Http\Controllers;

use App\Bloque;
use App\Campania;
use App\Empresa;
use App\Http\Middleware\CheckSession;
use App\Http\Middleware\PreventBackHistory;
use App\Patio;
use App\Tarea;
use App\Entrega;
use App\TipoCampania;
use App\UbicPatio;
use App\User;
use App\Vin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use App\Exports\TareasVinsExport;
use App\Marca;
use Maatwebsite\Excel\Facades\Excel;

class CampaniaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
      //  $this->middleware(PreventBackHistory::class);
        $this->middleware(CheckSession::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $campanias = Campania::all()
            ->sortBy('campania_id');

        $tipo_campanias = TipoCampania::all()
            ->sortBy('tipo_campania_id');

        $arrayTCampanias = [];

        foreach ($campanias as $campania) {
            $tCampanias = DB::table('campania_vins')
                ->join('tipo_campanias', 'campania_vins.tipo_campania_id', '=', 'tipo_campanias.tipo_campania_id')
                ->select('campania_vins.campania_id', 'tipo_campanias.tipo_campania_descripcion')
                ->where('campania_vins.campania_id', $campania->campania_id)
                ->where('campania_vins.deleted_at', null)
                ->where('tipo_campanias.deleted_at', null)
                ->get();

            array_push($arrayTCampanias, $tCampanias);
        }

        return view('campania.index', compact('campanias', 'tipo_campanias', 'arrayTCampanias'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index2(Request $request)
    {
        $user_empresa_id  = Auth::user()->belongsToEmpresa->empresa_id;

        $user_rol_id = Auth::user()->oneRol->rol_id;

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

        /** Listado de Campañas para la vista de planificación */

        // Campañas para los roles SúperAdministrador (1), Administrador (2) y Operador (3)
        if($user_rol_id == 1 || $user_rol_id == 2 || $user_rol_id == 3){
            $campanias = Campania::all()
                ->where('deleted_at', null)
                ->sortBy('campania_id');
        } elseif($user_rol_id == 4){  // Campañas para ser vistas por el perfil Customer (4)
            $campanias = Campania::where('user_id', Auth::user()->user_id)
                ->where('deleted_at', null)
                ->orderBy('campania_id')
                ->get();
        } else {  // Para los demás perfiles devuelve conjunto vacío.
            $campanias = [];
        }

        $tipo_campanias = TipoCampania::all()
            ->sortBy('tipo_campania_id');

        $arrayTCampanias = [];

        foreach ($campanias as $campania) {
            $tCampanias = DB::table('campania_vins')
                ->join('tipo_campanias', 'campania_vins.tipo_campania_id', '=', 'tipo_campanias.tipo_campania_id')
                ->select('campania_vins.campania_id', 'tipo_campanias.tipo_campania_descripcion')
                ->where('campania_vins.campania_id', $campania->campania_id)
                ->where('campania_vins.deleted_at', null)
                ->where('tipo_campanias.deleted_at', null)
                ->get();

            array_push($arrayTCampanias, $tCampanias);
        }

        /** Búsqueda de los Vins */

        if(!($request->has('vin_numero') || $request->has('estadoinventario_id') || $request->has('patio_id') || $request->has('marca_id'))){
            /** Búsqueda de vins para la cabecera de la vista de solicitud de campañas */
            $tabla_vins = Vin::join('users','users.user_id','=','vins.user_id')
                ->join('empresas','empresas.empresa_id','=','users.empresa_id')
                ->join('ubic_patios', 'ubic_patios.vin_id', '=', 'vins.vin_id')
                ->join('bloques', 'bloques.bloque_id', '=', 'ubic_patios.bloque_id')
                ->join('patios', 'patios.patio_id', '=', 'bloques.patio_id')
                ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_motor',
                    'empresas.empresa_razon_social', 'vin_fec_ingreso',  'vin_fecha_entrega','patio_nombre', 'bloque_nombre', 'ubic_patio_fila',
                    'ubic_patio_columna')
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
                $marca_nombre = "Sin Marca";
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
                            ->where('vin_codigo',$v)
                            ->orWhere('vin_patente',$v)
                            ->where('empresas.empresa_id', $user_empresa_id);

                        if($marca_nombre != 'Sin Marca'){
                            $query->where('vin_marca', $marca->marca_id);
                            //$query->WhereRaw('upper(vin_marca) like(?)',strtoupper($marca_nombre));
                        }

                        if($estado_id > 0){
                            $query->where('vins.vin_estado_inventario_id', $estado_id);

                            if($estado_id == 4 || $estado_id == 5 || $estado_id == 6) {
                                $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                    ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                    ->join('patios','bloques.patio_id','=','patios.patio_id')
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
                                        ->where('patios.patio_id', $patio_id);
                                } else {
                                    $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                        ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                        ->join('patios','bloques.patio_id','=','patios.patio_id');
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
                                ->where('vins.user_id',$user_empresa_id);

                            if($marca_nombre != 'Sin Marca'){
                                $query->where('vin_marca', $marca->marca_id);
                                //$query->WhereRaw('upper(vin_marca) like(?)',strtoupper($marca_nombre));
                            }

                            if($estado_id > 0){
                                $query->where('vins.vin_estado_inventario_id', $estado_id);

                                if($estado_id == 4 || $estado_id == 5 || $estado_id == 6) {
                                    if($patio_id > 0){
                                        $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                            ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                            ->join('patios','bloques.patio_id','=','patios.patio_id')
                                            ->where('patios.patio_id', $patio_id);
                                    } else {
                                        $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                            ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                            ->join('patios','bloques.patio_id','=','patios.patio_id');
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
                    ->where('empresas.empresa_id', $user_empresa_id);

                if($marca_nombre != 'Sin Marca'){
                    $query->where('vin_marca', $marca->marca_id);
                    //$query->WhereRaw('upper(vin_marca) like(?)',strtoupper($marca_nombre));
                }

                if($estado_id > 0){
                    $query->where('vins.vin_estado_inventario_id', $estado_id);

                    if($estado_id == 4 || $estado_id == 5 || $estado_id == 6) {
                        if($patio_id > 0){
                            $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                ->join('patios','bloques.patio_id','=','patios.patio_id')
                                ->where('patios.patio_id', $patio_id);
                        } else {
                            $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                ->join('patios','bloques.patio_id','=','patios.patio_id');
                        }
                    }
                }

                $tabla_vins = $query->get();
            }
        }

        return view('campania.solicitudCampania', compact('tabla_vins', 'estadosInventario', 'subEstadosInventario', 'patios', 'marcas', 'tipo_campanias_array', 'campanias', 'tipo_campanias', 'arrayTCampanias'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index3(Request $request)
    {
        /** Tareas creadas para mostrarse */
        $tareas = Tarea::where('tarea_finalizada', false)
            ->where('deleted_at', '=', null)
            ->orderBy('tarea_id')
            ->get();

        $tareas_finalizadas = Tarea::where('tarea_finalizada', true)
            ->where('updated_at',  '>', Carbon::now()->subDays(1)->toDateTimeString())
            ->orderBy('tarea_id')
            ->get();

        $tareas_historicas = Tarea::where('tarea_finalizada', true)
            ->orderBy('tarea_id')
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
                            ->where('vin_codigo',$v)
                            ->orWhere('vin_patente', $v);

                        if($user_empresa_id > 0){
                            $query->where('empresas.empresa_id',$user_empresa_id);
                        }

                        if($marca_nombre != 'Sin marca'){
                            $query->where('vin_marca', $marca->marca_id);
                            //$query->WhereRaw('upper(vin_marca) like(?)',strtoupper($marca_nombre));
                        }

                        if($estado_id > 0){
                            $query->where('vins.vin_estado_inventario_id', $estado_id);

                            if($estado_id == 4 || $estado_id == 5 || $estado_id == 6) {
                                if($patio_id > 0){
                                    $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                        ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                        ->join('patios','bloques.patio_id','=','patios.patio_id')
                                        ->where('patios.patio_id', $patio_id);
                                } else {
                                    $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                        ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                        ->join('patios','bloques.patio_id','=','patios.patio_id');
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
                                        ->where('patios.patio_id', $patio_id);
                                } else {
                                    $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                        ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                        ->join('patios','bloques.patio_id','=','patios.patio_id');
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
                                ->join('empresas','users.empresa_id','=','empresas.empresa_id');

                            if($user_empresa_id > 0){
                                $query->where('empresas.empresa_id',$user_empresa_id);
                            }

                            if($marca_nombre != 'Sin marca'){
                                $query->where('vin_marca', $marca->marca_id);
                                //$query->WhereRaw('upper(vin_marca) like(?)',strtoupper($marca_nombre));
                            }

                            if($estado_id > 0){
                                $query->where('vins.vin_estado_inventario_id', $estado_id);

                                if($estado_id == 4 || $estado_id == 5 || $estado_id == 6) {
                                    if($patio_id > 0){
                                        $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                            ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                            ->join('patios','bloques.patio_id','=','patios.patio_id')
                                            ->where('patios.patio_id', $patio_id);
                                    } else {
                                        $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                            ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                            ->join('patios','bloques.patio_id','=','patios.patio_id');
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
                    ->join('empresas','users.empresa_id','=','empresas.empresa_id');

                if($user_empresa_id > 0){
                    $query->where('empresas.empresa_id',$user_empresa_id);
                }

                if($marca_nombre != 'Sin marca'){
                    $query->where('vin_marca', $marca->marca_id);
                    //$query->WhereRaw('upper(vin_marca) like(?)',strtoupper($marca_nombre));
                }

                if($estado_id > 0){
                    $query->where('vins.vin_estado_inventario_id', $estado_id);

                    if($estado_id == 4 || $estado_id == 5 || $estado_id == 6) {
                        if($patio_id > 0){
                            $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                ->join('patios','bloques.patio_id','=','patios.patio_id')
                                ->where('patios.patio_id', $patio_id);
                        } else {
                            $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                ->join('patios','bloques.patio_id','=','patios.patio_id');
                        }
                    }
                }

                $tabla_vins = $query->get();
            }
        }

        /** Valores necesarios para poblar los selects del modal de asignación de tarea */

        $responsables = User::where('rol_id', 5)
            ->orWhere('rol_id', 6)
            //   ->orWhere('rol_id', 6)
            ->get();

        $responsables_array= [];

        foreach($responsables as $k => $v){
            $responsables_array[$v->user_id] = $v->user_nombre. " " . $v->user_apellido;
        }

        $tipo_tareas_array = DB::table('tipo_tareas')
            ->select('tipo_tarea_id', 'tipo_tarea_descripcion')
            ->where('deleted_at', null)
            ->pluck('tipo_tarea_descripcion', 'tipo_tarea_id');

        $tipo_destinos_array = DB::table('tipo_destinos')
            ->select('tipo_destino_id', 'tipo_destino_descripcion')
            ->where('deleted_at', null)
            ->pluck('tipo_destino_descripcion', 'tipo_destino_id');

        /** Listado de Campañas para la vista de planificación */
        $campanias = Campania::all()
            ->sortBy('campania_id');

        $tipo_campanias = TipoCampania::all()
            ->sortBy('tipo_campania_id');

        $arrayTCampanias = [];

        foreach ($campanias as $campania) {
            $tCampanias = DB::table('campania_vins')
                ->join('tipo_campanias', 'campania_vins.tipo_campania_id', '=', 'tipo_campanias.tipo_campania_id')
                ->select('campania_vins.campania_id', 'tipo_campanias.tipo_campania_descripcion')
                ->where('campania_vins.campania_id', $campania->campania_id)
                ->where('campania_vins.deleted_at', null)
                ->where('tipo_campanias.deleted_at', null)
                ->get();

            array_push($arrayTCampanias, $tCampanias);
        }

        return view('planificacion.index', compact('tareas', 'tareas_finalizadas', 'tareas_historicas' ,'tabla_vins', 'users','empresas', 'estadosInventario', 'subEstadosInventario', 'patios', 'marcas', 'responsables_array', 'tipo_tareas_array', 'tipo_destinos_array', 'tipo_campanias_array', 'campanias', 'tipo_campanias', 'arrayTCampanias'));
    }


    /**
     * Replica de la funión index2 para el método POST
     */
    public function index4(Request $request)
    {
        $user_empresa_id  = Auth::user()->belongsToEmpresa->empresa_id;

        $user_rol_id = Auth::user()->oneRol->rol_id;

        $estadosInventario = DB::table('vin_estado_inventarios')
            ->select('vin_estado_inventario_id', 'vin_estado_inventario_desc')
            ->pluck('vin_estado_inventario_desc', 'vin_estado_inventario_id');

        $subEstadosInventario = DB::table('vin_sub_estado_inventarios')
            ->select('vin_sub_estado_inventario_id', 'vin_sub_estado_inventario_desc')
            ->pluck('vin_sub_estado_inventario_desc', 'vin_sub_estado_inventario_id');

        $marcas = DB::table('marcas')
            ->select('marca_id', 'marca_nombre')
            ->where('deleted_at', null)
            ->orderBy('marca_nombre')
            ->pluck('marca_nombre', 'marca_id');

        $tipo_campanias_array = TipoCampania::select('tipo_campania_id', 'tipo_campania_descripcion')
            ->where('deleted_at', null)
            ->pluck('tipo_campania_descripcion', 'tipo_campania_id');

        $patios = DB::table('patios')
            ->select('patio_id', 'patio_nombre')
            ->pluck('patio_nombre', 'patio_id');

        /** Listado de Campañas para la vista de planificación */

        // Campañas para los roles SúperAdministrador (1), Administrador (2) y Operador (3)
        if($user_rol_id == 1 || $user_rol_id == 2 || $user_rol_id == 3){
            $campanias = Campania::all()
                ->where('deleted_at', null)
                ->sortBy('campania_id');
        } elseif($user_rol_id == 4){  // Campañas para ser vistas por el perfil Customer (4)
            $campanias = Campania::where('user_id', Auth::user()->user_id)
                ->where('deleted_at', null)
                ->orderBy('campania_id')
                ->get();
        } else {  // Para los demás perfiles devuelve conjunto vacío.
            $campanias = [];
        }

        $tipo_campanias = TipoCampania::all()
            ->sortBy('tipo_campania_id');

        $arrayTCampanias = [];

        foreach ($campanias as $campania) {
            $tCampanias = DB::table('campania_vins')
                ->join('tipo_campanias', 'campania_vins.tipo_campania_id', '=', 'tipo_campanias.tipo_campania_id')
                ->select('campania_vins.campania_id', 'tipo_campanias.tipo_campania_descripcion')
                ->where('campania_vins.campania_id', $campania->campania_id)
                ->where('campania_vins.deleted_at', null)
                ->where('tipo_campanias.deleted_at', null)
                ->get();

            array_push($arrayTCampanias, $tCampanias);
        }

        /** Búsqueda de los Vins */

        if(!($request->has('vin_numero') || $request->has('estadoinventario_id') || $request->has('patio_id') || $request->has('marca_id'))){
            /** Búsqueda de vins para la cabecera de la vista de solicitud de campañas */
            $tabla_vins = Vin::join('users','users.user_id','=','vins.user_id')
                ->join('empresas','empresas.empresa_id','=','users.empresa_id')
                ->join('marcas','vins.vin_marca','=','marcas.marca_id')
                ->join('ubic_patios', 'ubic_patios.vin_id', '=', 'vins.vin_id')
                ->join('bloques', 'bloques.bloque_id', '=', 'ubic_patios.bloque_id')
                ->join('patios', 'patios.patio_id', '=', 'bloques.patio_id')
                ->select('vins.vin_id','vin_codigo', 'vin_patente', 'marca_nombre', 'vin_modelo', 'vin_color', 'vin_motor',
                    'empresas.empresa_razon_social', 'vin_fec_ingreso',  'vin_fecha_entrega','patio_nombre', 'bloque_nombre', 'ubic_patio_fila',
                    'ubic_patio_columna')
                ->orderByRaw('ubic_patio_fila, ubic_patio_columna ASC')
                ->where('users.empresa_id', $user_empresa_id )
                ->get();
        } else {
            // dd($user_empresa_id);

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

                foreach($arreglo_vins as $v){
                    $validate = DB::table('vins')
                        ->join('users','users.user_id','=','vins.user_id')
                        ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                        ->where(function ($q) use ($v) {
                            $q->where('vin_codigo', $v)
                                ->orWhere('vin_patente', $v);
                        })
                        ->where('empresas.empresa_id', $user_empresa_id)
                        ->exists();
                    
                    if($validate == true){
                        $query = DB::table('vins')
                            ->join('users','users.user_id','=','vins.user_id')
                            ->join('vin_estado_inventarios','vins.vin_estado_inventario_id','=','vin_estado_inventarios.vin_estado_inventario_id')
                            ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                            ->join('marcas','vins.vin_marca','=','marcas.marca_id')
                            ->where(function ($q) use ($v) {
                                $q->where('vin_codigo', $v)
                                    ->orWhere('vin_patente', $v);
                            })
                            ->where('empresas.empresa_id', $user_empresa_id);

                        if($marca_nombre != 'Sin marca'){                    
                            $query->where('vin_marca', $marca->marca_id);
                            //$query->WhereRaw('upper(vin_marca) like(?)',strtoupper($marca_nombre));
                        }

                        if($estado_id > 0){
                            $query->where('vins.vin_estado_inventario_id', $estado_id);

                            if($estado_id == 4 || $estado_id == 5 || $estado_id == 6) {
                                $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                    ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                    ->join('patios','bloques.patio_id','=','patios.patio_id')
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
                                        ->where('patios.patio_id', $patio_id);
                                } else {
                                    $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                        ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                        ->join('patios','bloques.patio_id','=','patios.patio_id');
                                }
                            }
                        }

                        array_push($tabla_vins, $query->first());
                    } else {
                        if(count($arreglo_vins) >= 1){
                            $message[$v] = "Vin o patente: " . $v . " no se encuentra en la lista, o no pertenece a la empresa.";
                        } else {
                            $query = DB::table('vins')
                                ->join('users','users.user_id','=','vins.user_id')
                                ->join('vin_estado_inventarios','vins.vin_estado_inventario_id','=','vin_estado_inventarios.vin_estado_inventario_id')
                                ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                                ->join('marcas','vins.vin_marca','=','marcas.marca_id')
                                ->where('vins.user_id',$user_empresa_id);

                            if($marca_nombre != 'Sin marca'){
                                $query->where('vin_marca', $marca->marca_id);
                                //$query->WhereRaw('upper(vin_marca) like(?)',strtoupper($marca_nombre));
                            }

                            if($estado_id > 0){
                                $query->where('vins.vin_estado_inventario_id', $estado_id);

                                if($estado_id == 4 || $estado_id == 5 || $estado_id == 6) {
                                    if($patio_id > 0){
                                        $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                            ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                            ->join('patios','bloques.patio_id','=','patios.patio_id')
                                            ->where('patios.patio_id', $patio_id);
                                    } else {
                                        $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                            ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                            ->join('patios','bloques.patio_id','=','patios.patio_id');
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
                    ->join('marcas','vins.vin_marca','=','marcas.marca_id')
                    ->where('empresas.empresa_id', $user_empresa_id);

                if($marca_nombre != 'Sin marca'){
                    $query->where('vin_marca', $marca->marca_id);
                    //$query->WhereRaw('upper(vin_marca) like(?)',strtoupper($marca_nombre));
                }

                if($estado_id > 0){
                    $query->where('vins.vin_estado_inventario_id', $estado_id);

                    if($estado_id == 4 || $estado_id == 5 || $estado_id == 6) {
                        if($patio_id > 0){
                            $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                ->join('patios','bloques.patio_id','=','patios.patio_id')
                                ->where('patios.patio_id', $patio_id);
                        } else {
                            $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                ->join('patios','bloques.patio_id','=','patios.patio_id');
                        }
                    }
                }

                $tabla_vins = $query->get();
            }
        }

        return view('campania.solicitudCampania', compact('tabla_vins', 'estadosInventario', 'subEstadosInventario', 'patios', 'marcas', 'tipo_campanias_array', 'campanias', 'tipo_campanias', 'arrayTCampanias'));
    }


    /**
     * Replica de la funión index3 para el método POST
     */
    public function index5(Request $request)
    {
        /** Tareas creadas para mostrarse */
        $tareas = Tarea::where('tarea_finalizada', false)
            ->where('deleted_at', '=', null)
            ->orderBy('tarea_id')
            ->get();

        $tareas_finalizadas = Tarea::where('tarea_finalizada', true)
            ->where('updated_at',  '>', Carbon::now()->subDays(1)->toDateTimeString())
            ->orderBy('tarea_id')
            ->get();

        $tareas_historicas = Tarea::where('tarea_finalizada', true)
            ->orderBy('tarea_id')
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
                            ->where('vin_codigo',$v)
                            ->orWhere('vin_patente', $v);

                        if($user_empresa_id > 0){
                            $query->where('empresas.empresa_id',$user_empresa_id);
                        }

                        if($marca_nombre != 'Sin marca'){
                            $query->where('vin_marca', $marca->marca_id);
                            //$query->WhereRaw('upper(vin_marca) like(?)',strtoupper($marca_nombre));
                        }

                        if($estado_id > 0){
                            $query->where('vins.vin_estado_inventario_id', $estado_id);

                            if($estado_id == 4 || $estado_id == 5 || $estado_id == 6) {
                                if($patio_id > 0){
                                    $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                        ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                        ->join('patios','bloques.patio_id','=','patios.patio_id')
                                        ->where('patios.patio_id', $patio_id);
                                } else {
                                    $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                        ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                        ->join('patios','bloques.patio_id','=','patios.patio_id');
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
                                        ->where('patios.patio_id', $patio_id);
                                } else {
                                    $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                        ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                        ->join('patios','bloques.patio_id','=','patios.patio_id');
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
                                ->join('empresas','users.empresa_id','=','empresas.empresa_id');

                            if($user_empresa_id > 0){
                                $query->where('empresas.empresa_id',$user_empresa_id);
                            }

                            if($marca_nombre != 'Sin marca'){
                                $query->where('vin_marca', $marca->marca_id);
                                //$query->WhereRaw('upper(vin_marca) like(?)',strtoupper($marca_nombre));
                            }

                            if($estado_id > 0){
                                $query->where('vins.vin_estado_inventario_id', $estado_id);

                                if($estado_id == 4 || $estado_id == 5 || $estado_id == 6) {
                                    if($patio_id > 0){
                                        $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                            ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                            ->join('patios','bloques.patio_id','=','patios.patio_id')
                                            ->where('patios.patio_id', $patio_id);
                                    } else {
                                        $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                            ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                            ->join('patios','bloques.patio_id','=','patios.patio_id');
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
                    ->join('empresas','users.empresa_id','=','empresas.empresa_id');

                if($user_empresa_id > 0){
                    $query->where('empresas.empresa_id',$user_empresa_id);
                }

                if($marca_nombre != 'Sin marca'){
                    $query->where('vin_marca', $marca->marca_id);
                    //$query->WhereRaw('upper(vin_marca) like(?)',strtoupper($marca_nombre));
                }

                if($estado_id > 0){
                    $query->where('vins.vin_estado_inventario_id', $estado_id);

                    if($estado_id == 4 || $estado_id == 5 || $estado_id == 6) {
                        if($patio_id > 0){
                            $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                ->join('patios','bloques.patio_id','=','patios.patio_id')
                                ->where('patios.patio_id', $patio_id);
                        } else {
                            $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                ->join('patios','bloques.patio_id','=','patios.patio_id');
                        }
                    }
                }

                $tabla_vins = $query->get();
            }
        }

        /** Valores necesarios para poblar los selects del modal de asignación de tarea */

        $responsables = User::where('rol_id', 5)
            ->orWhere('rol_id', 6)
            //   ->orWhere('rol_id', 6)
            ->get();

        $responsables_array= [];

        foreach($responsables as $k => $v){
            $responsables_array[$v->user_id] = $v->user_nombre. " " . $v->user_apellido;
        }

        $tipo_tareas_array = DB::table('tipo_tareas')
            ->select('tipo_tarea_id', 'tipo_tarea_descripcion')
            ->where('deleted_at', null)
            ->pluck('tipo_tarea_descripcion', 'tipo_tarea_id');

        $tipo_destinos_array = DB::table('tipo_destinos')
            ->select('tipo_destino_id', 'tipo_destino_descripcion')
            ->where('deleted_at', null)
            ->pluck('tipo_destino_descripcion', 'tipo_destino_id');

        /** Listado de Campañas para la vista de planificación */
        $campanias = Campania::all()
            ->sortBy('campania_id');

        $tipo_campanias = TipoCampania::all()
            ->sortBy('tipo_campania_id');

        $arrayTCampanias = [];

        foreach ($campanias as $campania) {
            $tCampanias = DB::table('campania_vins')
                ->join('tipo_campanias', 'campania_vins.tipo_campania_id', '=', 'tipo_campanias.tipo_campania_id')
                ->select('campania_vins.campania_id', 'tipo_campanias.tipo_campania_descripcion')
                ->where('campania_vins.campania_id', $campania->campania_id)
                ->where('campania_vins.deleted_at', null)
                ->where('tipo_campanias.deleted_at', null)
                ->get();

            array_push($arrayTCampanias, $tCampanias);
        }

        return view('planificacion.index', compact('tareas', 'tareas_finalizadas', 'tareas_historicas', 'tabla_vins', 'users','empresas', 'estadosInventario', 'subEstadosInventario', 'patios', 'marcas', 'responsables_array', 'tipo_tareas_array', 'tipo_destinos_array', 'tipo_campanias_array', 'campanias', 'tipo_campanias', 'arrayTCampanias'));
    }

    public function index5_json(Request $request)
    {


        /** Búsqueda de vins para la cabecera de la vista de planificación */
        $vins = Vin::all();

        $tabla_vins = [];

        /** A partir de aqui las consultas del cuadro de busqueda */
        if(empty($request->empresa_id) && empty($request->vin_numero) && empty($request->estadoinventario_id) && empty($request->patio_id) && empty($request->marca_id) && empty($request->vin_numero)){                
            $query = DB::table('vins')
                ->join('users','users.user_id','=','vins.user_id')
                ->join('vin_estado_inventarios','vins.vin_estado_inventario_id','=','vin_estado_inventarios.vin_estado_inventario_id')
                ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                ->join('marcas','vins.vin_marca','=','marcas.marca_id')
                ->leftjoin('guia_vins','guia_vins.vin_id','=','vins.vin_id')
                ->leftjoin('guias','guia_vins.guia_id','guias.guia_id')
                ->leftJoin('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                ->leftJoin('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                ->leftJoin('patios','bloques.patio_id','=','patios.patio_id')
                ->select('vins.vin_id','vin_codigo', 'vin_patente', 'marca_nombre', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                    'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_fecha_entrega','vins.vin_estado_inventario_id', 'vin_estado_inventario_desc',
                    'patio_nombre', 'bloque_nombre', 'ubic_patio_id', 'ubic_patio_fila','ubic_patio_columna','guias.guia_ruta');

            if(Auth::user()->rol_id == 4) {
                $user_empresa_id = Auth::user()->belongsToEmpresa->empresa_id;
                $query->where('empresas.empresa_id',$user_empresa_id);
            }

            $tabla_vins = $query->get();
        } elseif($request->has('empresa_id') || $request->has('vin_numero') || $request->has('estadoinventario_id') || $request->has('patio_id') || $request->has('marca_id')){
            // Segundo caso: Se seleccionó algún criterio de filtro para la búsqueda.
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

            if(Auth::user()->rol_id == 4) {
                $user_empresa_id = Auth::user()->belongsToEmpresa->empresa_id;
            } else {
                if(!empty($user[0]->empresa_id))
                {
                    $user_empresa_id = $user[0]->empresa_id;
                }else{
                    $user_empresa_id = 0;
                }
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
                    $query = DB::table('vins')
                            ->join('users','users.user_id','=','vins.user_id')
                            ->join('vin_estado_inventarios','vins.vin_estado_inventario_id','=','vin_estado_inventarios.vin_estado_inventario_id')
                            ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                            ->join('marcas','vins.vin_marca','=','marcas.marca_id')
                            ->leftjoin('guia_vins','guia_vins.vin_id','=','vins.vin_id')
                            ->leftjoin('guias','guia_vins.guia_id','guias.guia_id')
                            ->leftJoin('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                            ->leftJoin('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                            ->leftJoin('patios','bloques.patio_id','=','patios.patio_id')
                            ->select('vins.vin_id','vin_codigo', 'vin_patente', 'marca_nombre', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                                'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_fecha_entrega','vins.vin_estado_inventario_id', 'vin_estado_inventario_desc',
                                'patio_nombre', 'bloque_nombre', 'ubic_patio_id', 'ubic_patio_fila','ubic_patio_columna','guias.guia_ruta');
                        
                    if(Auth::user()->rol_id == 4) {
                        $validate = DB::table('vins')
                            ->join('users','users.user_id','=','vins.user_id')
                            ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                            ->where('empresas.empresa_id', $user_empresa_id)
                            ->where(function ($query) use ($v) {
                                $query->where('vin_codigo', $v)
                                    ->orWhere('vin_patente', $v);
                            })
                            ->exists();
                    } else {
                        $validate = DB::table('vins')
                            ->where('vin_codigo', $v)
                            ->orWhere('vin_patente', $v)
                            ->exists();
                    }

                    if($validate == true){
                        $query->where('vin_codigo',$v)
                            ->orWhere('vin_patente', $v);

                        if($user_empresa_id > 0){
                            $query->where('empresas.empresa_id',$user_empresa_id);
                        }

                        if($marca_nombre != 'Sin marca'){
                            $query->where('vin_marca', $marca->marca_id);
                            //$query->WhereRaw('upper(vin_marca) like(?)',strtoupper($marca_nombre));
                        }

                        if($patio_id > 0){
                            $query->where('patios.patio_id', $patio_id);
                        }

                        if($estado_id > 0) {
                            $query->where('vins.vin_estado_inventario_id', $estado_id);
                        }

                        array_push($tabla_vins, $query->first());
                    } else {
                        if(count($arreglo_vins) >= 1){
                            if(Auth::user()->rol_id == 4){
                                $message[$v] = "Vin o patente: " . $v . " no se encuentra en la lista o no pertenece a la empresa " . Auth::user()->belongsToEmpresa->empresa_id . ".";
                            } else {
                                $message[$v] = "Vin o patente: " . $v . " no se encuentra en la lista";
                            }
                        } else {
                            if($user_empresa_id > 0){
                                $query->where('empresas.empresa_id',$user_empresa_id);
                            }

                            if($marca_nombre != 'Sin marca'){
                                $query->where('vin_marca', $marca->marca_id);
                                //$query->WhereRaw('upper(vin_marca) like(?)',strtoupper($marca_nombre));
                            }

                            if($patio_id > 0){
                                $query->where('patios.patio_id', $patio_id);
                            }

                            if($estado_id > 0) {
                                $query->where('vins.vin_estado_inventario_id', $estado_id);
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
                    ->join('marcas','vins.vin_marca','=','marcas.marca_id')
                    ->leftjoin('guia_vins','guia_vins.vin_id','=','vins.vin_id')
                    ->leftjoin('guias','guia_vins.guia_id','guias.guia_id')
                    ->leftJoin('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                    ->leftJoin('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                    ->leftJoin('patios','bloques.patio_id','=','patios.patio_id')
                    ->select('vins.vin_id','vin_codigo', 'vin_patente', 'marca_nombre', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
                        'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_fecha_entrega','vins.vin_estado_inventario_id', 'vin_estado_inventario_desc',
                        'patio_nombre', 'bloque_nombre', 'ubic_patio_id', 'ubic_patio_fila','ubic_patio_columna','guias.guia_ruta');
                    
                if($user_empresa_id > 0){
                    $query->where('empresas.empresa_id', $user_empresa_id);
                }
                
                if($marca_nombre != 'Sin marca'){
                    $query->where('vin_marca', $marca->marca_id);
                    //$query->WhereRaw('upper(vin_marca) like(?)',strtoupper($marca_nombre));
                } 

                if($patio_id > 0){
                    $query->where('patios.patio_id', $patio_id);
                }

                if($estado_id > 0) {
                    $query->where('vins.vin_estado_inventario_id', $estado_id);
                }

                $resultados = $query->get();

                foreach($resultados as $registro){
                    array_push($tabla_vins, $registro);
                }
            }
        }


        foreach($tabla_vins as $vins){
            
            if($vins->guia_ruta){
                $vins->vin_downloadGuiaN =  "Guia Cargada";
            }else{
                $vins->vin_downloadGuiaN =  "Sin Guia";
            }

            if(!$vins->marca_nombre){
                $vins->marca_nombre = 'Sin Marca';
            }
            
            $vins->vin_downloadGuia =  route('vin.downloadGuia', Crypt::encrypt($vins->vin_id));
            $vins->vin_encrypt =  Crypt::encrypt($vins->vin_id);
            $vins->vin_guia =  route('vin.guia', Crypt::encrypt($vins->vin_id));
            $vins->vin_editarestado =  route('vin.editarestado', Crypt::encrypt($vins->vin_id));
            $vins->vin_edit =  route('vin.edit', Crypt::encrypt($vins->vin_id));
            $vins->rol_id = auth()->user()->rol_id;

            if ($vins->vin_estado_inventario_id == 8){
                $vinFechaEntrega = Entrega::where('vin_id', $vins->vin_id)
                                    ->select('entrega_fecha')
                                    ->orderBy('entrega_fecha', 'desc')
                                    ->limit(1)
                                    ->value('entrega_fecha');

                $vins->vin_fecha_entrega = $vinFechaEntrega;
            } else {
                $vins->vin_fecha_entrega = "";
            }

        }

        return response()->json(
            $tabla_vins
        );
    }

    public function vinCodigos(Request $request){

        if ($request->ajax()){
            $vin_codigos = [];
            foreach($request->vin_ids as $vin_id){
                $query = VIN::where('vin_id', '=', $vin_id)
                    ->select('vin_codigo')
                    ->orderBy('vin_id')
                    ->value('vin_codigo');
                array_push($vin_codigos, $query);
            }

            return response()->json([
                'success' => true,
                'message' => "Data de usuarios por empresa disponible",
                'codigos' => $vin_codigos,
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeModal(Request $request)
    {
        try {
            DB::beginTransaction();

            $campania = new Campania();

            $campania->campania_fecha_finalizacion = $request->campania_fecha_finalizacion;
            $campania->campania_observaciones = $request->campania_observaciones;
            $campania->vin_id = $request->vin_id;
            $campania->user_id = Auth::user()->user_id;

            $campania->save();

            $desc_campanias = "";

            foreach ($request->tipo_campanias as $t_campania_id) {
                $tipo_campania_id = (int)$t_campania_id;
                DB::insert('INSERT INTO campania_vins (tipo_campania_id, campania_id) VALUES (?, ?)', [$tipo_campania_id, $campania->campania_id]);

                $tipo_campania = TipoCampania::find($tipo_campania_id);

                $desc_campanias .= " " . $tipo_campania->tipo_campania_descripcion;
            }

            // Guardar histórico de la asignación de la campaña
            $fecha = date('Y-m-d');
            $user = User::find(Auth::id());
            $vin = Vin::findOrfail($campania->vin_id);
            $ubicPatio = UbicPatio::where('vin_id', $vin->vin_id)->first();

            if($ubicPatio){
                $bloque_id = $ubicPatio->bloque_id;
            } else {
                $bloque_id = null;
            }

            if($bloque_id != null){
                $bloqueOrigen = Bloque::find($bloque_id);
                
                DB::insert('INSERT INTO historico_vins
                    (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                    origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                    [
                        $vin->vin_id,
                        $vin->vin_estado_inventario_id,
                        $fecha,
                        $user->user_id,
                        $bloque_id,
                        $bloque_id,
                        $user->empresa_id,
                        "Campañas asignadas:" . $desc_campanias,
                        "Patio: " . $bloqueOrigen->onePatio->patio_nombre . ". Bloque: $bloqueOrigen->bloque_nombre. Fila: $ubicPatio->ubic_patio_fila. Columna: $ubicPatio->ubic_patio_columna.",
                        "Patio: " . $bloqueOrigen->onePatio->patio_nombre . ". Bloque: $bloqueOrigen->bloque_nombre. Fila: $ubicPatio->ubic_patio_fila. Columna: $ubicPatio->ubic_patio_columna.",
                    ]
                );
            } else {
                DB::insert('INSERT INTO historico_vins
                    (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                    origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                    [
                        $vin->vin_id,
                        $vin->vin_estado_inventario_id,
                        $fecha,
                        $user->user_id,
                        $bloque_id,
                        $bloque_id,
                        $user->empresa_id,
                        "Campañas asignadas:" . $desc_campanias,
                        "VIN sin ubicación (fuera de bloque) al asignar campañas.",
                        "VIN preparado para ser asignado a nueva ubicación y estado."
                    ]
                );
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            flash('Error asignando campaña.')->error();
            return redirect()->route('campania.index');
        }

        flash('Campaña asignada con éxito.')->success();
        return redirect()->route('campania.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeModalTarea(Request $request)
    {
        try {

            DB::beginTransaction();

            $tarea = new Tarea();

            $tarea->tarea_fecha_finalizacion = $request->tarea_fecha_finalizacion;
            $tarea->tarea_prioridad = $request->tarea_prioridad;
            $tarea->tarea_hora_termino = $request->tarea_hora_termino;
            $tarea->vin_id = $request->vin_id;
            $tarea->user_id = $request->tarea_responsable_id;
            $tarea->tipo_tarea_id = $request->tipo_tarea_id;
            $tarea->tipo_destino_id = $request->tipo_destino_id;

            $tarea->save();

            // Guardar histórico de la asignación de la campaña
            $fecha = date('Y-m-d');
            $user = User::find(Auth::id());
            $vin = Vin::findOrfail($tarea->vin_id);
            $ubicPatio = UbicPatio::where('vin_id', $vin->vin_id)->first();

            if($ubicPatio){
                $bloque_id = $ubicPatio->bloque_id;
            } else {
                $bloque_id = null;
            }

            $tipo_tarea = DB::table("tipo_tareas")
                ->where('tipo_tarea_id', $tarea->tipo_tarea_id)
                ->first();

            $desc_tarea = $tipo_tarea->tipo_tarea_descripcion;

            if($bloque_id != null){
                $bloqueOrigen = Bloque::find($bloque_id);

                DB::insert('INSERT INTO historico_vins
                    (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                    origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                    [
                        $vin->vin_id,
                        $vin->vin_estado_inventario_id,
                        $fecha,
                        $user->user_id,
                        $bloque_id,
                        $bloque_id,
                        $user->empresa_id,
                        "Tarea asignada: " . $desc_tarea,
                        "Patio: " . $bloqueOrigen->onePatio->patio_nombre . ". Bloque: $bloqueOrigen->bloque_nombre. Fila: $ubicPatio->ubic_patio_fila. Columna: $ubicPatio->ubic_patio_columna.",
                        "Patio: " . $bloqueOrigen->onePatio->patio_nombre . ". Bloque: $bloqueOrigen->bloque_nombre. Fila: $ubicPatio->ubic_patio_fila. Columna: $ubicPatio->ubic_patio_columna.",
                    ]
                );
            } else {
                DB::insert('INSERT INTO historico_vins
                    (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                    origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                    [
                        $vin->vin_id,
                        $vin->vin_estado_inventario_id,
                        $fecha,
                        $user->user_id,
                        $bloque_id,
                        $bloque_id,
                        $user->empresa_id,
                        "Tarea Asignada:" . $desc_tarea,
                        "VIN sin ubicación (fuera de bloque) al asignar tarea.",
                        "VIN preparado para ser asignado a nueva ubicación y estado."
                    ]
                );
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            flash('Error asignando tarea.')->error();
            return redirect()->route('planificacion.index');
        }
        flash('Tarea asignada con éxito.')->success();
        return redirect()->route('planificacion.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeModalTareaLotes(Request $request)
    {
        //try {
            DB::beginTransaction();

            foreach($request->vin_ids as $vin_id){
                $tarea = new Tarea();
                $tarea->tarea_fecha_finalizacion = $request->tarea_fecha_finalizacion;
                $tarea->tarea_prioridad = (int)$request->tarea_prioridad;
                $tarea->tarea_hora_termino = $request->tarea_hora_termino;
                $tarea->vin_id = (int)$vin_id;
                $tarea->user_id = $request->tarea_responsable_id;
                $tarea->tipo_tarea_id = $request->tipo_tarea_id;
                $tarea->tipo_destino_id = $request->tipo_destino_id;
                $tarea->save();

                // REVISAR ESTE BLOQUE DE CÓDIGO
                // Guardar histórico de la asignación de la tarea
                $fecha = date('Y-m-d');
                $user = User::find(Auth::id());
                $vin = Vin::findOrfail($tarea->vin_id);
                $ubicPatio = UbicPatio::where('vin_id', $vin->vin_id)->first();

                if($ubicPatio){
                    $bloque_id = $ubicPatio->bloque_id;
                } else {
                    $bloque_id = null;
                }

                $tipo_tarea = DB::table("tipo_tareas")
                    ->where('tipo_tarea_id', $tarea->tipo_tarea_id)
                    ->first();

                $desc_tarea = $tipo_tarea->tipo_tarea_descripcion;

                if($bloque_id != null){
                    $bloqueOrigen = Bloque::find($bloque_id);

                    DB::insert('INSERT INTO historico_vins
                        (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                        origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                        [
                            $vin->vin_id,
                            $vin->vin_estado_inventario_id,
                            $fecha,
                            $user->user_id,
                            $bloque_id,
                            $bloque_id,
                            $user->empresa_id,
                            "Tarea asignada: " . $desc_tarea,
                            "Patio: " . $bloqueOrigen->onePatio->patio_nombre . ". Bloque: $bloqueOrigen->bloque_nombre. Fila: $ubicPatio->ubic_patio_fila. Columna: $ubicPatio->ubic_patio_columna.",
                            "Patio: " . $bloqueOrigen->onePatio->patio_nombre . ". Bloque: $bloqueOrigen->bloque_nombre. Fila: $ubicPatio->ubic_patio_fila. Columna: $ubicPatio->ubic_patio_columna.",
                        ]
                    );
                } else {
                    DB::insert('INSERT INTO historico_vins
                        (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                        origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                        [
                            $vin->vin_id,
                            $vin->vin_estado_inventario_id,
                            $fecha,
                            $user->user_id,
                            $bloque_id,
                            $bloque_id,
                            $user->empresa_id,
                            "Tarea asignada: " . $desc_tarea,
                            "VIN sin ubicación (fuera de bloque) al asignar tarea.",
                            "VIN preparado para ser asignado a nueva ubicación y estado."
                        ]
                    );
                }
            }

            DB::commit();




        /*} catch (\Throwable $th) {
            DB::rollBack();
            if($request->ajax())
                return response()->json(
                    Array("error"=>1,"mensaje"=>"Error asignando tarea")
                );
            else {
                flash('Error asignando tarea.')->error();
                return redirect()->route('planificacion.index');
            }


        }*/


        $tareas = Tarea::where('tarea_finalizada', false)
            ->where('deleted_at', '=', null)
            ->orderBy('tarea_id')
            ->get();




        foreach($tareas as $tarea){

            $tarea->vin_codigo = $tarea->codigoVin();
            $tarea->TipoTarea = $tarea->oneTipoTarea();
            $tarea->TipoDestino = $tarea->oneTipoDestino();
            $tarea->nombreResponsable = $tarea->nombreResponsable();
            $tarea->planificacion_destroy =  route('planificacion.destroy', Crypt::encrypt($tarea->tarea_id));
            $tarea->planificacion_edit =  route('planificacion.edit', Crypt::encrypt($tarea->tarea_id));

        }

        if($request->ajax())
            return response()->json(
                Array("error"=>0,"mensaje"=>"Guardado con Èxito","tareas"=>$tareas)
            );
        else {
            flash('Tarea asignada con éxito.')->success();
            return redirect()->route('planificacion.index');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeModalCampaniaLotes(Request $request)
    {
        try {
            DB::beginTransaction();

            foreach($request->vin_ids as $vin_id){

                $campania = new Campania();

                $campania->campania_fecha_finalizacion = $request->campania_fecha_finalizacion;
                $campania->campania_observaciones = $request->campania_observaciones;
                $campania->vin_id = (int)$vin_id;
                $campania->user_id = Auth::user()->user_id;

                $campania->save();

                $desc_campanias = "";

                foreach ($request->tipo_campanias as $t_campania_id) {
                    $tipo_campania_id = (int)$t_campania_id;
                    DB::insert('INSERT INTO campania_vins (tipo_campania_id, campania_id) VALUES (?, ?)', [$tipo_campania_id, $campania->campania_id]);

                    $tipo_campania = TipoCampania::find($tipo_campania_id);

                    $desc_campanias .= " " . $tipo_campania->tipo_campania_descripcion;
                }

                // Guardar histórico de la asignación de la campaña
                $fecha = date('Y-m-d');
                $user = User::find(Auth::id());
                $vin = Vin::findOrfail($campania->vin_id);
                $ubicPatio = UbicPatio::where('vin_id', $vin->vin_id)->first();

                if($ubicPatio){
                    $bloque_id = $ubicPatio->bloque_id;
                } else {
                    $bloque_id = null;
                }

                if($bloque_id != null){
                    $bloqueOrigen = Bloque::find($bloque_id);

                    DB::insert('INSERT INTO historico_vins
                        (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                        origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                        [
                            $vin->vin_id,
                            $vin->vin_estado_inventario_id,
                            $fecha,
                            $user->user_id,
                            $bloque_id,
                            $bloque_id,
                            $user->empresa_id,
                            "Campañas asignadas:" . $desc_campanias,
                            "Patio: " . $bloqueOrigen->onePatio->patio_nombre . ". Bloque: $bloqueOrigen->bloque_nombre. Fila: $ubicPatio->ubic_patio_fila. Columna: $ubicPatio->ubic_patio_columna.",
                            "Patio: " . $bloqueOrigen->onePatio->patio_nombre . ". Bloque: $bloqueOrigen->bloque_nombre. Fila: $ubicPatio->ubic_patio_fila. Columna: $ubicPatio->ubic_patio_columna.",
                        ]
                    );
                } else {
                    DB::insert('INSERT INTO historico_vins
                        (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                        origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                        [
                            $vin->vin_id,
                            $vin->vin_estado_inventario_id,
                            $fecha,
                            $user->user_id,
                            $bloque_id,
                            $bloque_id,
                            $user->empresa_id,
                            "Campañas asignadas:" . $desc_campanias,
                            "VIN sin ubicación (fuera de bloque) al asignar campañas.",
                            "VIN preparado para ser asignado a nueva ubicación y estado."
                        ]
                    );
                }
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            flash('Error asignando campaña.')->error();
            return redirect()->route('campania.index');
        }
        flash('Campaña asignada con éxito.')->success();
        return redirect()->route('campania.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Campania  $campania
     * @return \Illuminate\Http\Response
     */
    public function show(Campania $campania)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Campania  $campania
     * @return \Illuminate\Http\Response
     */
    public function edit($id_campania)
    {
        $campania_id =  Crypt::decrypt($id_campania);
        $campania = Campania::findOrfail($campania_id);

        $vin_codigo = $campania->oneVin->vin_codigo;

        $tipo_campanias_array = TipoCampania::all()
            ->sortBy('tipo_campania_id')
            ->where('deleted_at', null)
            ->pluck('tipo_campania_descripcion', 'tipo_campania_id');

        $arrayTCampanias = [];

        $tCampanias = DB::table('campania_vins')
            ->join('tipo_campanias', 'campania_vins.tipo_campania_id', '=', 'tipo_campanias.tipo_campania_id')
            ->select('campania_vins.campania_id', 'tipo_campanias.tipo_campania_id', 'tipo_campanias.tipo_campania_descripcion')
            ->where('campania_vins.campania_id', $campania->campania_id)
            ->where('campania_vins.deleted_at', null)
            ->where('tipo_campanias.deleted_at', null)
            ->get();

        return view('campania.edit', compact('campania', 'vin_codigo','tipo_campanias_array', 'tCampanias'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Campania  $campania
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();

            $campania = Campania::find($request->campania_id);

            $campania->campania_fecha_finalizacion = $request->campania_fecha_finalizacion;
            $campania->campania_observaciones = $request->campania_observaciones;

            $campania->save();

            $campania_tipos = DB::table('campania_vins')
                ->where('campania_id', '=', $request->campania_id)
                ->where('deleted_at', '=', null)
                ->get();

            /** Insertar tipos de campaña nuevos */
            foreach ($request->tipo_campanias as $t_campania_id) {
                $tipo_campania_id = (int)$t_campania_id;

                $existe = DB::table('campania_vins')
                    ->where('campania_id', '=', $request->campania_id)
                    ->where('tipo_campania_id', '=', $tipo_campania_id)
                    ->where('deleted_at', '=', null)
                    ->get();

                if(count($existe) == 0){
                    DB::insert('INSERT INTO campania_vins (tipo_campania_id, campania_id) VALUES (?, ?)', [$tipo_campania_id, $request->campania_id]);

                    // Guardar histórico de la asignación de la campaña
                    $fecha = date('Y-m-d');
                    $user = User::find(Auth::id());
                    $vin = Vin::findOrfail($campania->vin_id);
                    $ubicPatio = UbicPatio::where('vin_id', $vin->vin_id)->first();
                    
                    if($ubicPatio){
                        $bloque_id = $ubicPatio->bloque_id;
                    } else {
                        $bloque_id = null;
                    }

                    $tipo_camp_desc = TipoCampania::find($tipo_campania_id)->tipo_campania_descripcion;

                    if($bloque_id != null){
                        $bloqueOrigen = Bloque::find($bloque_id);

                        DB::insert('INSERT INTO historico_vins
                            (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                            origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                            [
                                $vin->vin_id,
                                $vin->vin_estado_inventario_id,
                                $fecha,
                                $user->user_id,
                                $bloque_id,
                                $bloque_id,
                                $user->empresa_id,
                                "Nuevo tipo de campaña asignado:" . $tipo_camp_desc,
                                "Patio: " . $bloqueOrigen->onePatio->patio_nombre . ". Bloque: $bloqueOrigen->bloque_nombre. Fila: $ubicPatio->ubic_patio_fila. Columna: $ubicPatio->ubic_patio_columna.",
                                "Patio: " . $bloqueOrigen->onePatio->patio_nombre . ". Bloque: $bloqueOrigen->bloque_nombre. Fila: $ubicPatio->ubic_patio_fila. Columna: $ubicPatio->ubic_patio_columna.",
                            ]
                        );
                    } else {
                        DB::insert('INSERT INTO historico_vins
                            (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                            origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                            [
                                $vin->vin_id,
                                $vin->vin_estado_inventario_id,
                                $fecha,
                                $user->user_id,
                                $bloque_id,
                                $bloque_id,
                                $user->empresa_id,
                                "Nuevo tipo de campaña asignado:" . $tipo_camp_desc,
                                "VIN sin ubicación (fuera de bloque) al asignar nueva campaña.",
                                "VIN preparado para ser asignado a nueva ubicación y estado."
                            ]
                        );
                    }
                }
            }

            /** Eliminar de los tipos de campaña aquellos que hayan sido desmarcados */

            foreach($campania_tipos as $tipoCamp){
                $enc = false;
                $tipo_campania_id = $tipoCamp->tipo_campania_id;

                foreach($request->tipo_campanias as $t_campania_id){
                    if((int)$t_campania_id === $tipo_campania_id){
                        $enc = true;
                        continue;
                    }
                }

                if(!$enc){
                    DB::table('campania_vins')
                        ->where('campania_id', '=', $request->campania_id)
                        ->where('tipo_campania_id', '=', $tipo_campania_id)
                        ->where('deleted_at', '=', null)
                        ->update(['deleted_at' => now()]);

                    // Guardar histórico de la asignación de la campaña
                    $fecha = date('Y-m-d');
                    $user = User::find(Auth::id());
                    $vin = Vin::findOrfail($campania->vin_id);
                    $ubicPatio = UbicPatio::where('vin_id', $vin->vin_id)->first();

                    if($ubicPatio){
                        $bloque_id = $ubicPatio->bloque_id;
                    } else {
                        $bloque_id = null;
                    }

                    $tipo_camp_desc = TipoCampania::find($tipo_campania_id)->tipo_campania_descripcion;

                    if($bloque_id != null){
                        $bloqueOrigen = Bloque::find($bloque_id);

                        DB::insert('INSERT INTO historico_vins
                            (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                            origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                            [
                                $vin->vin_id,
                                $vin->vin_estado_inventario_id,
                                $fecha,
                                $user->user_id,
                                $bloque_id,
                                $bloque_id,
                                $user->empresa_id,
                                "Tipo de campaña removido:" . $tipo_camp_desc,
                                "Patio: " . $bloqueOrigen->onePatio->patio_nombre . ". Bloque: $bloqueOrigen->bloque_nombre. Fila: $ubicPatio->ubic_patio_fila. Columna: $ubicPatio->ubic_patio_columna.",
                                "Patio: " . $bloqueOrigen->onePatio->patio_nombre . ". Bloque: $bloqueOrigen->bloque_nombre. Fila: $ubicPatio->ubic_patio_fila. Columna: $ubicPatio->ubic_patio_columna.",
                            ]
                        );
                    } else {
                        DB::insert('INSERT INTO historico_vins
                            (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                            origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                            [
                                $vin->vin_id,
                                $vin->vin_estado_inventario_id,
                                $fecha,
                                $user->user_id,
                                $bloque_id,
                                $bloque_id,
                                $user->empresa_id,
                                "Tipo de campaña removido:" . $tipo_camp_desc,
                                "VIN sin ubicación (fuera de bloque) al remover campaña de la lista de campañas.",
                                "VIN preparado para ser asignado a nueva ubicación y estado."
                            ]
                        );
                    }
                }
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            flash('Error actualizando campaña.')->error();
            return redirect()->route('campania.index');
        }
        flash('Campaña actualizada con éxito.')->success();
        return redirect()->route('campania.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Campania  $campania
     * @return \Illuminate\Http\Response
     */
    public function editTarea($id_tarea)
    {
        $tarea_id =  Crypt::decrypt($id_tarea);
        $tarea = Tarea::findOrfail($tarea_id);

        $vin_codigo = $tarea->codigoVin();

        $tipo_tareas_array = DB::table('tipo_tareas')
            ->orderBy('tipo_tarea_id')
            ->where('deleted_at', null)
            ->pluck('tipo_tarea_descripcion', 'tipo_tarea_id');

        $tipo_destinos_array = DB::table('tipo_destinos')
            ->orderBy('tipo_destino_id')
            ->where('deleted_at', null)
            ->pluck('tipo_destino_descripcion', 'tipo_destino_id');

        $tipo_campanias_array = TipoCampania::all()
            ->sortBy('tipo_campania_id')
            ->where('deleted_at', null)
            ->pluck('tipo_campania_descripcion', 'tipo_campania_id');

        $campania = Campania::where('vin_id', $tarea->vin_id)
            ->where('deleted_at', null)
            ->first();

        if (isset($campania)){
            $campania_id = $campania->campania_id;
        } else {
            $campania_id = 0;
        }


        $tCampanias = DB::table('campania_vins')
            ->join('tipo_campanias', 'campania_vins.tipo_campania_id', '=', 'tipo_campanias.tipo_campania_id')
            ->select('campania_vins.campania_id', 'tipo_campanias.tipo_campania_id', 'tipo_campanias.tipo_campania_descripcion')
            ->where('campania_vins.campania_id', $campania_id)
            ->where('campania_vins.deleted_at', null)
            ->where('tipo_campanias.deleted_at', null)
            ->get();

        $responsables = User::where('rol_id', 4)
            ->orWhere('rol_id', 5)
            ->orWhere('rol_id', 6)
            ->get();

        $responsables_array= [];

        foreach($responsables as $k => $v){
            $responsables_array[$v->user_id] = $v->user_nombre. " " . $v->user_apellido;
        }

        return view('planificacion.edit', compact('tarea', 'vin_codigo', 'tipo_tareas_array', 'tipo_destinos_array', 'tipo_campanias_array', 'tCampanias', 'responsables_array', 'campania_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Campania  $campania
     * @return \Illuminate\Http\Response
     */
    public function updateTarea(Request $request)
    {
        try {
            DB::beginTransaction();
            $tarea = Tarea::find($request->tarea_id);

            $tipo_tarea_id_anterior = $tarea->tipo_tarea_id;

            $tarea->tarea_prioridad = $request->tarea_prioridad;
            $tarea->tarea_fecha_finalizacion = $request->tarea_fecha_finalizacion;
            $tarea->tarea_hora_termino = $request->tarea_hora_termino;
            $tarea->tipo_tarea_id = $request->tipo_tarea_id;
            $tarea->tipo_destino_id = $request->tipo_destino_id;

            $tarea->save();

            if($tipo_tarea_id_anterior != $tarea->tipo_tarea_id){
                // Guardar histórico de la asignación de la campaña
                $fecha = date('Y-m-d');
                $user = User::find(Auth::id());
                $vin = Vin::findOrfail($tarea->vin_id);
                $ubicPatio = UbicPatio::where('vin_id', $vin->vin_id)->first();
                
                if($ubicPatio){
                    $bloque_id = $ubicPatio->bloque_id;
                } else {
                    $bloque_id = null;
                }

                $tipo_tarea = DB::table("tipo_tareas")
                    ->where('tipo_tarea_id', $tarea->tipo_tarea_id)
                    ->first();

                $desc_tarea = $tipo_tarea->tipo_tarea_descripcion;

                if($bloque_id != null){
                    $bloqueOrigen = Bloque::find($bloque_id);

                    DB::insert('INSERT INTO historico_vins
                        (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                        origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                        [
                            $vin->vin_id,
                            $vin->vin_estado_inventario_id,
                            $fecha,
                            $user->user_id,
                            $bloque_id,
                            $bloque_id,
                            $user->empresa_id,
                            "Cambio de tarea previamente asignada a: " . $desc_tarea,
                            "Patio: " . $bloqueOrigen->onePatio->patio_nombre . ". Bloque: $bloqueOrigen->bloque_nombre. Fila: $ubicPatio->ubic_patio_fila. Columna: $ubicPatio->ubic_patio_columna.",
                            "Patio: " . $bloqueOrigen->onePatio->patio_nombre . ". Bloque: $bloqueOrigen->bloque_nombre. Fila: $ubicPatio->ubic_patio_fila. Columna: $ubicPatio->ubic_patio_columna.",
                        ]
                    );
                } else {
                    DB::insert('INSERT INTO historico_vins
                        (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                        origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                        [
                            $vin->vin_id,
                            $vin->vin_estado_inventario_id,
                            $fecha,
                            $user->user_id,
                            $bloque_id,
                            $bloque_id,
                            $user->empresa_id,
                            "Cambio de tarea previamente asignada a: " . $desc_tarea,
                            "VIN sin ubicación (fuera de bloque) al modificar tarea.",
                            "VIN preparado para ser asignado a nueva ubicación y estado."
                        ]
                    );
                }
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            flash('Error actualizando tarea.')->error();
            return redirect()->route('planificacion.index');
        }
        flash('Tarea actualizada con éxito.')->success();
        return redirect()->route('planificacion.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Campania  $campania
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_campania)
    {
        $campania_id =  Crypt::decrypt($id_campania);

        try {
            $campania = Campania::findOrfail($campania_id)->delete();

            flash('Los datos de la campaña han sido eliminados satisfactoriamente.')->success();
            return redirect('campania');
        }catch (\Exception $e) {

            flash('Error al intentar eliminar los datos de la campaña.')->error();
            return redirect('campania');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Campania  $campania
     * @return \Illuminate\Http\Response
     */
    public function destroyTarea($id_tarea)
    {
        $tarea_id =  Crypt::decrypt($id_tarea);

        try {
            $tarea = Tarea::findOrfail($tarea_id)->delete();

            flash('Los datos de la tarea han sido eliminados satisfactoriamente.')->success();
            return redirect('planificacion');
        }catch (\Exception $e) {

            flash('Error al intentar eliminar los datos de la tarea.')->error();
            return redirect('planificacion');
        }
    }



    public function exportResultadoBusquedaVins(Request $request)
    {
       // dd($request->resultado_busqueda);
        $query = DB::table('tareas')
            ->join('vins','vins.vin_id','=','tareas.tarea_id')
            ->join('users','users.user_id','=','tareas.user_id')
            ->join('tipo_tareas','tipo_tareas.tipo_tarea_id','=','tareas.tipo_tarea_id')
            ->join('tipo_destinos','tipo_destinos.tipo_destino_id','=','tareas.tipo_destino_id')
            ->select('tarea_id','vin_codigo','vin_patente','tipo_tarea_descripcion',DB::raw("CONCAT(user_nombre, ' ', user_apellido) as usuario_responsable"),
            'tipo_destino_descripcion','tarea_fecha_finalizacion','tarea_hora_termino','tareas.updated_at')
            ->get();

            $j_query = json_encode($query);



        return Excel::download(new TareasVinsExport(json_decode($j_query)), 'historico_tareas.xlsx');
    }
}
