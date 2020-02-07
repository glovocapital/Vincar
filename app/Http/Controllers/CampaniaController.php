<?php

namespace App\Http\Controllers;

use App\Campania;
use App\Empresa;
use App\Http\Middleware\CheckSession;
use App\Http\Middleware\PreventBackHistory;
use App\Patio;
use App\Tarea;
use App\TipoCampania;
use App\User;
use App\Vin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CampaniaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(PreventBackHistory::class);
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

        $estadosInventario = DB::table('vin_estado_inventarios')
            ->select('vin_estado_inventario_id', 'vin_estado_inventario_desc')
            ->pluck('vin_estado_inventario_desc', 'vin_estado_inventario_id');

        $subEstadosInventario = DB::table('vin_sub_estado_inventarios')
            ->select('vin_sub_estado_inventario_id', 'vin_sub_estado_inventario_desc')
            ->pluck('vin_sub_estado_inventario_desc', 'vin_sub_estado_inventario_id');

        $marcas = DB::table('marcas')
            ->select('marca_id', 'marca_nombre')
            ->pluck('marca_nombre', 'marca_id');

        $tipo_campanias_array = TipoCampania::select('tipo_campania_id', 'tipo_campania_descripcion')
            ->pluck('tipo_campania_descripcion', 'tipo_campania_id');

        $patios = DB::table('patios')
            ->select('patio_id', 'patio_nombre')
            ->pluck('patio_nombre', 'patio_id');

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

        /** Búsqueda de los Vins */

        if(!($request->has('vin_numero') || $request->has('estadoinventario_id') || $request->has('patio_id') || $request->has('marca_id'))){
            /** Búsqueda de vins para la cabecera de la vista de solicitud de campañas */
            $tabla_vins = Vin::join('users','users.user_id','=','vins.user_id')
                ->join('empresas','empresas.empresa_id','=','users.empresa_id')
                ->join('ubic_patios', 'ubic_patios.vin_id', '=', 'vins.vin_id')
                ->join('bloques', 'bloques.bloque_id', '=', 'ubic_patios.bloque_id')
                ->join('patios', 'patios.patio_id', '=', 'bloques.patio_id')
                ->select('vins.vin_id','vin_codigo', 'vin_patente', 'vin_marca', 'vin_modelo', 'vin_color', 'vin_motor', 
                'empresas.empresa_razon_social', 'vin_fec_ingreso', 'patio_nombre', 'bloque_nombre', 'ubic_patio_fila', 
                'ubic_patio_columna')
                ->orderByRaw('ubic_patio_fila, ubic_patio_columna ASC')
                ->where('users.empresa_id', $user_empresa_id )
                ->get();
        } else {
            // dd($tabla_vins)

            /** A partir de aqui las consultas del cuadro de busqueda */

            $estado = DB::table('vin_estado_inventarios')
                ->where('vin_estado_inventario_id', $request->estadoinventario_id)
                ->get();

            if(!empty($estado[0]->vin_estado_inventario_id)){
                $estado_id = $estado[0]->vin_estado_inventario_id;
            }else{
                $estado_id = 0;
            }

            $marca = DB::table('marcas')
                ->where('marca_id',$request->marca_id)
                ->get();

            if(!empty($marca[0]->marca_nombre))
            {
                $marca_nombre = $marca[0]->marca_nombre;
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

                foreach(explode(',',$request->vin_numero) as $row){
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
                            ->orWhere('vin_patente',$v)
                            ->where('empresas.empresa_id', $user_empresa_id);

                        if($marca_nombre != 'Sin marca'){
                            $query->where('vin_marca', $marca_nombre);
                        }
        
                        if($estado_id > 0){
                            $query->where('vins.vin_estado_inventario_id', $estado_id);
                        }
                            
                        if($estado_id == 5 || $estado_id == 6) {
                            $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                ->join('patios','bloques.patio_id','=','patios.patio_id')
                                ->where('patios.patio_id', $patio_id);
                        }
                            
                        array_push($tabla_vins, $query->first());
                    } else {
                        $query = DB::table('vins')
                            ->join('users','users.user_id','=','vins.user_id')
                            ->join('vin_estado_inventarios','vins.vin_estado_inventario_id','=','vin_estado_inventarios.vin_estado_inventario_id')
                            ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                            ->where('vins.user_id',$user_empresa_id);

                        if($marca_nombre != 'Sin marca'){
                            $query->where('vin_marca', $marca_nombre);
                        }
        
                        if($estado_id > 0){
                            $query->where('vins.vin_estado_inventario_id', $estado_id);
                        }
                            
                        if($estado_id == 5 || $estado_id == 6) {
                            $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                                ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                                ->join('patios','bloques.patio_id','=','patios.patio_id')
                                ->where('patios.patio_id', $patio_id);
                        }
                            
                        array_push($tabla_vins, $query->get());
                    }
                }
            }else{
                $query = DB::table('vins')
                    ->join('users','users.user_id','=','vins.user_id')
                    ->join('vin_estado_inventarios','vins.vin_estado_inventario_id','=','vin_estado_inventarios.vin_estado_inventario_id')
                    ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                    ->where('empresas.empresa_id', $user_empresa_id);

                if($marca_nombre != 'Sin marca'){
                    $query->where('vin_marca', $marca_nombre);
                }

                if($estado_id > 0){
                    $query->where('vins.vin_estado_inventario_id', $estado_id);
                }
                    
                if($estado_id == 5 || $estado_id == 6) {
                    $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                        ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                        ->join('patios','bloques.patio_id','=','patios.patio_id')
                        ->where('patios.patio_id', $patio_id);
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
        /** Búsqueda de vins para la cabecera de la vista de planificación */
        $vins = Vin::all();

        $tipo_campanias_array = TipoCampania::select('tipo_campania_id', 'tipo_campania_descripcion')
            ->pluck('tipo_campania_descripcion', 'tipo_campania_id');

        $patios = DB::table('patios')
            ->select('patio_id', 'patio_nombre')
            ->pluck('patio_nombre', 'patio_id');

        $users = User::select(DB::raw("CONCAT(user_nombre,' ', user_apellido) AS user_nombres"), 'user_id')
            ->orderBy('user_id')
            ->pluck('user_nombres', 'user_id')
            ->all();

        $empresas = Empresa::select('empresa_id', 'empresa_razon_social')
            ->orderBy('empresa_id')
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
            ->pluck('marca_nombre', 'marca_id');

        $tabla_vins = [];

        /** A partir de aqui las consultas del cuadro de busqueda */
        if(($request->has('vin_numero') || $request->has('estadoinventario_id') || $request->has('patio_id') || $request->has('marca_id'))){

            $estado = DB::table('vin_estado_inventarios')
                ->where('vin_estado_inventario_id',$request->estadoinventario_id)
                ->get();

            if(!empty($estado[0]->vin_estado_inventario_id)){
                $estado_id = $estado[0]->vin_estado_inventario_id;
            }else{
                $estado_id = 0;
            }

            $marca = DB::table('marcas')
                ->where('marca_id',$request->marca_id)
                ->get();

            if(!empty($marca[0]->marca_nombre))
            {
                $marca_nombre = $marca[0]->marca_nombre;
            }else{
                $marca_nombre = 'Sin marca';
            }

            $user = DB::table('users')
                ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                ->where('user_id',$request->user_id)
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

                foreach(explode(',',$request->vin_numero) as $row){
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
                            $query->where('vin_marca',$marca_nombre);
                        }

                        if($estado_id > 0){
                            $query->where('vins.vin_estado_inventario_id', $estado_id);
                        }
                        
                        array_push($tabla_vins, $query->first());
                    } else {
                        $query = DB::table('vins')
                            ->join('users','users.user_id','=','vins.user_id')
                            ->join('vin_estado_inventarios','vins.vin_estado_inventario_id','=','vin_estado_inventarios.vin_estado_inventario_id')
                            ->join('empresas','users.empresa_id','=','empresas.empresa_id');

                        if($user_empresa_id > 0){
                            $query->where('empresas.empresa_id',$user_empresa_id);
                        }
                        
                        if($marca_nombre != 'Sin marca'){
                            $query->where('vin_marca',$marca_nombre);
                        }

                        if($estado_id > 0){
                            $query->where('vins.vin_estado_inventario_id', $estado_id);
                        }
                        
                        array_push($tabla_vins, $query->get());
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
                    $query->where('vin_marca', $marca_nombre);
                }

                if($estado_id > 0){
                    $query->where('vins.vin_estado_inventario_id', $estado_id);
                }
                    
                if($estado_id == 5 || $estado_id == 6) {
                    $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                        ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                        ->join('patios','bloques.patio_id','=','patios.patio_id')
                        ->where('patios.patio_id', $patio_id);
                }

                $tabla_vins = $query->get();
            }
        }

        // dd($tabla_vins);

        /** Valores necesarios para poblar los selects del modal de asignación de tarea */

        $responsables = User::where('rol_id', 4)
            ->orWhere('rol_id', 5)
            ->orWhere('rol_id', 6)
            ->get();

        $responsables_array= [];

        foreach($responsables as $k => $v){
            $responsables_array[$v->user_id] = $v->user_nombre. " " . $v->user_apellido;
        }

        $tipo_tareas_array = DB::table('tipo_tareas')
            ->select('tipo_tarea_id', 'tipo_tarea_descripcion')
            ->pluck('tipo_tarea_descripcion', 'tipo_tarea_id');

        $tipo_destinos_array = DB::table('tipo_destinos')
            ->select('tipo_destino_id', 'tipo_destino_descripcion')
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
        return view('planificacion.index', compact('tabla_vins', 'users','empresas', 'estadosInventario', 'subEstadosInventario', 'patios', 'marcas', 'responsables_array', 'tipo_tareas_array', 'tipo_destinos_array', 'tipo_campanias_array', 'campanias', 'tipo_campanias', 'arrayTCampanias'));
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
            
            foreach ($request->tipo_campanias as $t_campania_id) {
                $tipo_campania_id = (int)$t_campania_id;
                DB::insert('INSERT INTO campania_vins (tipo_campania_id, campania_id) VALUES (?, ?)', [$tipo_campania_id, $campania->campania_id]);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('vin.index')->with('error-msg', 'Error asignando campaña.');
        }

        return redirect()->route('campania.index')->with('success', 'Campaña asignada con éxito.');; 
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

            // dd($request);
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
            
            // foreach ($request->tipo_campanias as $t_campania_id) {
            //     $tipo_campania_id = (int)$t_campania_id;
            //     DB::insert('INSERT INTO campania_vins (tipo_campania_id, campania_id) VALUES (?, ?)', [$tipo_campania_id, $campania->campania_id]);
            // }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('planificacion.index')->with('error-msg', 'Error asignando tarea.');
        }

        return redirect()->route('planificacion.index')->with('success', 'Tarea asignada con éxito.');; 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeModalTareaLotes(Request $request)
    {
        try {

            // dd($request);
            DB::beginTransaction();

            foreach($request->vin_ids as $vin_id){
                $tarea = new Tarea();

                $tarea->tarea_fecha_finalizacion = $request->tarea_fecha_finalizacion;
                $tarea->tarea_prioridad = $request->tarea_prioridad;
                $tarea->tarea_hora_termino = $request->tarea_hora_termino;
                $tarea->vin_id = $request->vin_id;
                $tarea->user_id = $request->tarea_responsable_id;
                $tarea->tipo_tarea_id = $request->tipo_tarea_id;
                $tarea->tipo_destino_id = $request->tipo_destino_id;
                
                $tarea->save();
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('planificacion.index')->with('error-msg', 'Error asignando tarea.');
        }

        return redirect()->route('planificacion.index')->with('success', 'Tarea asignada con éxito.');; 
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
    public function edit(Campania $campania)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Campania  $campania
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Campania $campania)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Campania  $campania
     * @return \Illuminate\Http\Response
     */
    public function destroy(Campania $campania)
    {
        //
    }
}
