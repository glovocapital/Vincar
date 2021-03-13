<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\CheckSession;
use App\User;
use App\Empresa;
use App\Imports\VinsCollectionImport;
use App\TipoCampania;
use App\Vin;
use App\Entrega;
use App\Exports\BusquedaVinsExport;
use App\Exports\VinEntregadosExport;
Use App\Guia;
Use App\GuiaVin;
use App\Marca;
use App\Patio;
use App\Predespacho;
use App\UbicPatio;
use Auth;
use Illuminate\Support\Facades\Crypt;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use League\Flysystem\FileNotFoundException;

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
        $queryAgendados = Vin::where('vin_predespacho', true)
            ->where('vin_estado_inventario_id','!=', 8)
            ->join('users','vins.user_id','=','users.user_id')
            ->join('empresas','users.empresa_id','=','empresas.empresa_id')
            ->join('ubic_patios', 'vins.vin_id', '=', 'ubic_patios.vin_id')
            ->join('bloques', 'ubic_patios.bloque_id', '=', 'bloques.bloque_id')
            ->join('patios', 'bloques.patio_id', '=', 'patios.patio_id');

        if($request->from){
            $date = Carbon::createFromFormat('Y-d-m', $request->from);

            $queryAgendados->whereDate('vins.vin_fecha_agendado', '>=', $date);
        }

        if($request->to){
            $date = Carbon::createFromFormat('Y-d-m', $request->to);

            $queryAgendados->whereDate('vins.vin_fecha_agendado', '<=', $date);
        }

        $vin_agendados = $queryAgendados->orderBy('vin_fecha_entrega')
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
            ->join('entregas','entregas.vin_id','=','vins.vin_id')
            ->orderBy('vin_fecha_entrega')
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
            ->where('deleted_at', null)
            ->pluck('user_nombres', 'user_id')
            ->all();

        $empresas = Empresa::select('empresa_id', 'empresa_razon_social')
            ->orderBy('empresa_razon_social')
            ->pluck('empresa_razon_social', 'empresa_id')
            ->all();

        $estadosInventario = DB::table('vin_estado_inventarios')
            ->select('vin_estado_inventario_id', 'vin_estado_inventario_desc')
            ->pluck('vin_estado_inventario_desc', 'vin_estado_inventario_id');

        $subEstadosInventario = DB::table('vin_sub_estado_inventarios')
            ->select('vin_sub_estado_inventario_id', 'vin_sub_estado_inventario_desc')
            ->pluck('vin_sub_estado_inventario_desc', 'vin_sub_estado_inventario_id');

        $marcas = Marca::select('marca_id', 'marca_nombre')
            ->orderBy('marca_nombre')
            ->pluck('marca_nombre', 'marca_id');

        $tabla_vins = [];

        return view('vin.index', compact( 'tabla_vins','users','empresas', 'estadosInventario', 'subEstadosInventario', 'patios', 'marcas', 'vin_entregados', 'vin_entregados_dia','vin_agendados'));
    }

    public function index_json(Request $request)
    {
        set_time_limit (600);
        if($request->ajax())
        {
            $tabla_vins = [];

            /** A partir de aquí las consultas del cuadro de búsqueda */
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

                $patio = Patio::where('patio_id', $request->patio_id)
                    ->first();

                if($patio) {
                    $patio_id = $patio->patio_id;
                }else{
                    $patio_id = 0;
                }

                if(!empty($request->vin_numero)){
                    foreach(explode(PHP_EOL,$request->vin_numero) as $row){
                        $arreglo_vins[] = trim($row);
                    }

                    $message = [];

                    foreach($arreglo_vins as $v){
                        $query = Vin::with('oneMarca', 'ubicPatio', 'oneUser', 'entregas');

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
                            $query->where(function ($query) use ($v) {
                                $query->where('vin_codigo', $v)
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
                    }
                } else {
                    $query = Vin::with('oneMarca', 'ubicPatio', 'oneUser', 'entregas');

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

                    $resultados = $query->get();

                    foreach($resultados as $registro){
                        if ($patio_id > 0){
                            if ($registro->ubicPatio != null){
                                if ($patio_id == $registro->ubicPatio->oneBloque->onePatio->patio_id){
                                    array_push($tabla_vins, $registro);
                                }
                            }
                        } else {
                            array_push($tabla_vins, $registro);
                        }
                    }
                }
            }
        }

        $tabla_resultados = [];

        foreach($tabla_vins as $vin){
            if($vin){
                if (count($vin->guias()) > 0){
                    $vin->vin_downloadGuiaN =  "Guia Cargada";
                } else {
                    $vin->vin_downloadGuiaN =  "Sin Guia";
                }

                if($vin->oneMarca != null){
                    $vin->marca_nombre = $vin->oneMarca->marca_nombre;
                } else {
                    $vin->marca_nombre = 'Sin Marca';
                }

                $vin->vin_downloadGuia =  route('vin.downloadGuia', Crypt::encrypt($vin->vin_id));
                $vin->vin_encrypt =  Crypt::encrypt($vin->vin_id);
                $vin->vin_guia =  route('vin.guia', Crypt::encrypt($vin->vin_id));
                $vin->vin_editarestado =  route('vin.editarestado', Crypt::encrypt($vin->vin_id));
                $vin->vin_edit =  route('vin.edit', Crypt::encrypt($vin->vin_id));
                $vin->rol_id = auth()->user()->rol_id;

                if ($vin->ubicPatio != null){
                    $vin->patio_nombre = $vin->ubicPatio->oneBloque->onePatio->patio_nombre;
                    $vin->bloque_nombre = $vin->ubicPatio->oneBloque->bloque_nombre;
                    $vin->ubic_patio_id = $vin->ubicPatio->ubic_patio_id;
                    $vin->ubic_patio_fila = $vin->ubicPatio->ubic_patio_fila;
                    $vin->ubic_patio_columna = $vin->ubicPatio->ubic_patio_columna;
                }

                $vin->vin_estado_inventario_desc = $vin->estadoInventario->vin_estado_inventario_desc;
                $vin->empresa_razon_social = $vin->oneUser->belongsToEmpresa->empresa_razon_social;

                if($vin->vin_fec_ingreso != null){
                    $vin->vin_fec_ingreso = date("d-m-Y", strtotime($vin->vin_fec_ingreso));
                }
                if ($vin->vin_fecha_agendado != null){
                    $vin->vin_fecha_agendado = date("d-m-Y", strtotime($vin->vin_fecha_agendado));
                }
                if ($vin->vin_fecha_entrega != null){
                    $vin->vin_fecha_entrega = date("d-m-Y", strtotime($vin->vin_fecha_entrega));
                }

                if(count($vin->entregas) > 0){
                    $vin->vin_fecha_entrega = date("d-m-Y", strtotime($vin->entregas[0]->entrega_fecha));
                } else {
                    $vin->vin_fecha_entrega = '';
                }

                array_push($tabla_resultados, $vin);
            }
        }

        return response()->json(
            $tabla_resultados
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
            $vin->vin_codigo = trim($request->vin_codigo);
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
                    $user->empresa_id,
                    "VIN Creado.",
                    "Origen Externo.",
                    "Patio: Bloque y Ubicación por asignar."
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

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show($id)
    // {
    //     //
    // }

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

            $estado_previo = $vin->vin_estado_inventario_id;
            $estado_nuevo = (int)$request->vin_estado_inventario_id;

            $vin->vin_codigo = trim($request->vin_codigo);
            $vin->vin_patente = $request->vin_patente;
            $vin->vin_marca = (int)$request->vin_marca;
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
                            $user->empresa_id,
                            "VIN Arribado.",
                            "Origen Externo: Puerto.",
                            "Patio: Bloque y Ubicación por asignar."
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
                                    $user->empresa_id,
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
                                    $user->empresa_id,
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
                                    $user->empresa_id,
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
                                    $user->empresa_id,
                                    "VIN Suprimido.",
                                    "Sin ubicación previa en bloque.",
                                    "VIN Suprimido. Sin disponibilidad ni ubicación física."
                                ]
                            );
                        }
                    }
                } else if($estado_previo == 8 && $estado_nuevo == 1){
                    $vin->vin_estado_inventario_id = $estado_nuevo;
                    $vin->vin_fec_ingreso = Carbon::now();
                    $vin->vin_predespacho = false;
                    $vin->vin_bloqueado_entrega = false;
                    $vin->vin_fecha_entrega = null;
                    $vin->vin_fecha_agendado = null;

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
                            $user->empresa_id,
                            "VIN nuevamente Anunciado (reingresado al sistema) luego de haber sido entregado anteriormente.",
                            "Sin ubicación previa en bloque. Reingreso de VIN.",
                            "Patio: Bloque y Ubicación por asignar."
                        ]
                    );
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
        } else {
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
                        $user->empresa_id,
                        "VIN Arribado.",
                        "Origen Externo: Puerto.",
                        "Patio: Bloque y Ubicación por asignar."
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
                                $user->empresa_id,
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
                                $user->empresa_id,
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
                                $user->empresa_id,
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
                                $user->empresa_id,
                                "VIN Suprimido.",
                                "Sin ubicación previa en bloque.",
                                "Destino externo. VIN entregado."
                            ]
                        );
                    }
                }
            } else if (($estado_previo == 7 && $estado_nuevo == 1) || ($estado_previo == 8 && $estado_nuevo == 1)){
                $vin->vin_estado_inventario_id = $estado_nuevo;
                $vin->vin_fec_ingreso = Carbon::now();
                $vin->vin_predespacho = false;
                $vin->vin_bloqueado_entrega = false;
                $vin->vin_fecha_entrega = null;
                $vin->vin_fecha_agendado = null;

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
                        $user->empresa_id,
                        "VIN nuevamente Anunciado (reingresado al sistema) luego de haber sido entregado anteriormente.",
                        "Sin ubicación previa en bloque. Reingreso de VIN.",
                        "Patio: Bloque y Ubicación por asignar."
                    ]
                );
            }

            DB::commit();

        } catch (\Exception $e) {
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

        $date = Carbon::now();

        $fecha = $date->toDateString();
        $hora = $date->toTimeString();

        $empresa = $vin->empresa();

        try {
            DB::beginTransaction();

            $guiaVin = $request->file('guia_vin');
            $extensionGuia = $guiaVin->extension();
            $path = $guiaVin->storeAs(
                'GuiaVin',
                "foto de documento ".'- '.Auth::id().' - '.date('Y-m-d').' - '.\Carbon\Carbon::now()->timestamp.'.'.$extensionGuia
            );

            $guia = new Guia();
            $guia->guia_ruta = $path;
            $guia->guia_numero = trim($request->guia_numero);
            $guia->guia_fecha = $request->guia_fecha;
            $guia->empresa_id = $empresa->empresa_id;

            if ($guia->save()) {
                $guia_vin = new GuiaVin();
                $guia_vin->vin_id = $vin_id;
                $guia_vin->guia_id = $guia->guia_id;

                if ($guia_vin->save()) {
                    // Guardar historial del cambio
                    DB::insert('INSERT INTO historico_vins
                        (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                        origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto, created_at, updated_at)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                        [
                            $vin->vin_id,
                            $vin->vin_estado_inventario_id,
                            $fecha,
                            Auth::user()->user_id,
                            null,
                            null,
                            $empresa->empresa_id,
                            "Carga de nueva Guía al VIN. Responsable: " . Auth::user()->user_nombre . " " . Auth::user()->user_apellido . ". Hora: " . $hora,
                            "Guía Nro: " . $guia->guia_numero .", Empresa: " . $empresa->empresa_razon_social . ".",
                            "VIN " . $vin->vin_codigo . ".",
                            $date->toDateTimeString(),
                            $date->toDateTimeString()
                        ]
                    );
                } else {
                    DB::rollback();

                    flash('Error relacionando guia con el VIN.')->error();
                    return redirect('vin');
                }
            } else {
                DB::rollback();

                flash('Error guardando la guia.')->error();
                return redirect('vin');
            }

            DB::commit();

            flash('La guia fue almacenada correctamente.')->success();
            return redirect('vin');

        } catch (\Exception $e) {
            flash('Error registrando de la guia.')->error();
            flash($e->getMessage())->error();
            return redirect('vin');
        }
    }

    public function downloadGuia($id)
    {
        $vin_id =  Crypt::decrypt($id);

        $vin_codigo = Vin::find($vin_id)->vin_codigo;

        $guias = Vin::find($vin_id)->guias();

        if ($guias){
            return view('vin.download_guias_vin', compact('vin_id', 'vin_codigo', 'guias'));
        } else {
            return redirect()->back();
        }
    }

    public function descargarGuia($id)
    {
        $guia_id =  Crypt::decrypt($id);

        $guia = Guia::find($guia_id);

        if($guia)
        {
            try
            {
                return Storage::download($guia->guia_ruta);
            } catch (FileNotFoundException $e) {
                flash('Error: Archivo no encontrado. Informar al administrador.')->error();
                return redirect('guia');
            }
        } else {
            flash('Error: Guía ID: ' . $guia_id . ' no encontrada. Informar al administrador.')->error();
            return redirect('guia');
        }
    }

    // Función para cargar una guía de empresa y relacionarla con los VINs seleccionados de una búsqueda.
    public function storeModalGuiaLote(Request $request)
    {
        $request->validate([
            'guia_vin' => 'max:20000',
        ]);

        $date = Carbon::now();

        $fecha = $date->toDateString();
        $hora = $date->toTimeString();

        // $arrayGuiasEliminar = [];

        try {
            DB::beginTransaction();

            // Comprobar que cada VIN enviado corresponda con la empresa emisora de la guía.
            foreach($request->vin_ids as $vin_id){
                $vinCheck = Vin::find($vin_id);

                if($vinCheck){
                    $empresa = $vinCheck->empresa();

                    // Validar que los VINs pertenecen a la empresa que emite la guía
                    if($request->empresa_guia_id != $empresa->empresa_id){
                        DB::rollback();

                        flash('Error. El VIN seleccionado: ' . $vinCheck->vin_codigo . ' no pertenece a la empresa que emitió la guía.')->error();
                        return back()->withInput();
                    }
                } else {
                    DB::rollBack();

                    flash('Error: VIN con ID: ' . $vin_id . ' no encontrado. Informe al administrador.')->error();
                    return back()->withInput();
                }
            }

            // Almacenar imagen o PDF de la guía en la base de datos.
            $guiaVin = $request->file('guia_vin');
            $extensionGuia = $guiaVin->extension();
            $path = $guiaVin->storeAs(
                'GuiaVin',
                "foto de documento ".'- '.Auth::id().' - '.date('Y-m-d').' - '.\Carbon\Carbon::now()->timestamp.'.'.$extensionGuia
            );

            // Crear la guía y su relación respectiva con los VINs
            $guia = new Guia();
            $guia->guia_ruta = $path;
            $guia->guia_fecha = $request->guia_fecha;
            $guia->guia_numero = trim($request->guia_numero);
            $guia->empresa_id = $request->empresa_guia_id;

            if($guia->save()) {
                foreach($request->vin_ids as $vin_id){
                    $guia_vin = new GuiaVin();
                    $guia_vin->vin_id = $vin_id;
                    $guia_vin->guia_id = $guia->guia_id;

                    if (!$guia_vin->save()) {
                        DB::rollBack();

                        flash('Error guardando relación de VIN con guía en la base de datos.')->error();
                        return back()->withInput();
                    }

                    $vin = Vin::find($vin_id);

                    $emp = $vin->empresa();

                    // Guardar historial del cambio
                    DB::insert('INSERT INTO historico_vins
                        (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                        origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto, created_at, updated_at)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                        [
                            $vin->vin_id,
                            $vin->vin_estado_inventario_id,
                            $fecha,
                            Auth::user()->user_id,
                            null,
                            null,
                            $emp->empresa_id,
                            "VIN en asignación masiva de nueva Guía. Responsable: " . Auth::user()->user_nombre . " " . Auth::user()->user_apellido . ". Hora: " . $hora,
                            "Guía Nro: " . $guia->guia_numero .", Empresa: " . $emp->empresa_razon_social . ".",
                            "VIN " . $vin->vin_codigo . ".",
                            $date->toDateTimeString(),
                            $date->toDateTimeString()
                        ]
                    );
                }
            } else {
                DB::rollBack();

                flash('Error guardando guía en la base de datos.')->error();
                return back()->withInput();
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            flash('Error asignando guía.')->error();
            return redirect()->route('vin.index');
        }

        flash('Guía cargada con éxito.')->success();
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
                            $user->empresa_id,
                            "VIN Arribado.",
                            "Origen Externo: Puerto.",
                            "Patio: Bloque y Ubicación por asignar."
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
                                    $user->empresa_id,
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
                                    $user->empresa_id,
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
                                    $user->empresa_id,
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
                                    $user->empresa_id,
                                    "VIN Suprimido.",
                                    "Sin ubicación previa en bloque.",
                                    "Destino externo. VIN entregado."
                                ]
                            );
                        }
                    }
                } else if($estado_previo == 8 && $estado_nuevo == 1){
                    $vin->vin_estado_inventario_id = $estado_nuevo;
                    $vin->vin_fec_ingreso = Carbon::now();
                    $vin->vin_predespacho = false;
                    $vin->vin_bloqueado_entrega = false;
                    $vin->vin_fecha_entrega = null;
                    $vin->vin_fecha_agendado = null;
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
                            $user->empresa_id,
                            "VIN nuevamente Anunciado (reingresado al sistema) luego de haber sido entregado anteriormente.",
                            "Sin ubicación previa en bloque. Reingreso de VIN.",
                            "Patio: Bloque y Ubicación por asignar."
                        ]
                    );
                }
            }

            DB::commit();

            if($request->ajax()) {
                if($guardados>0) {
                    return response()->json(
                        Array("error"=>0,"mensaje"=>"Guardado con Èxito")
                    );
                } else {
                    return response()->json(
                        Array("error"=>1,"mensaje"=>"Guardado Incompleto")
                    );
                }
            } else {
                flash('Estados cambiados con éxito.')->success();
                return redirect()->route('vin.index');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            if($request->ajax()){
                return response()->json(
                    Array("error"=>1,"mensaje"=>"Error al cambiar estado")
                );
            } else {
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

            if(Marca::find($vin->vin_marca) == null){
                $vinExport->vin_marca = 'Marca incorrecta, corregir';
            } else {
                $vinExport->vin_marca = strtoupper($vin->oneMarca->marca_nombre);
            }

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

                if(count($vinUbic) < 1){
                    $vinExport->patio_nombre = null;
                    $vinExport->bloque_nombre = null;
                    $vinExport->ubic_patio_fila = null;
                    $vinExport->ubic_patio_columna = null;
                } else {
                    $vinExport->patio_nombre = $vinUbic[0]->patio_nombre;
                    $vinExport->bloque_nombre = $vinUbic[0]->bloque_nombre;
                    $vinExport->ubic_patio_fila = $vinUbic[0]->ubic_patio_fila;
                    $vinExport->ubic_patio_columna = $vinUbic[0]->ubic_patio_columna;
                }

            } else {
                $vinExport->patio_nombre = null;
                $vinExport->bloque_nombre = null;
                $vinExport->ubic_patio_fila = null;
                $vinExport->ubic_patio_columna = null;
            }

            array_push($array_vins, $vinExport);
        }

        return Excel::download(new BusquedaVinsExport($array_vins), 'export_busqueda_vins.xlsx');
    }

    public function exportMasivoResultadoBusquedaVins(Request $request)
    {
        $vin_ids = explode(',', $request->vin_ids);

        $array_vins = [];
        foreach($vin_ids as $vin_id){
            $vin = Vin::leftJoin('entregas', 'vins.vin_id', '=', 'entregas.vin_id')
                ->select('vins.*', 'entrega_fecha')
                ->where('vins.vin_id', $vin_id)
                ->first();

            $vinExport = new Vin();
            $vinExport->vin_id = $vin->vin_id;
            $vinExport->vin_codigo = $vin->vin_codigo;
            $vinExport->vin_patente = $vin->vin_patente;

            if(Marca::find($vin->vin_marca) == null){
                $vinExport->vin_marca = 'Marca incorrecta, corregir';
            } else {
                $vinExport->vin_marca = strtoupper($vin->oneMarca->marca_nombre);
            }

            $vinExport->vin_modelo = $vin->vin_modelo;
            $vinExport->vin_color = $vin->vin_color;
            $vinExport->vin_motor = $vin->vin_motor;
            $vinExport->vin_segmento = $vin->vin_segmento;
            $vinExport->vin_fec_ingreso = $vin->vin_fec_ingreso;
            $vinExport->user_id = $vin->oneUser->belongsToEmpresa->empresa_razon_social;
            $vinExport->vin_estado_inventario_id = $vin->oneVinEstadoInventario();
            $vinExport->vin_fecha_entrega = $vin->entrega_fecha;
            $vinExport->vin_fecha_agendado = $vin->vin_fecha_agendado;

            if($vin->vin_estado_inventario_id == 4 || $vin->vin_estado_inventario_id == 5 || $vin->vin_estado_inventario_id == 6){
                $vinUbic = DB::table('ubic_patios')
                    ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                    ->join('patios','bloques.patio_id','=','patios.patio_id')
                    ->where('ubic_patios.vin_id', $vin_id)
                    ->get();

                if(count($vinUbic) < 1){
                    $vinExport->patio_nombre = null;
                    $vinExport->bloque_nombre = null;
                    $vinExport->ubic_patio_fila = null;
                    $vinExport->ubic_patio_columna = null;
                } else {
                    $vinExport->patio_nombre = $vinUbic[0]->patio_nombre;
                    $vinExport->bloque_nombre = $vinUbic[0]->bloque_nombre;
                    $vinExport->ubic_patio_fila = $vinUbic[0]->ubic_patio_fila;
                    $vinExport->ubic_patio_columna = $vinUbic[0]->ubic_patio_columna;
                }
            } else {
                $vinExport->patio_nombre = null;
                $vinExport->bloque_nombre = null;
                $vinExport->ubic_patio_fila = null;
                $vinExport->ubic_patio_columna = null;
            }

            array_push($array_vins, $vinExport);
        }

        return Excel::download(new BusquedaVinsExport($array_vins), 'export_masivo_busqueda_vins.xlsx');
    }

    public function desagendado($id)
    {
        $vin_id =  Crypt::decrypt($id);
        $vin = Vin::findOrfail($vin_id);

        try{
            DB::beginTransaction();

            $vin->vin_predespacho = false;
            $vin->vin_fecha_agendado  = null;
            if($vin->save()){
                $predespacho = Predespacho::where('vin_id', $vin->vin_id)->first();
                if(!$predespacho->delete()) {
                    DB::rollBack();
                    flash('Error eliminando predespacho existente del VIN.')->error();
                    return redirect()->route('vin.index');
                }
            } else {
                DB::rollBack();
                flash('Error desagendando el VIN.')->error();
                return redirect()->route('vin.index');
            }

            DB::commit();

        }  catch (\Throwable $th) {
            DB::rollBack();
            flash('Error desagendado el VIN.')->error();
            return redirect()->route('vin.index');
        }
        flash('VIN desagendado correctamente.')->success();
        return redirect()->route('vin.index');
    }

    // Agendamiento de VINs para entrega
    public function predespacho(Request $request)
    {
        if ($request->predespacho != 1) {
            return response()->json(
                [
                    "error" => 1,
                    "mensaje" => 'Debe seleccionar "Autorizar Entrega" para poder continuar.'
                ]
            );
        }
        //$fecha = Carbon::now();
        $fecha = date('Y-m-d');
        $fechaPredespacho = new Carbon($request->vin_fecha_despacho);

        // Validar que la fecha sea correcta.
        if ($fechaPredespacho <= Carbon::yesterday()){
            return response()->json(
                [
                    "error" => 1,
                    "mensaje" => 'Fecha incorrecta. La fecha de agendamiento no puede ser anterior a la fecha actual.'
                ]
            );
        }

        // Validar que el VIN no ha sido previamente agendado. Si en la lista hay algún VIN
        // previamente agendado, impedirá avanzar hasta tanto no se elimine de la lista de VINs.
        foreach($request->vin_ids as $vin_id){
            $vin = Vin::findOrfail($vin_id);
            $predespacho = Predespacho::where('vin_id', $vin_id)->exists();

            if ($vin->vin_predespacho || $predespacho){
                return response()->json(
                    [
                        "error" => 1,
                        "mensaje" => 'VIN: ' . $vin->vin_codigo . ' previamente agendado para predespacho. Elimínelo de la lista y vuelva a enviar.'
                    ]
                );
            }
        }

        $user = User::find(Auth::id());

        try {
            DB::beginTransaction();

            $transportista = null;

            if ($request->tipo_agendamiento_id == 1){
                $trans = User::where('user_rut','=', $request->usuario_rut)
                    ->first();

                if($trans){
                    $transportista = User::findOrFail($trans->user_id);
                } else {
                    $emailExists = User::where('email', $request->email)
                        ->exists();

                    if($emailExists){
                        DB::rollBack();

                        return response()->json(
                            [
                                "error" => 1,
                                "mensaje" => 'Email ya existente. Por favor intente con otra dirección de Email.'
                            ]
                        );
                    } else {
                        $transportista = new User();
                        $transportista->user_nombre = $request->usuario_nombre;
                        $transportista->user_apellido = $request->usuario_apellido;
                        $transportista->user_rut = $request->usuario_rut;
                        $transportista->user_cargo = "";
                        $transportista->user_estado = 1;
                        $transportista->email = $request->email;
                        $transportista->password = "";
                        $transportista->rol_id = 7;
                        $transportista->user_telefono = "";
                        $transportista->empresa_id = $user->empresa_id;
                        $transportista->save();
                    }
                }
            }

            $guardados = 0;
            // Verificación de VINs.
            foreach($request->vin_ids as $vin_id){
                $vin = Vin::findOrfail($vin_id);

                $existePredespacho = Predespacho::where('vin_id', $vin_id)->exists();

                if ($existePredespacho){
                    DB::rollBack();
                    return response()->json(
                        [
                            "error" => 1,
                            "mensaje" => 'VIN: ' . $vin->vin_codigo . ' previamente agendado para predespacho. Elimínelo de la lista y vuelva a enviar.'
                        ]
                    );
                }

                $estado_inventario = $vin->vin_estado_inventario_id;

                // Nueva validación de VINs. Ahora por estado y luego, de nuevo, verificar que ninguno esté agendado previamente.
                if ($vin->vin_predespacho || ($estado_inventario != 4 && $estado_inventario != 5 && $estado_inventario != 6)){
                    DB::rollBack();

                    return response()->json(
                        [
                            "error" => 1,
                            "mensaje" => 'VIN: ' . $vin->vin_codigo . ' previamente agendado o no cumple condiciones de estado de inventario para predespacho.'
                        ]
                    );
                }

                $predespacho= new Predespacho();

                if ($request->tipo_agendamiento_id == 2) {
                    $predespacho->predespacho_origen = $request->ruta_origen;
                    $predespacho->predespacho_destino = $request->ruta_destino;
                } elseif (($request->tipo_agendamiento_id != 1) && ($request->tipo_agendamiento_id != 2)) {
                    DB::rollBack();

                    return response()->json(
                        [
                            "error" => 1,
                            "mensaje" => 'Tipo de Agendamiento Inválido.'
                        ]
                    );
                }

                $predespacho->responsable_id = Auth::id();

                if($transportista){
                    $predespacho->user_id = $transportista->user_id;
                }

                $predespacho->vin_id = $vin->vin_id;
                $predespacho->tipo_agendamiento_id = $request->tipo_agendamiento_id;

                if ($predespacho->tipo_agendamiento_id == 1){
                    $tipoAgendamiento = "Retiro";
                    $cadena = "Retira: " . $transportista->user_nombre . " " . $transportista->user_apellido . ", RUT: " . $transportista->user_rut;
                } else {
                    $tipoAgendamiento = "Traslado";
                    $cadena = "Traslado. Desde: " . $predespacho->predespacho_origen . ". Hasta: " . $predespacho->predespacho_origen . ".";
                }

                $predespacho->save();

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
                            $estado_inventario,
                            $fecha,
                            $user->user_id,
                            null,
                            null,
                            $user->empresa_id,
                            "VIN Agendado por: " . $user->user_nombre . " " . $user->user_apellido . ".",
                            "VIN Agendado para " . $tipoAgendamiento,
                            $cadena,
                        ]
                    );
                }

            }

            DB::commit();

            if($request->ajax()){
                if($guardados>0){
                    return response()->json(
                        [
                            "error" => 0,
                            "mensaje" => "Guardado con Èxito"
                        ]
                    );
                } else {
                    DB::rollBack();

                    return response()->json(
                        [
                            "error" => 1,
                            "mensaje" => "Guardado Incompleto"
                        ]
                    );
                }
            } else {
                flash('Estados cambiados con éxito.')->success();
                return redirect()->route('vin.index');
            }
        } catch (\Throwable $th) {
            DB::rollBack();

            if($request->ajax()){
                return response()->json(
                    [
                        "error" => 1,
                        "mensaje" => "Error al cambiar estado"
                    ]
                );
            } else {
                flash('Error al cambiar estados.')->error();
                return redirect()->route('vin.index');
            }
        }
    }

    public function  entregaExportResultadoBusquedaVins(Request $request)
    {
        $vin_request = json_decode($request->resultado_busqueda);

        $array_vins = [];

        $vin_entregados = DB::table('vins')
            ->join('users','vins.user_id','=','users.user_id')
            ->join('empresas','empresas.empresa_id','=','users.empresa_id')
            ->join('entregas','entregas.vin_id','=','vins.vin_id')
            ->select('vins.vin_codigo','vins.vin_patente','vins.vin_color','vin_fec_ingreso', 'vins.vin_fecha_agendado', 'entregas.entrega_fecha','empresas.empresa_razon_social')
            ->whereNotNull('entregas.entrega_fecha')
            ->get();

        foreach($vin_entregados as $vin_ent){

            if($vin_ent){
                array_push($array_vins, $vin_ent);
            }

        }

        $data= json_decode( json_encode($array_vins), true);

        return Excel::download(new VinEntregadosExport($data), 'historico_entregados.xlsx');
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
                $estado_inventario = $vin->vin_estado_inventario_id;

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
                            $estado_inventario,
                            $fecha,
                            $user->user_id,
                            null,
                            null,
                            $user->empresa_id,
                            "Cambio de cliente del VIN. ",
                            "Cambio de cliente del VIN. ",
                            "Cambio de cliente del VIN. "
                        ]
                    );
                }

            }

            DB::commit();

            if($request->ajax()) {
                if($guardados>0) {
                    return response()->json(
                        Array("error"=>0,"mensaje"=>"Cambio de cliente realizado con Èxito")
                    );
                } else {
                    return response()->json(
                        Array("error"=>1,"mensaje"=>"Guardado Incompleto")
                    );
                }
            } else {
                flash('Cambio de cliente realizado con éxito.')->success();
                return redirect()->route('vincambiodecliente.index');
            }
        } catch (\Throwable $th) {
            DB::rollBack();

            if($request->ajax()) {
                return response()->json(
                    Array("error"=>1,"mensaje"=>"Error al cambiar el cliente")
                );
            } else {
                flash('Error al cambiar el cliente.')->error();
                return redirect()->route('vincambiodecliente.index');
            }
        }
    }

    /**
     * Bloque la entrega de un VIN a nivel administrativo.
     */

    public function bloqueaEntrega(Request $request)
    {
        $vin_id =  $request->vin_id;
        $vin = Vin::findOrfail($vin_id);

        try{
            $vin->vin_bloqueado_entrega = $request->bloqueado;

            if($vin->save()){
                if($request->bloqueado){
                    $mensaje = "VIN bloqueado correctamente.";
                } else {
                    $mensaje = "VIN desbloqueado correctamente.";
                }
            }
        }  catch (\Throwable $th) {
            flash('Error bloqueando el VIN.')->error();

            return response()->json([
                'success' => false,
                'message' => "Error bloqueando o desbloqueando el VIN: " . $th . ".",
            ]);
        }
        flash('VIN bloqueado correctamente.')->success();

        return response()->json([
            'success' => true,
            'message' => $mensaje,
        ]);
    }
}
