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

        $marcas = Marca::orderBy('marca_nombre')
            ->select('marca_id', 'marca_nombre')
            ->pluck('marca_nombre', 'marca_id');

        $tipo_campanias_array = TipoCampania::select('tipo_campania_id', 'tipo_campania_descripcion')
            ->pluck('tipo_campania_descripcion', 'tipo_campania_id');

        $patios = Patio::orderBy('patio_nombre')
            ->select('patio_id', 'patio_nombre')
            ->pluck('patio_nombre', 'patio_id');

        /** Listado de Campañas para la vista de planificación */

        // Campañas para los roles SúperAdministrador (1), Administrador (2) y Operador (3)
        if($user_rol_id == 1 || $user_rol_id == 2 || $user_rol_id == 3){
            $campanias = Campania::all()
                ->sortBy('campania_id');
        } elseif($user_rol_id == 4){  // Campañas para ser vistas por el perfil Customer (4)
            $campanias = Campania::where('user_id', Auth::user()->user_id)
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
            if($user_rol_id == 1 || $user_rol_id == 2 || $user_rol_id == 3){
                $tabla_vins = Vin::join('users','users.user_id','=','vins.user_id')
                    ->join('empresas','empresas.empresa_id','=','users.empresa_id')
                    ->join('ubic_patios', 'ubic_patios.vin_id', '=', 'vins.vin_id')
                    ->join('bloques', 'bloques.bloque_id', '=', 'ubic_patios.bloque_id')
                    ->join('patios', 'patios.patio_id', '=', 'bloques.patio_id')
                    ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_motor',
                        'empresas.empresa_razon_social', 'vin_fec_ingreso',  'vin_fecha_entrega','patio_nombre', 'bloque_nombre', 'ubic_patio_fila',
                        'ubic_patio_columna')
                    ->orderByRaw('ubic_patio_fila, ubic_patio_columna ASC')
                    ->get();
            } elseif($user_rol_id == 4) {
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
        $auth_user = Auth::user();
        $auth_user_rol = $auth_user->oneRol->rol_id;

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
            ->pluck('tipo_campania_descripcion', 'tipo_campania_id');

        $patios = Patio::orderBy('patio_nombre')
            ->select('patio_id', 'patio_nombre')
            ->pluck('patio_nombre', 'patio_id');

        $users = User::select(DB::raw("CONCAT(user_nombre,' ', user_apellido) AS user_nombres"), 'user_id')
            ->orderBy('user_id')
            ->pluck('user_nombres', 'user_id')
            ->all();

        if ($auth_user_rol == 4) {
            $empresas = Empresa::join('users','empresas.empresa_id','=','users.empresa_id')
                ->select('empresas.empresa_id', 'empresas.empresa_razon_social')
                ->where('users.user_id', $auth_user->user_id)
                ->pluck('empresa_razon_social', 'empresa_id')
                ->all();
        } else {
            $empresas = Empresa::select('empresa_id', 'empresa_razon_social')
                ->orderBy('empresa_razon_social', 'ASC')
                ->pluck('empresa_razon_social', 'empresa_id')
                ->all();
        }

        $estadosInventario = DB::table('vin_estado_inventarios')
            ->select('vin_estado_inventario_id', 'vin_estado_inventario_desc')
            ->pluck('vin_estado_inventario_desc', 'vin_estado_inventario_id');

        $subEstadosInventario = DB::table('vin_sub_estado_inventarios')
            ->select('vin_sub_estado_inventario_id', 'vin_sub_estado_inventario_desc')
            ->pluck('vin_sub_estado_inventario_desc', 'vin_sub_estado_inventario_id');

        $marcas = Marca::orderBy('marca_nombre')
            ->select('marca_id', 'marca_nombre')
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

        return view('planificacion.index', compact('tareas', 'tareas_historicas' ,'tabla_vins', 'users','empresas', 'estadosInventario', 'subEstadosInventario', 'patios', 'marcas', 'responsables_array', 'tipo_tareas_array', 'tipo_destinos_array', 'tipo_campanias_array', 'campanias', 'tipo_campanias', 'arrayTCampanias'));
    }


    // Obtención de tareas finalizadas por Ajax para la vista de Planificación->Tareas
    public function tareasFinalizadasAjax()
    {
        $user = Auth::user();

        if ($user->rol_id == 4) {
            $user_empresa_id = Auth::user()->belongsToEmpresa->empresa_id;

            $tareasFinalizadas = Tarea::join('vins', 'vins.vin_id', 'tareas.vin_id')
                ->join('users','users.user_id','=','vins.user_id')
                ->join('empresas','empresas.empresa_id','=','users.empresa_id')
                ->where('tarea_finalizada', true)
                ->where('tareas.updated_at',  '>', Carbon::now()->subDays(1)->toDateTimeString())
                ->where('empresas.empresa_id', $user_empresa_id)
                ->orderBy('tarea_id')
                ->get();
        } else {
            $tareasFinalizadas = Tarea::where('tarea_finalizada', true)
                ->where('updated_at',  '>', Carbon::now()->subDays(1)->toDateTimeString())
                ->orderBy('tarea_id')
                ->get();
        }

        $arraySalidaTareasFinalizadas = [];

        foreach($tareasFinalizadas as $tareaFinalizada) {
            $tareaSalida = new Tarea();

            $tareaSalida->tarea_id = $tareaFinalizada->tarea_id;
            $tareaSalida->vin_codigo = $tareaFinalizada->oneVin->vin_codigo;

            if ($tareaFinalizada->tarea_prioridad == 0) {
                $tareaSalida->tarea_prioridad = 'Baja';
            } elseif ($tareaFinalizada->tarea_prioridad == 1) {
                $tareaSalida->tarea_prioridad = 'Media';
            } elseif ($tareaFinalizada->tarea_prioridad == 2) {
                $tareaSalida->tarea_prioridad = 'Alta';
            } elseif ($tareaFinalizada->tarea_prioridad == 3) {
                $tareaSalida->tarea_prioridad = 'Urgente';
            } else {
                $tareaSalida->tarea_prioridad = 'Sin prioridad';
            }

            $tareaSalida->tarea_fecha_finalizacion = $tareaFinalizada->tarea_fecha_finalizacion;
            $tareaSalida->tarea_hora_termino = $tareaFinalizada->tarea_hora_termino;
            $tareaSalida->tarea_responsable = $tareaFinalizada->nombreResponsable();

            if ($tareaFinalizada->tarea_finalizada) {
                $tareaSalida->tarea_finalizada = 'Sí';
            } else {
                $tareaSalida->tarea_finalizada = 'No';
            }

            $tareaSalida->tarea_tipo_tarea = $tareaFinalizada->oneTipoTarea();
            $tareaSalida->tarea_tipo_destino = $tareaFinalizada->oneTipoDestino();

            $tareaSalida->rol_id = auth()->user()->rol_id;

            if ($tareaSalida->rol_id == 1 || $tareaSalida->rol_id == 2  || $tareaSalida->rol_id == 3) {
                $tareaSalida->botones_tarea = '<small><a href="' . route('planificacion.edit', Crypt::encrypt($tareaFinalizada->tarea_id)) . '" class="btn-bloque" title="Editar Tarea"><i class="fas fa-edit"></i></a></small><small><a href="'
                    . route('planificacion.destroy', Crypt::encrypt($tareaFinalizada->tarea_id))
                    . '" onclick="return confirm(\'¿Esta seguro que desea eliminar este elemento?\')" class="btn-bloque"  title="Eliminar tarea"><i class="far fa-trash-alt"></i></a></small>';
            } else {
                $tareaSalida->botones_tarea = '';
            }

            array_push($arraySalidaTareasFinalizadas, $tareaSalida);
        }

        if (count($arraySalidaTareasFinalizadas) == 0) {
            return response()->json([
                'success' => false,
                'error' => 1,
                'message' => 'Sin datos de tareas finalizadas para el último día'
            ]);
        }

        return response()->json(
            $arraySalidaTareasFinalizadas
        );
    }

    // Obtención del histórico de tareaspor Ajax para la vista de Planificación->Tareas
    public function historicoTareasAjax()
    {
        $user = Auth::user();

        if ($user->rol_id == 4) {
            $user_empresa_id = Auth::user()->belongsToEmpresa->empresa_id;

            $tareasHistoricas = Tarea::join('vins', 'vins.vin_id', 'tareas.vin_id')
                ->join('users','users.user_id','=','vins.user_id')
                ->join('empresas','empresas.empresa_id','=','users.empresa_id')
                ->where('tarea_finalizada', true)
                ->where('empresas.empresa_id', $user_empresa_id)
                ->orderBy('tarea_id')
                ->get();
        } else {
            $tareasHistoricas = Tarea::where('tarea_finalizada', true)
                ->orderBy('tarea_id')
                ->get();
        }

        $arraySalidaHistoricoTareas = [];

        foreach($tareasHistoricas as $tareaHistorica) {
            $tareaSalida = new Tarea();

            $tareaSalida->tarea_id = $tareaHistorica->tarea_id;
            $tareaSalida->vin_codigo = $tareaHistorica->oneVin->vin_codigo;

            if ($tareaHistorica->tarea_prioridad == 0) {
                $tareaSalida->tarea_prioridad = 'Baja';
            } elseif ($tareaHistorica->tarea_prioridad == 1) {
                $tareaSalida->tarea_prioridad = 'Media';
            } elseif ($tareaHistorica->tarea_prioridad == 2) {
                $tareaSalida->tarea_prioridad = 'Alta';
            } elseif ($tareaHistorica->tarea_prioridad == 3) {
                $tareaSalida->tarea_prioridad = 'Urgente';
            } else {
                $tareaSalida->tarea_prioridad = 'Sin prioridad';
            }

            $tareaSalida->tarea_fecha_finalizacion = $tareaHistorica->tarea_fecha_finalizacion;
            $tareaSalida->tarea_hora_termino = $tareaHistorica->tarea_hora_termino;
            $tareaSalida->tarea_responsable = $tareaHistorica->nombreResponsable();

            if ($tareaHistorica->tarea_finalizada) {
                $tareaSalida->tarea_finalizada = 'Sí';
            } else {
                $tareaSalida->tarea_finalizada = 'No';
            }

            $tareaSalida->tarea_tipo_tarea = $tareaHistorica->oneTipoTarea();
            $tareaSalida->tarea_tipo_destino = $tareaHistorica->oneTipoDestino();

            $tareaSalida->rol_id = auth()->user()->rol_id;

            array_push($arraySalidaHistoricoTareas, $tareaSalida);
        }

        if (count($arraySalidaHistoricoTareas) == 0) {
            if ($user->rol_id == 4) {
                return response()->json([
                    'success' => false,
                    'error' => 1,
                    'message' => 'Sin datos de histórico de tareas finalizadas para la empresa.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 1,
                    'message' => 'Sin datos de histórico de tareas finalizadas.'
                ]);
            }

        }

        return response()->json(
            $arraySalidaHistoricoTareas
        );
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

        $marcas = Marca::orderBy('marca_nombre')
            ->select('marca_id', 'marca_nombre')
            ->pluck('marca_nombre', 'marca_id');

        $tipo_campanias_array = TipoCampania::select('tipo_campania_id', 'tipo_campania_descripcion')
            ->pluck('tipo_campania_descripcion', 'tipo_campania_id');

        $patios = Patio::orderBy('patio_nombre')
            ->select('patio_id', 'patio_nombre')
            ->pluck('patio_nombre', 'patio_id');

        /** Listado de Campañas para la vista de planificación */

        // Campañas para los roles SúperAdministrador (1), Administrador (2) y Operador (3)
        if ($user_rol_id == 1 || $user_rol_id == 2 || $user_rol_id == 3) {
            $campanias = Campania::all()
                ->sortByDesc('campania_id');
        } elseif($user_rol_id == 4) {  // Campañas para ser vistas por el perfil Customer (4)
            $campanias = Campania::where('user_id', Auth::user()->user_id)
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

        if (empty($request->vin_numero) && empty($request->estadoinventario_id) && empty($request->patio_id) && empty($request->marca_id)) {
            /** Búsqueda de vins para la cabecera de la vista de solicitud de campañas */
            if($user_rol_id == 1 || $user_rol_id == 2 || $user_rol_id == 3){
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
                    ->get();
            } elseif($user_rol_id == 4) {
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
            }
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

            if($marca) {
                $marca_nombre = $marca->marca_nombre;
            }else{
                $marca_nombre = 'Sin marca';
            }

            $patio = Patio::where('patio_id', $request->patio_id)
                ->first();

            if(!empty($patio->patio_nombre))
            {
                $patio_id = $patio->patio_id;
            }else{
                $patio_id = 0;
            }

            if(!empty($request->vin_numero)){
                $tabla_vins = [];

                foreach(explode(PHP_EOL,$request->vin_numero) as $row){
                    $arreglo_vins[] = trim($row);
                }

                foreach($arreglo_vins as $v){
                    $existsQuery = Vin::join('users','users.user_id','=','vins.user_id')
                        ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                        ->where(function ($q) use ($v) {
                            $q->where('vin_codigo', $v)
                                ->orWhere('vin_patente', $v);
                            });

                    if ($user_rol_id == 4) {
                        $existsQuery->where('empresas.empresa_id', $user_empresa_id);
                    }

                    $validate = $existsQuery->exists();

                    if($validate == true){
                        $query = Vin::join('users','users.user_id','=','vins.user_id')
                            ->join('vin_estado_inventarios','vins.vin_estado_inventario_id','=','vin_estado_inventarios.vin_estado_inventario_id')
                            ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                            ->join('marcas','vins.vin_marca','=','marcas.marca_id')
                            ->where(function ($q) use ($v) {
                                $q->where('vin_codigo', $v)
                                    ->orWhere('vin_patente', $v);
                            });

                        if ($user_rol_id == 4) {
                            $query->where('empresas.empresa_id', $user_empresa_id);
                        } elseif ($user_rol_id == 5 || $user_rol_id == 6 || $user_rol_id == 7) {
                            $query->where('empresas.empresa_id', 0);
                        }

                        if($marca_nombre != 'Sin marca'){
                            $query->where('vin_marca', $marca->marca_id);
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
                            } else {
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

                        array_push($tabla_vins, $query->first());
                    } else {
                        if(count($arreglo_vins) >= 1){
                            $message[$v] = "Vin o patente: " . $v . " no se encuentra en la lista, o no pertenece a la empresa.";
                        } else {
                            if($user_rol_id == 1 || $user_rol_id == 2 || $user_rol_id == 3){
                                $query = Vin::join('users','users.user_id','=','vins.user_id')
                                    ->join('vin_estado_inventarios','vins.vin_estado_inventario_id','=','vin_estado_inventarios.vin_estado_inventario_id')
                                    ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                                    ->join('marcas','vins.vin_marca','=','marcas.marca_id');
                            } elseif($user_rol_id == 4) {
                                $query = Vin::join('users','users.user_id','=','vins.user_id')
                                    ->join('vin_estado_inventarios','vins.vin_estado_inventario_id','=','vin_estado_inventarios.vin_estado_inventario_id')
                                    ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                                    ->join('marcas','vins.vin_marca','=','marcas.marca_id')
                                    ->where('empresas.empresa_id', $user_empresa_id);
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

                            $tabla_vins = $query->get();
                        }
                    }
                }
            }else{
                if($user_rol_id == 1 || $user_rol_id == 2 || $user_rol_id == 3){
                    $query = Vin::join('users','users.user_id','=','vins.user_id')
                        ->join('vin_estado_inventarios','vins.vin_estado_inventario_id','=','vin_estado_inventarios.vin_estado_inventario_id')
                        ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                        ->join('marcas','vins.vin_marca','=','marcas.marca_id');
                } elseif($user_rol_id == 4) {
                    $query = Vin::join('users','users.user_id','=','vins.user_id')
                        ->join('vin_estado_inventarios','vins.vin_estado_inventario_id','=','vin_estado_inventarios.vin_estado_inventario_id')
                        ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                        ->join('marcas','vins.vin_marca','=','marcas.marca_id')
                        ->where('empresas.empresa_id', $user_empresa_id);
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

                $tabla_vins = $query->get();
            }
        }

        return view('campania.solicitudCampania', compact('request', 'tabla_vins', 'estadosInventario', 'subEstadosInventario', 'patios', 'marcas', 'tipo_campanias_array', 'campanias', 'tipo_campanias', 'arrayTCampanias'));
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
        $tabla_vins = [];

        /** A partir de aqui las consultas del cuadro de busqueda */
        // Primer caso: Consulta general sin ningún criterio de filtro.
        if(empty($request->empresa_id) && empty($request->vin_numero) && empty($request->estadoinventario_id) && empty($request->patio_id) && empty($request->marca_id)){
            // No está previsto enviar resultados globales.
            // TO DO: Mejorar eficiencia de renderizado de resultados.
            return response()->json([
                'success' => false,
                'message' => "Debe seleccionar al menos un criterio de búsqueda",
                'error' => 1
            ]);
        } elseif ($request->has('empresa_id') || $request->has('vin_numero') || $request->has('estadoinventario_id') || $request->has('patio_id') || $request->has('marca_id')){
            // Segundo caso: Se seleccionó algún criterio de filtro para la búsqueda.
            $query = Vin::with('oneMarca', 'ubicPatio', 'oneUser', 'entregas');

            $users = User::where('empresa_id', $request->empresa_id)->get();

            if(Auth::user()->rol_id == 4) {
                $user_empresa_id = Auth::user()->belongsToEmpresa->empresa_id;
            } else {
                if(count($users) > 0) {
                    $user_empresa_id = $request->empresa_id;
                } else {
                    if (!empty($request->empresa_id)) {
                        $user_empresa_id = $request->empresa_id;
                    } else {
                        $user_empresa_id = 0;
                    }
                }
            }

            if (!empty($request->empresa_id) && empty($request->vin_numero) && empty($request->estadoinventario_id) && empty($request->patio_id) && empty($request->marca_id)) {
                $query->join('users','users.user_id','=','vins.user_id')
                    ->join('empresas', 'users.empresa_id','=','empresas.empresa_id')
                    ->where('empresas.empresa_id', $user_empresa_id);

                $result = $query->get();

                foreach ($result as $vinResult){
                    array_push($tabla_vins, $vinResult);
                }
            } elseif (!empty($request->estadoinventario_id) && empty($request->empresa_id) && empty($request->vin_numero) && empty($request->patio_id) && empty($request->marca_id)) {
                $query->where('vin_estado_inventario_id', $request->estadoinventario_id);

                if(Auth::user()->rol_id == 4){
                    $query->join('users','users.user_id','=','vins.user_id')
                        ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                        ->where('empresas.empresa_id', $user_empresa_id);
                }

                $result = $query->get();

                foreach ($result as $vinResult){
                    array_push($tabla_vins, $vinResult);
                }
            } elseif (!empty($request->patio_id) && empty($request->empresa_id) && empty($request->vin_numero) && empty($request->estadoinventario_id) && empty($request->marca_id)) {
                $patio = Patio::where('patio_id', $request->patio_id)
                        ->first();

                $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                    ->join('bloques','bloques.bloque_id','=','ubic_patios.bloque_id')
                    ->join('patios','patios.patio_id','=','bloques.patio_id')
                    ->where('patios.patio_id', $patio->patio_id);

                if(Auth::user()->rol_id == 4){
                    $query->join('users','users.user_id','=','vins.user_id')
                        ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                        ->where('empresas.empresa_id', $user_empresa_id);
                }

                $result = $query->get();

                foreach ($result as $vinResult){
                    array_push($tabla_vins, $vinResult);
                }
            } elseif (!empty($request->marca_id) && empty($request->empresa_id) && empty($request->vin_numero) && empty($request->estadoinventario_id) && empty($request->patio_id)) {
                $query->where('vin_marca', $request->marca_id);

                if(Auth::user()->rol_id == 4){
                    $query->join('users','users.user_id','=','vins.user_id')
                        ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                        ->where('empresas.empresa_id', $user_empresa_id);
                }

                $result = $query->get();

                foreach ($result as $vinResult){
                    array_push($tabla_vins, $vinResult);
                }
            } else {
                $estado = DB::table('vin_estado_inventarios')
                        ->where('vin_estado_inventario_id', $request->estadoinventario_id)
                        ->get();

                if(!empty($estado[0]->vin_estado_inventario_id)){
                    $estado_id = $estado[0]->vin_estado_inventario_id;
                }else{
                    $estado_id = 0;
                }

                $marca = Marca::find($request->marca_id);

                if($marca) {
                    $marca_nombre = $marca->marca_nombre;
                }else{
                    $marca_nombre = 'Sin marca';
                }

                $patio = Patio::where('patio_id', $request->patio_id)
                    ->first();

                if($patio) {
                    $patio_id = $patio->patio_id;
                }else{
                    $patio_id = 0;
                }

                if (!empty($request->vin_numero)) {
                    foreach(explode(PHP_EOL,$request->vin_numero) as $row){
                        $arreglo_vins[] = trim($row);
                    }

                    $message = [];

                    foreach($arreglo_vins as $v){
                        if(Auth::user()->rol_id == 4) {
                            $validate = Vin::join('users','users.user_id','=','vins.user_id')
                                ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                                ->where('empresas.empresa_id', $user_empresa_id)
                                ->where(function ($query) use ($v) {
                                    $query->where('vin_codigo', $v)
                                        ->orWhere('vin_patente', $v);
                                })
                                ->exists();
                        } else {
                            $validate = Vin::where('vin_codigo', $v)
                                ->orWhere('vin_patente', $v)
                                ->exists();
                        }

                        if($validate == true){
                            $query->where(function ($cons) use ($v) {
                                $cons->where('vin_codigo', $v)
                                    ->orWhere('vin_patente', $v);
                            });

                            if (count($users) > 0) {
                                $query->join('users', 'users.user_id', 'vins.user_id')
                                    ->join('empresas', 'empresas.empresa_id', 'users.empresa_id')
                                    ->where('empresas.empresa_id', $user_empresa_id);
                            }

                            if($marca_nombre != 'Sin marca'){
                                $query->where('vin_marca', $marca->marca_id);
                            }

                            if($estado_id > 0) {
                                $query->where('vin_estado_inventario_id', $estado_id);
                            }

                            $result = $query->first();

                            if ($patio_id > 0){
                                if ($result->ubicPatio != null){
                                    if ($patio_id == $result->ubicPatio->oneBloque->onePatio->patio_id){
                                        array_push($tabla_vins, $result);
                                    }
                                }
                            } else {
                                array_push($tabla_vins, $result);
                            }
                        } else {
                            if(count($arreglo_vins) >= 1){
                                if(Auth::user()->rol_id == 4){
                                    $message[$v] = "Vin o patente: " . $v . " no se encuentra en la lista o no pertenece a la empresa " . Auth::user()->belongsToEmpresa->empresa_id . ".";
                                } else {
                                    $message[$v] = "Vin o patente: " . $v . " no se encuentra en la lista";
                                }
                            } else {
                                if($user_empresa_id > 0){
                                    if (count($users) > 0) {
                                        $query->join('users', 'users.user_id', 'vins.user_id')
                                            ->join('empresas', 'empresas.empresa_id', 'users.empresa_id')
                                            ->where('empresas.empresa_id', $user_empresa_id);
                                    }
                                }

                                if($marca_nombre != 'Sin marca'){
                                    $query->where('vin_marca', $marca->marca_id);
                                }

                                if($estado_id > 0) {
                                    $query->where('vins.vin_estado_inventario_id', $estado_id);
                                }

                                $result = $query->get();

                                foreach ($result as $vinResult){
                                    if ($patio_id > 0){
                                        if ($vinResult->ubicPatio != null){
                                            if ($patio_id == $vinResult->ubicPatio->oneBloque->onePatio->patio_id){
                                                array_push($tabla_vins, $result);
                                            }
                                        }
                                    } else {
                                        array_push($tabla_vins, $vinResult);
                                    }

                                }
                            }
                        }
                        // Reiniciar la consulta para el próximo VIN
                        $query = Vin::with('oneMarca', 'ubicPatio', 'oneUser', 'entregas');
                    }

                    // $message = [];

                    // foreach(explode(PHP_EOL,$request->vin_numero) as $row){
                    //     $arreglo_vins[] = trim($row);
                    // }

                    // foreach($arreglo_vins as $v){
                    //     $validate = DB::table('vins')
                    //         ->where('vin_codigo', $v)
                    //         ->orWhere('vin_patente', $v)
                    //         ->exists();

                    //     if($validate == true){
                    //         if (!empty($request->empresa_id)){
                    //             $query->where(function ($cons) use ($v) {
                    //                 $cons->where('vin_codigo', $v)
                    //                     ->orWhere('vin_patente', $v);
                    //             });
                    //         } else {
                    //             $query->where('vin_codigo',$v)
                    //                 ->orWhere('vin_patente', $v);
                    //         }

                    //         $result = $query->first();

                    //         array_push($tabla_vins, $result);
                    //     } else {
                    //         $message[$v] = "Vin o patente: " . $v . " no se encuentra en la lista";
                    //     }

                    //     // Reiniciar la consulta para el próximo VIN
                    //     $query = Vin::with('oneMarca', 'ubicPatio', 'oneUser', 'entregas');

                    //     if (!empty($request->empresa_id)) {
                    //         $query->join('users','users.user_id','=','vins.user_id')
                    //             ->join('empresas', 'users.empresa_id','=','empresas.empresa_id')
                    //             ->where('empresas.empresa_id', $request->empresa_id);
                    //     }
                    // }
                } else {
                    if ($user_empresa_id > 0){
                        $query->join('users','users.user_id','=','vins.user_id')
                            ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                            ->where('empresas.empresa_id', $user_empresa_id);
                    }

                    if($marca_nombre != 'Sin marca'){
                        $query->where('vin_marca', $marca->marca_id);
                    }

                    if($estado_id > 0) {
                        $query->where('vins.vin_estado_inventario_id', $estado_id);
                    }

                    $result = $query->get();

                    foreach($result as $vinResult){
                        if ($patio_id > 0){
                            if ($vinResult->ubicPatio != null){
                                if ($patio_id == $vinResult->ubicPatio->oneBloque->onePatio->patio_id){
                                    array_push($tabla_vins, $vinResult);
                                }
                            }
                        } else {
                            array_push($tabla_vins, $vinResult);
                        }
                    }
                }
            }
        }

        $tabla_resultados = [];

        foreach($tabla_vins as $vin){
            if($vin){
                $vin_salida = new Vin();

                $vin_salida->vin_id = $vin->vin_id;
                $vin_salida->vin_id_checkbox = '<input type="checkbox" class="check-tarea" value="' . $vin->vin_id . '" name="checked_vins[]" id="check-vin-' . $vin->vin_id . '">';
                $vin_salida->vin_codigo = $vin->vin_codigo;
                $vin_salida->vin_patente = $vin->vin_patente;

                if($vin->oneMarca != null){
                    $vin_salida->marca_nombre = strtoupper($vin->oneMarca->marca_nombre);
                } else {
                    $vin_salida->marca_nombre = 'Sin Marca';
                }

                $vin_salida->vin_modelo = $vin->vin_modelo;
                $vin_salida->vin_color = $vin->vin_color;
                $vin_salida->vin_segmento = $vin->vin_segmento;
                $vin_salida->vin_segmento = $vin->vin_segmento;
                $vin_salida->empresa_razon_social = $vin->oneUser->belongsToEmpresa->empresa_razon_social;
                $vin_salida->vin_estado_inventario_desc = $vin->estadoInventario->vin_estado_inventario_desc;

                if ($vin->ubicPatio != null){
                    if ($vin->ubicPatio->oneBloque->onePatio->patio_nombre == null) {
                        $vin_salida->patio_nombre = '';
                    } else {
                        $vin_salida->patio_nombre = $vin->ubicPatio->oneBloque->onePatio->patio_nombre;
                    }

                    if ($vin->ubicPatio->oneBloque->bloque_nombre == null) {
                        $vin_salida->bloque_nombre = '';
                    } else {
                        $vin_salida->bloque_nombre = $vin->ubicPatio->oneBloque->bloque_nombre;
                    }

                    if ($vin->ubicPatio->ubic_patio_id == null) {
                        $vin_salida->ubic_patio = "Fila: , Columna: ";
                    } else {
                        $vin_salida->ubic_patio = 'Fila: ' . $vin->ubicPatio->ubic_patio_fila . ', Columna: ' . $vin->ubicPatio->ubic_patio_columna;
                    }
                } else {
                    $vin_salida->patio_nombre = '';
                    $vin_salida->bloque_nombre = '';
                    $vin_salida->ubic_patio = "Fila: , Columna: ";
                }

                if (count($vin->guias()) > 0){
                    $vin_salida->color_texto_guia = '<font color="Green">Guía Cargada</font>';
                } else {
                    $vin_salida->color_texto_guia = '<font color="Green">Sin Guía</font>';;
                }

                if($vin->vin_fec_ingreso != null){
                    $vin_salida->vin_fec_ingreso = date("d-m-Y", strtotime($vin->vin_fec_ingreso));
                } else {
                    $vin_salida->vin_fec_ingreso = '';
                }

                if(count($vin->entregas) > 0){
                    $vin_salida->vin_fecha_entrega = date("d-m-Y", strtotime($vin->entregas[0]->entrega_fecha));
                } else {
                    $vin_salida->vin_fecha_entrega = '';
                }

                $vin_salida->botones_vin = '<small><a href="#" type="button" class="btn-historico"  value="' . Crypt::encrypt($vin->vin_id) . '" title="Ver Historico"><i class="fas fa-history"></i></a></small>';

                $vin_salida->rol_id = auth()->user()->rol_id;

                if ($vin_salida->rol_id == 1 || $vin_salida->rol_id == 2  || $vin_salida->rol_id == 3) {
                    $vin_salida->botones_vin .= '<small><a href="' . route('vin.edit', Crypt::encrypt($vin->vin_id)) . '" type="button" class="btn-vin"  title="Editar"><i class="far fa-edit"></i></a></small>' .
                        '<small><a  href="' . route('vin.editarestado', Crypt::encrypt($vin->vin_id)) . '" type="button" class="btn-vin"  title="Cambiar Estado"><i class="fas fa-flag-checkered"></i></a></small>';
                }

                if (count($vin->guias()) < 1){
                    $vin_salida->botones_vin .= '<small><a href="' . route('vin.guia', Crypt::encrypt($vin->vin_id)) . '" type="button" class="btn-vin"  title="Cargar Guía"><i class="fas fa fa-barcode"></i></a></small>';

                } else {
                    $vin_salida->botones_vin .= '<small><a href="' . route('vin.downloadGuia', Crypt::encrypt($vin->vin_id)) . '" type="button" class="btn-vin btn-download-guias-vin"  title="Descargar Guías"><i class="fas fa fa-barcode2"></i></a></small>';
                }

                array_push($tabla_resultados, $vin_salida);
            }
        }

        return response()->json(
            $tabla_resultados
        );
    }

    public function vinCodigos(Request $request)
    {

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
