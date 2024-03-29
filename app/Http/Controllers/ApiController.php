<?php

namespace App\Http\Controllers;

use App\Bloque;
use App\Conductor;
use App\Ubicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Vin;
use App\UbicPatio;
use App\Patio;
use App\Inspeccion;
use App\DanoPieza;
use App\Tarea;
use App\Foto;
use App\Empresa;
use App\Transportista;
use App\Entrega;
use App\FotoNN;
use App\Guia;
use App\GuiaVin;
use App\Predespacho;
use App\Ruta;
use App\Thumbnail;
use App\Tour;
use App\VehiculoNN;
use Carbon\Carbon;

class ApiController extends Controller
{
    public function index(){
        return response()->json(["Msg"=>"Api Activo"]);
    }
    public function cors() {

        // Allow from any origin
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
            // you want to allow, and if so:
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }

        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                // may also be using PUT, PATCH, HEAD etc
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

            exit(0);
        }

    }

    public function login(Request $request)
    {
        $this->cors();

        $user = User::where('email', $request->input('email'))->first();

        if ($user)
        {

            if ($user->user_estado == 1 )
            {
                $credenciales = $request->only('email', 'password');

                if (Auth::attempt($credenciales))
                {

                    $usersf = Array("Err" => 0, "Msg" => "Datos Correctos", "User"=>Array(
                        "user_id" => $user->user_id,
                        "user_nombre" => $user->user_nombre,
                        "user_apellido" => $user->user_apellido,
                        "user_cargo" => $user->user_cargo,
                        "user_estado" => $user->user_estado,
                        "user_empresa_id"=> $user->empresa_id,
                        "user_rol_id"=> $user->rol_id
                        ));

                    $Users= User::findOrFail($user->user_id);
                    $Users->user_firebase = $request->input('firebase');
                    $Users->update();


                } else {
                    //credenciales incorrectas

                    $usersf = Array("Err" => 1, "Msg" => "Datos ingresados no son válidos");
                }

            } else {

                $usersf = Array("Err" => 2, "Msg" => "Usuario inactivo");

            }
        }

        else{
            $usersf = Array("Err" => 1, "Msg" => "Email no válido");

        }

        return response()->json($usersf);

    }

    public function CambiarPosicion(Request $request){

        $this->cors();

        $vins = $request->input('vin');
        $patio = $request->input('patio_id');
        $bloque = $request->input('bloque_id');
        $posicion =  explode("_", $request->input('posicion'));

        if(count($posicion)!=2){
            $usersf = Array("Err" => 1, "Msg" => "Posición es requerido");
            return response()->json($usersf);
            exit;
        }

        $Vin = Vin::where('vin_codigo','=',$vins)
            ->first();

        if($Vin){

            $UbicPatio_ = UbicPatio::where('bloque_id','=', $bloque)
                ->where('ubic_patio_fila','=', $posicion[0])
                ->where('ubic_patio_columna','=', $posicion[1])
                ->first();

            if ($UbicPatio_){
                if($UbicPatio_->ubic_patio_ocupada){
                    $usersf = Array("Err" => 1, "Msg" => "Esta posición esta ocupada");
                }else{
                    try {
                        DB::beginTransaction();
                        $UbicPatio = UbicPatio::where('vin_id','=', $Vin->vin_id)->first();
                        $ubicPatioOrigen = null;

                        if($UbicPatio){
                            $ubicPatioOrigen = $UbicPatio;
                            $UbicPatio->ubic_patio_ocupada = false;
                            $UbicPatio->vin_id = null;
                            $UbicPatio->update();
                        }

                        $UbicPatios = UbicPatio::findOrFail($UbicPatio_->ubic_patio_id);
                        $UbicPatios->ubic_patio_ocupada = true;
                        $UbicPatios->vin_id = $Vin->vin_id;
                        $UbicPatios->update();

                        $itemlist =self::ListVIN($request);

                        $itemlistData = json_decode($itemlist->content(),true);

                        // Guardar histórico de la asignación de la campaña
                        $fecha = date('Y-m-d');
                        $user = User::find($request->user_id);

                        if($ubicPatioOrigen){
                            $bloque_origen = $ubicPatioOrigen->bloque_id;
                        } else {
                            $bloque_origen = null;
                        }

                        if($bloque_origen != null){
                            $bloqueOrigen = Bloque::find($bloque_origen);
                            $bloqueDestino = Bloque::find($bloque);

                            $patioOrigenNombre = $bloqueOrigen->onePatio->patio_nombre;
                            $patioDestinoNombre = $bloqueDestino->onePatio->patio_nombre;

                            DB::insert('INSERT INTO historico_vins
                                (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                                origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                                [
                                    $Vin->vin_id,
                                    $Vin->vin_estado_inventario_id,
                                    $fecha,
                                    $user->user_id,
                                    $bloque_origen,
                                    $bloque,
                                    $user->empresa_id,
                                    "Cambio de ubicación en patio",
                                    "Patio: $patioOrigenNombre. Bloque: $bloqueOrigen->bloque_nombre. Fila: $ubicPatioOrigen->ubic_patio_fila. Columna: $ubicPatioOrigen->ubic_patio_columna.",
                                    "Patio: $patioDestinoNombre. Bloque: $bloqueDestino->bloque_nombre. Fila: $UbicPatios->ubic_patio_fila. Columna: $UbicPatios->ubic_patio_columna.",
                                ]
                            );
                        } else {
                            $bloqueDestino = Bloque::find($bloque);
                            $patioDestinoNombre = $bloqueDestino->onePatio->patio_nombre;

                            DB::insert('INSERT INTO historico_vins
                                (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                                origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                                [
                                    $Vin->vin_id,
                                    $Vin->vin_estado_inventario_id,
                                    $fecha,
                                    $user->user_id,
                                    $bloque_origen,
                                    $bloque,
                                    $user->empresa_id,
                                    "Primera asignación de ubicación del VIN en el patio.",
                                    "VIN recién ingresado a patio.",
                                    "Patio: $patioDestinoNombre. Bloque: $bloqueDestino->bloque_nombre. Fila: $UbicPatios->ubic_patio_fila. Columna: $UbicPatios->ubic_patio_columna.",
                                ]
                            );
                        }

                        $usersf = Array("Err" => 0, "Msg" => "Cambio Exitoso", "itemlistData"=>$itemlistData['items']);

                        DB::commit();
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        $usersf = Array("Err" => 1, "Msg" => "Error actualizando datos.");
                    }
                }
            }else{
                $usersf = Array("Err" => 1, "Msg" => "La ubicacion en el bloque no esta creado");
            }
        }else{
            $usersf = Array("Err" => 1, "Msg" => "Vin obligatorio");
        }

        return response()->json($usersf);
    }

    public function Lists(Request $request) {
        $this->cors();

        $Vin =DB::table('tareas')
            ->join('tipo_tareas', 'tipo_tareas.tipo_tarea_id','=', 'tareas.tipo_tarea_id')
            ->join('tipo_destinos', 'tipo_destinos.tipo_destino_id','=', 'tareas.tipo_destino_id')
            ->join('vins', 'tareas.vin_id','=', 'vins.vin_id')
            ->join("marcas", "marcas.marca_id","=","vins.vin_marca")
            ->join('vin_estado_inventarios','vin_estado_inventarios.vin_estado_inventario_id','=', 'vins.vin_estado_inventario_id')
            ->leftJoin('ubic_patios', 'ubic_patios.vin_id', '=', 'vins.vin_id')

            ->select('vins.vin_estado_inventario_id as vin_estado_inventario_id','tarea_prioridad','tarea_id','tipo_tarea_descripcion as ico','vins.vin_codigo as vin','vins.vin_modelo as modelo','marca_nombre as marca', 'vins.created_at as fecha'
                ,'vin_estado_inventario_desc as estado','tipo_destino_descripcion as destino','vins.vin_color as color','ubic_patios.ubic_patio_fila', 'ubic_patios.ubic_patio_columna','ubic_patios.bloque_id'
                 )
            ->where('tareas.user_id',$request->user_id)
            ->where('tareas.tarea_finalizada',false)
            ->where('tareas.deleted_at',null)
            ->orderBy('vins.updated_at','desc')
            ->get();


        $bloques =DB::table('bloques')
            ->select('bloque_id', 'bloque_nombre', 'bloque_filas', 'bloque_columnas')
            ->where('bloques.deleted_at','=',null)
            ->orderBy('bloque_nombre', 'asc')
            ->get();


        foreach($Vin as $Vins){

            $dias = (strtotime($Vins->fecha)-strtotime(date('Y-m-d H:i:s')))/86400;
            $dias = abs($dias); $dias = floor($dias);

            //[0 => 'Baja', '1' => 'Media', '2' => 'Alta', '3' => 'Urgente']

            if($Vins->tarea_prioridad==0){
                $Vins->bandera = "green";
            }elseif($Vins->tarea_prioridad==1){
                $Vins->bandera = "yellow";
            }elseif($Vins->tarea_prioridad==2){
                $Vins->bandera = "blue";
            }else{
                $Vins->bandera = "red";
            }


            foreach($bloques as $bloq){

                if($bloq->bloque_id == $Vins->bloque_id) $Vins->bloque_nombre = $bloq->bloque_nombre;

            }

            $Vins->check ="false";
        }

        return response()->json(Array("Err"=>0,"listData"=>$Vin));

    }

    public function ListBloques(Request $request, $patio_id){
        $this->cors();
        if(empty($patio_id)){
            $resul = Array("Err" => 1, "Msg" => "Patio obligatorio");
        }else{
            $bloques =DB::table('bloques')
                ->select('bloque_id', 'bloque_nombre', 'bloque_filas', 'bloque_columnas')
                ->where('patio_id','=',$patio_id)
                ->where('bloques.deleted_at','=',null)
                ->orderBy('bloque_nombre', 'asc')
                ->get();

            $resul = Array("Err"=>0,"Bloques"=>$bloques);
        }

        return response()->json($resul);
    }

    public function ListVIN(Request $request)
    {
        $this->cors();

        $vins_id = $request->vin;


        if(empty($vins_id)){
            $usersf = Array("Err" => 1, "Msg" => "Vin obligatorio");
        } else {
            $bloques=null;
            $patios = Patio::select('patio_id','patio_nombre')->get();

            $Vin =DB::table('vins')
                ->join("marcas", "marcas.marca_id","=","vins.vin_marca")
                ->join('vin_estado_inventarios','vin_estado_inventarios.vin_estado_inventario_id','=', 'vins.vin_estado_inventario_id')
                ->leftJoin('ubic_patios', 'ubic_patios.vin_id', '=', 'vins.vin_id' )
                ->select('vins.vin_id as vin_id','vins.vin_codigo as vin','vins.vin_modelo as modelo','marca_nombre as marca', 'vins.user_id', 'vins.created_at as fecha',
                    'vin_estado_inventario_desc as estado', 'vins.vin_color as color','vins.vin_estado_inventario_id as vin_estado_inventario_id',
                    'ubic_patios.ubic_patio_fila', 'ubic_patios.ubic_patio_columna','ubic_patios.bloque_id','vin_predespacho','vin_bloqueado_entrega');

            if(strlen($vins_id)==6){
                $Vin->where('vins.vin_codigo', 'like', '%'.$vins_id);
                $Vin->orWhere('vins.vin_patente', '=', $vins_id);
            } else {
                $Vin->where('vins.vin_codigo', '=', $vins_id);
                $Vin->orWhere('vins.vin_patente', '=', $vins_id);
            }

            $vin = $Vin->get();

            if(count($vin)>0){
                foreach ($vin as $vins) {
                    $tarea = DB::table('tareas')
                        ->join('tipo_destinos', 'tipo_destinos.tipo_destino_id', '=', 'tareas.tipo_destino_id')
                        ->select('tipo_destino_descripcion as destino')
                        ->where('tareas.vin_id', $vins->vin_id)
                        ->get();

                    $vins->destino = (count($tarea) > 0) ? $tarea[0]->destino : '';

                    $_patio = DB::table('bloques')
                        ->join('patios', 'patios.patio_id', '=', 'bloques.patio_id')
                        ->select('bloques.patio_id', 'bloque_nombre', 'patio_nombre')
                        ->where('bloque_id', '=', $vins->bloque_id)
                        ->where('bloques.deleted_at', '=', null)
                        ->get();

                    $ubicados = DB::table('ubic_patios')
                        ->join("vins", "ubic_patios.vin_id", "=", "vins.vin_id")
                        ->join("marcas", "marcas.marca_id", "=", "vins.vin_marca")
                        ->join("bloques", "bloques.bloque_id", "=", "ubic_patios.bloque_id")
                        ->join("vin_estado_inventarios", "vin_estado_inventarios.vin_estado_inventario_id", "=", "vins.vin_estado_inventario_id")
                        ->select('vins.vin_id as vin_id', 'ubic_patio_columna', 'ubic_patio_fila', "vin_codigo", "marca_nombre as vin_marca", "ubic_patios.updated_at as vin_fec_ingreso", "vins.vin_estado_inventario_id as vin_estado_inventario_id", "bloques.bloque_id as bloque_id", "vin_estado_inventario_desc", 'patio_id')
                        ->get();

                    if (count($_patio) == 0) {
                        $vins->patio_id = null;
                    } else {
                        $vins->bloque_nombre = $_patio[0]->bloque_nombre;
                        $vins->patio_id = $_patio[0]->patio_id;
                        $vins->patio_nombre = $_patio[0]->patio_nombre;
                        $vins->posicion = $_patio[0]->bloque_nombre . " Fil:" . $vins->ubic_patio_fila . " Col:" . $vins->ubic_patio_columna;

                        $bloques = DB::table('bloques')
                            ->select('bloque_id', 'bloque_nombre', 'bloque_filas', 'bloque_columnas')
                            ->where('patio_id', '=', $_patio[0]->patio_id)
                            ->where('bloques.deleted_at', '=', null)
                            ->orderBy('bloque_nombre', 'asc')
                            ->get();

                        $ubicados = DB::table('ubic_patios')
                            ->join("vins", "ubic_patios.vin_id", "=", "vins.vin_id")
                            ->join("marcas", "marcas.marca_id", "=", "vins.vin_marca")
                            ->join("bloques", "bloques.bloque_id", "=", "ubic_patios.bloque_id")
                            ->join("vin_estado_inventarios", "vin_estado_inventarios.vin_estado_inventario_id", "=", "vins.vin_estado_inventario_id")
                            ->select('vins.vin_id as vin_id', 'ubic_patio_columna', 'ubic_patio_fila', "vin_codigo", "marca_nombre as vin_marca", "ubic_patios.updated_at as vin_fec_ingreso", "vins.vin_estado_inventario_id as vin_estado_inventario_id", "bloques.bloque_id as bloque_id", "vin_estado_inventario_desc", "vins.vin_predespacho as vin_predespacho")
                            ->where('patio_id', '=', $_patio[0]->patio_id)
                            ->get();

                    }

                    $vins->HabilitadoInspeccion = true;
                    $vins->HabilitadoCambio = true;
                    $vins->HabilitadoArribo = true;
                    $vins->HabilitadoEntregarVeh = false;

                    if ($vins->estado == "Anunciado") {
                        $vins->HabilitadoInspeccion = false;
                        $vins->HabilitadoCambio = false;
                    }

                    if ($vins->estado == "Arribado") {
                        $vins->HabilitadoArribo = false;
                        $vins->HabilitadoCambio = false;
                    }

                    if ($vins->estado == "Tránsito") {
                        $vins->HabilitadoInspeccion = false;
                        $vins->HabilitadoCambio = false;
                        $vins->HabilitadoArribo = false;
                    }

                    if ($vins->estado == "En Patio" || $vins->estado == "Disponible para la venta") {
                        $vins->HabilitadoArribo = false;
                    }

                    if ($vins->estado == "Agendado para entrega") {
                        $vins->HabilitadoInspeccion = false;
                        $vins->HabilitadoCambio = false;
                        $vins->HabilitadoArribo = false;
                    }

                    if ($vins->estado == "No disponible para la venta") {
                        $vins->HabilitadoArribo = false;
                    }

                    if ($vins->estado == "Suprimido") {
                        $vins->HabilitadoInspeccion = false;
                        $vins->HabilitadoCambio = false;
                        $vins->HabilitadoArribo = false;
                    }

                    if (($vins->vin_predespacho == true) && ($vins->vin_bloqueado_entrega == false)) {
                        $vins->HabilitadoEntregarVeh = true;
                    } else {
                        $vins->HabilitadoEntregarVeh = false;
                    }

                    if ($vins->estado == "Entregado") {
                        $vins->HabilitadoInspeccion = false;
                        $vins->HabilitadoCambio = false;
                        $vins->HabilitadoArribo = false;
                        $vins->HabilitadoEntregarVeh = false;
                    }

                    $propietario = User::join('empresas', 'users.empresa_id', 'empresas.empresa_id')
                        ->where('user_id', $vins->user_id)
                        ->select('empresa_razon_social')
                        ->value('empresa_razon_social');

                }

                $usersf = Array("Err"=>0,"items"=>$vin, "propietario"=>$propietario, "patios"=>$patios, "bloques"=>$bloques, "ubicados"=>$ubicados);
            } else {
                $usersf = Array("Err" => 1, "Msg" => "No se encuentra el Vin");
            }
        }

        return response()->json($usersf);

    }

    public function TareaFinalizada(Request $request, $tarea_id){
        $this->cors();

        $Tarea =DB::table('tareas')->select('tareas.*');
        $Tarea->where('tareas.tarea_id', '=', $tarea_id);
        $Tarea=$Tarea->first();

        if($Tarea){
            try {
                DB::beginTransaction();
                $Tareas = Tarea::findOrFail($Tarea->tarea_id);
                $Tareas->tarea_finalizada = true;
                $Tareas->update();

                $Vin = Vin::where('vin_id','=',$Tareas->vin_id)->first();

                $vins = $Vin->vin_codigo;

                $request->user_id = $Tarea->user_id;

                $itemlist = self::Lists($request);

                $itemlistData = json_decode($itemlist->content(),true);

                // Guardar histórico de la asignación de la campaña
                $fecha = date('Y-m-d');
                $user = User::find($request->user_id);

                $ubicPatio = UbicPatio::where('vin_id', $Vin->vin_id)->first();

                if($ubicPatio){
                    $bloque_id = $ubicPatio->bloque_id;
                } else {
                    $bloque_id = null;
                }

                $tipo_tarea = DB::table("tipo_tareas")
                    ->where('tipo_tarea_id', $Tarea->tipo_tarea_id)
                    ->first();

                $desc_tarea = $tipo_tarea->tipo_tarea_descripcion;

                if($bloque_id != null){
                    $bloqueOrigen = Bloque::find($bloque_id);
                    $ubicPatio = UbicPatio::where('vin_id','=', $Vin->vin_id)->first();

                    DB::insert('INSERT INTO historico_vins
                        (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                        origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                        [
                            $Vin->vin_id,
                            $Vin->vin_estado_inventario_id,
                            $fecha,
                            $user->user_id,
                            $bloque_id,
                            $bloque_id,
                            $user->empresa_id,
                            "Tarea finalizada: " . $desc_tarea,
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
                            $Vin->vin_id,
                            $Vin->vin_estado_inventario_id,
                            $fecha,
                            $user->user_id,
                            $bloque_id,
                            $bloque_id,
                            $user->empresa_id,
                            "Tarea finalizada: " . $desc_tarea,
                            "Ubicación fuera de bloque para realización de la tarea.",
                            "Preparado para ser asignado a nuevo estado."
                        ]
                    );
                }

                DB::commit();

                $usersf = Array("Err" => 0, "Msg" => "Cambio Exitoso", "itemlistData"=>$itemlistData['listData'], "vins"=>$vins);
            } catch (\Throwable $th) {
                DB::rollBack();
                $usersf = Array("Err" => 1, "Msg" => "Error finalizando tarea. Fallo en actualización de datos.");
            }
        }else{
            $usersf = Array("Err" => 1, "Msg" => "Tarea obligatorio");
        }

        return response()->json($usersf);

    }

    public function DarArribo(Request $request){
        $this->cors();

        $vins_codigo = $request->vin;

        $Vin = DB::table('vins')->select('vins.*');

        if(strlen($vins_codigo)==6){
            $Vin->where('vins.vin_codigo', 'like', '%'.$vins_codigo);
        }else{
            $Vin->where('vins.vin_codigo', '=', $vins_codigo);
        }

        $Vin = $Vin->first();

        if($Vin){
            try {
                DB::beginTransaction();

                $Vin_= Vin::findOrFail($Vin->vin_id);

                // El VIN debe estar en estado anunciado para poder darle arribo, de lo contrario, lo impedirá.
                if ($Vin_->vin_estado_inventario_id != 1){
                    DB::rollBack();
                    $usersf = Array("Err" => 1, "Msg" => "Error: Estado incorrecto. El VIN debe estar en estado 'Anunciado'");
                }

                $Vin_->vin_estado_inventario_id = 2;
                $Vin_->vin_fec_ingreso = date('Y-m-d');
                $Vin_->update();


                // Guardar historial del cambio
                $fecha = date('Y-m-d');
                $user = User::find($request->user_id);

                DB::insert('INSERT INTO historico_vins
                    (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                    origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                    [
                        $Vin->vin_id,
                        2,
                        $fecha,
                        $user->user_id,
                        null,
                        null,
                        $user->empresa_id,
                        "VIN Arribado.",
                        "Origen Externo: Llegada a patio.",
                        "Patio: BLoque y Ubicación por asignar."
                    ]
                );

                $itemlist =self::ListVIN($request);

                $itemlistData = json_decode($itemlist->content(),true);

                $usersf = Array("Err" => 0, "Msg" => "Cambio Exitoso", "itemlistData"=>$itemlistData['items']);

                DB::commit();
            }catch (\Exception $e) {
                DB::rollBack();
                $usersf = Array("Err" => 1, "Msg" => "Error actualizando datos para dar arribo al VIN");
            }

        }else{
            $usersf = Array("Err" => 1, "Msg" => "Vin obligatorio");
        }

        return response()->json($usersf);

    }

    public function CargaInicialInspeccionar(Request $request){
        $this->cors();

        $vins_codigo = $request->vin;

        $Vin = Vin::join('users','users.user_id','vins.user_id')->select('vins.*','empresa_id');

        if(strlen($vins_codigo)==6){
            $Vin->where('vins.vin_codigo', 'like', '%'.$vins_codigo);
        }else{
            $Vin->where('vins.vin_codigo', '=', $vins_codigo);
        }

        $Vin = $Vin->first();

        if($Vin){

            $tipoDanos = DB::table('tipo_danos')
                ->select('tipo_dano_id', 'tipo_dano_descripcion')
                ->get();

            $gravedades = DB::table('gravedades')
                ->select('gravedad_id', 'gravedad_descripcion')
                ->get();

            $subAreas = DB::table('pieza_sub_areas')
                ->select('pieza_sub_area_id', 'pieza_sub_area_desc')
                ->get();

            $piezaCategorias = DB::table('categoria_piezas')
                ->select('categoria_pieza_id', 'categoria_pieza_desc')
                ->get();

            $piezaSubCategorias = DB::table('subcategoria_piezas')
                ->select('subcategoria_pieza_id', 'subcategoria_pieza_desc', 'categoria_pieza_id')
                ->get();

            $piezas = DB::table('piezas')
                ->select('pieza_id', 'pieza_descripcion','subcategoria_pieza_id')
                ->get();

            $inspecciones = DB::table('inspecciones')
                ->select('cliente_id', 'inspeccion_fecha', 'responsable_id')
                ->where('vin_id', '=', $Vin->vin_id)
                ->latest()->first();

            $danos = DB::table('inspecciones')
                ->select(DB::raw("count(vin_id) AS can_danos"))
                ->where('vin_id', '=', $Vin->vin_id)
                ->where('inspeccion_dano',"=",true)
                ->groupBy('vin_id')
                ->get();

            $users = User::select(DB::raw("CONCAT(user_nombre,' ', user_apellido) AS user_nombres"), 'user_id')
                ->orderBy('user_id')
                ->pluck('user_nombres', 'user_id')
                ->all();

            $empresa = Empresa::select(DB::raw("CONCAT(empresa_razon_social,' ') AS empresa"), 'empresa_id')
                ->orderBy('empresa_id')
                ->pluck('empresa', 'empresa_id')
                ->all();




            if($inspecciones) {
                $inspecciones->responsable = $users[$inspecciones->responsable_id];

                $inspecciones->inspeccion_fecha = date('d/m/Y', strtotime($inspecciones->inspeccion_fecha));

               if(array_key_exists($Vin->empresa_id, $empresa)){   //cambio de posicion del condicional
                     $inspecciones->cliente = $empresa[$Vin->empresa_id];
                    }else
                    $inspecciones->cliente="";


            }else{
                $inspecciones = Array();
            }

            $usersf = Array("Err" => 0, "Msg" => "Cambio Exitoso",
                "piezaCategorias"=>$piezaCategorias,
                "piezaSubCategorias"=>$piezaSubCategorias,
                "piezas"=>$piezas,
                "tipoDanos"=>$tipoDanos,
                "subAreas"=>$subAreas,
                "inspecciones"=>$inspecciones,
                "danos"=>((count($danos)>0)?$danos[0]->can_danos:0)


                );

        }else{
            $usersf = Array("Err" => 1, "Msg" => "Vin obligatorio");
        }

        return response()->json($usersf);

    }

    public function InpeccionarSinDano(Request $request){

        $this->cors();

        $vins_id = $request->vin;

        $Vin = Vin::where('vin_codigo', $vins_id)->first();

        $estado_previo = $Vin->vin_estado_inventario_id;
        $estado_nuevo = 4; // Estado En Patio

        if($Vin){
            try {
                DB::beginTransaction();

                $cliente_id = $request->input('user_id');

                $inspeccion = new Inspeccion();
                $inspeccion->inspeccion_fecha = date('Y-m-d');
                $inspeccion->responsable_id = $request->input('user_id');
                $inspeccion->vin_id = $Vin->vin_id;
                $inspeccion->cliente_id = $cliente_id;
                $inspeccion->inspeccion_dano = false;
                $inspeccion->vin_estado_inventario_id = $Vin->vin_estado_inventario_id;
                //$inspeccion->vin_sub_estado_inventario_id = $datosInspeccion['vin_sub_estado_inventario_id'];

                if($inspeccion->save()){

                    $Vin_= Vin::findOrFail($Vin->vin_id);
                    $Vin_->vin_estado_inventario_id = $estado_nuevo;
                    $Vin_->update();

                    $itemlist = self::ListVIN($request);

                    $itemlistData = json_decode($itemlist->content(),true);

                    // Guardar historial del cambio
                    if($estado_previo == 4 || $estado_previo == 5 || $estado_previo == 6){
                        $ubic_patio = UbicPatio::where('vin_id', $Vin->vin_id)->first();
                        if($ubic_patio){
                            $bloque_id = $ubic_patio->bloque_id;
                        } else {
                            $bloque_id = null;
                        }
                    } else {
                        $bloque_id = null;
                    }

                    $user = User::find($request->user_id);

                    if($bloque_id != null){
                        $bloqueOrigen = Bloque::find($bloque_id);
                        $ubicPatio = UbicPatio::where('vin_id', $Vin->vin_id)->first();

                        DB::insert('INSERT INTO historico_vins
                            (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                            origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                            [
                                $Vin->vin_id,
                                $estado_nuevo,
                                $inspeccion->inspeccion_fecha,
                                $user->user_id,
                                $bloque_id,
                                $bloque_id,
                                $user->empresa_id,
                                "VIN Inspeccionado Sin Daño.",
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
                                $Vin->vin_id,
                                $estado_nuevo,
                                $inspeccion->inspeccion_fecha,
                                $user->user_id,
                                $bloque_id,
                                $bloque_id,
                                $user->empresa_id,
                                "VIN Inspeccionado Sin Daño.",
                                "Vin sin ubicación (fuera de bloque) para realizar inspección.",
                                "Inspeccionado y preparado para ser asignado a nueva ubicación y estado."
                            ]
                        );
                    }

                    DB::commit();

                    $usersf = Array("Err" => 0, "Msg" => "Registrado Exitoso",  "itemlistData"=>$itemlistData['items']);
                }else{
                    DB::rollBack();
                    $usersf = Array("Err" => 0, "Msg" => "Error al registrar");
                }
            } catch (\Throwable $th) {
                 DB::rollBack();
                 $usersf = Array("Err" => 1, "Msg" => "Error inesperado al registrar datos.");
                //  return back()->with('error', 'Error inesperado al registrar datos.');
            }
        }else{
            $usersf = Array("Err" => 1, "Msg" => "Vin obligatorio");
        }

        return response()->json($usersf);
    }

    public function InpeccionarConDano(Request $request){

        $this->cors();

        $vins = $request->input('vin');
        $user_id = $request->input('user_id');
        $pieza_id = $request->input('pieza_id');
        $tipo_dano_id = $request->input('tipo_dano_id');
        $gravedad_id = $request->input('gravedad_id');
        $pieza_sub_area_id = $request->input('pieza_sub_area_id');
        $dano_pieza_observaciones = $request->input('dano_pieza_observaciones');

        $foto_coord_lon = $request->input('foto_coord_lon');
        $foto_coord_lat = $request->input('foto_coord_lat');

        $Vin =Vin::where('vin_codigo','=', $vins)
            ->first();

        $estado_previo = $Vin->vin_estado_inventario_id;
        $estado_nuevo = 6; // Estado No Disponible Para la Venta

        if($Vin){
            //try {
                DB::beginTransaction();

                $cliente_id = $user_id;

                $inspeccion = new Inspeccion();
                $inspeccion->inspeccion_fecha = date('Y-m-d');
                $inspeccion->responsable_id = (int)$request->input('user_id');
                $inspeccion->vin_id = $Vin->vin_id;
                $inspeccion->cliente_id = $cliente_id;
                $inspeccion->inspeccion_dano = true;
                $inspeccion->vin_estado_inventario_id = $Vin->vin_estado_inventario_id;
                //$inspeccion->vin_sub_estado_inventario_id = $datosInspeccion['vin_sub_estado_inventario_id'];

                if($inspeccion->save()){

                    $Vin_= Vin::findOrFail($Vin->vin_id);
                    $Vin_->vin_estado_inventario_id = 6;
                    $Vin_->update();

                    $datosDanoPieza = $request->input('dano_pieza');
                    $danoPieza = new DanoPieza();
                    $danoPieza->pieza_id = $pieza_id;
                    $danoPieza->tipo_dano_id = $tipo_dano_id;
                    $danoPieza->gravedad_id = $gravedad_id;
                    $danoPieza->pieza_sub_area_id = $pieza_sub_area_id;
                    $danoPieza->dano_pieza_observaciones = $dano_pieza_observaciones;
                    $danoPieza->inspeccion_id = $inspeccion->inspeccion_id;

                    $danoPieza->save();

                    $foto = new Foto();
                    $foto->foto_fecha = date('Y-m-d');
                    $foto->foto_descripcion = "Inspección con daño";
                    $foto->foto_ubic_archivo = "fotos/";
                    $foto->foto_coord_lat = $foto_coord_lat;
                    $foto->foto_coord_lon = $foto_coord_lon;
                    $foto->dano_pieza_id = $danoPieza->dano_pieza_id;
                    $foto->save();

                    $fotoArchivo = $request->file('file');
                    $extensionFoto = $fotoArchivo->extension();
                    $path = $fotoArchivo->storeAs(
                        'fotos',
                        "foto_de_inspeccion".'-'.$request->user_id.'-'.date('Y-m-d').'-'.\Carbon\Carbon::now()->timestamp.'.'.$extensionFoto
                    );

                    //Creamos una instancia de la libreria instalada
                    $image = \Image::make($fotoArchivo);

                    //Ruta donde queremos guardar las imagenes
                    $path2 = storage_path().'/app/thumbnails/';

                    // Cambiar de tamaño
                    $image->resize(240,200);

                    // Guardar
                    $image->save($path2.'thumb_'.$fotoArchivo->getClientOriginalName());

                    //Guardamos nombre y nombreOriginal en la BD
                    $thumbnail = new Thumbnail();
                    $thumbnail->thumbnail_nombre = "Foto de Inspección";
                    $thumbnail->thumbnail_imagen = $fotoArchivo->getClientOriginalName();
                    $thumbnail->foto_id = $foto->foto_id;

                    $thumbnail->save();

                    $foto1 = Foto::find($foto->foto_id);
                    $foto1->foto_ubic_archivo = $path;

                    $foto1->save();

                    $itemlist = self::ListVIN($request);
                    $itemlistData = json_decode($itemlist->content(),true);

                    // Guardar historial del cambio
                    if($estado_previo == 4 || $estado_previo == 5 || $estado_previo == 6){
                        $ubic_patio = UbicPatio::where('vin_id', $Vin->vin_id)->first();
                        if($ubic_patio){
                            $bloque_id = $ubic_patio->bloque_id;
                        } else {
                            $bloque_id = null;
                        }
                    } else {
                        $bloque_id = null;
                    }

                    $user = User::find($request->user_id);

                    if($bloque_id != null){
                        $bloqueOrigen = Bloque::find($bloque_id);
                        $ubicPatio = UbicPatio::where('vin_id', $Vin->vin_id)->first();

                        DB::insert('INSERT INTO historico_vins
                            (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                            origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                            [
                                $Vin->vin_id,
                                $estado_nuevo,
                                $inspeccion->inspeccion_fecha,
                                $user->user_id,
                                $bloque_id,
                                $bloque_id,
                                $user->empresa_id,
                                "VIN Inspeccionado Con Daño.",
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
                                $Vin->vin_id,
                                $estado_nuevo,
                                $inspeccion->inspeccion_fecha,
                                $user->user_id,
                                $bloque_id,
                                $bloque_id,
                                $user->empresa_id,
                                "VIN Inspeccionado Con Daño.",
                                "Vin sin ubicación (fuera de bloque) para realizar inspección.",
                                "Inspeccionado y preparado para ser asignado a nueva ubicación y estado."

                            ]
                        );
                    }

                    DB::commit();

                    $usersf = Array("Err" => 0, "Msg" => "Registrado Exitoso",  "itemlistData"=>$itemlistData['items'], 'foto'=>$fotoArchivo->getClientOriginalName());


                }else{
                    DB::rollBack();
                    $usersf = Array("Err" => 1, "Msg" => "Error al registrar");
                }
          /*  } catch (\Throwable $th) {
                DB::rollBack();
                $usersf = Array("Err" => 1, "Msg" => "Error inesperado al registrar datos");
           }*/

        }else{
            $usersf = Array("Err" => 1, "Msg" => "Vin obligatorio");
        }

        return response()->json($usersf);
    }

    public function Entregar(Request $request){

        $this->cors();

        $vins = $request->input('vin');
        $user_id = $request->input('user_id');
        $tipo_id = $request->input('tipo_id'); //1->tierra 2->camión
        $rut = $request->input('rut');
        $nombres = $request->input('nombres');
        $apellidos = $request->input('apellidos');
        $patente = $request->input('patente');
        $file_rut = $request->file('file_rut');
        $obs = $request->input('observaciones');

        $vins = explode(",",$vins);

        foreach ($vins as $vi) {

            $Vin = Vin::where('vin_codigo', '=', $vi)
                ->first();

            $user = User::find($user_id);

            if ($Vin) {

                $estado_previo = $Vin->vin_estado_inventario_id;
                $estado_nuevo = 8; // Entregado

                DB::beginTransaction();

                $trans = User::where('user_rut', '=', $rut)->first();

                if ($trans) {
                    $transportista = User::findOrFail($trans->user_id);
                } else {
                    $emailExists = User::where('email', $request->correo)
                        ->exists();

                    if ($emailExists) {
                        $usersf = Array("Err" => 1, "Msg" => "Email ya existente. Por favor introducir otro.");
                        return response()->json($usersf);
                    } else {
                        $transportista = new User();
                        $transportista->user_nombre = $nombres;
                        $transportista->user_apellido = $apellidos;
                        $transportista->user_rut = $rut;
                        $transportista->user_cargo = "";
                        $transportista->user_estado = 1;
                        $transportista->email = $request->correo;
                        $transportista->password = "";
                        $transportista->rol_id = 7;
                        $transportista->user_telefono = "";
                        $transportista->empresa_id = $user->empresa_id;
                        $transportista->save();
                    }
                }

                $entregar = new Entrega();
                $entregar->entrega_fecha = date('Y-m-d');
                $entregar->responsable_id = (int)$user_id;
                //$entregar->responsable_id = Auth::user()->user_id;
                $entregar->vin_id = $Vin->vin_id;
                $entregar->tipo_id = $tipo_id;
                $entregar->user_id = $transportista->user_id;
                $entregar->foto_rut = "";
                $entregar->foto_patente = $patente;
                $entregar->observaciones = $obs;

                if (!empty($file_rut)) {

                    $fotoArchivo = $request->file('file_rut');
                    $extensionFoto = $fotoArchivo->extension();
                    $filename = "foto_de_rut" . '-' . $user_id . '-' . date('Y-m-d') . '-' . \Carbon\Carbon::now()->timestamp . '.' . $extensionFoto;
                    $path = storage_path() . '/app/entrega_fotos/';
                    // $path = $fotoArchivo->storeAs(
                    //     'fotos_entrega',
                    //     "foto_de_rut" . '-' . $user_id . '-' . date('Y-m-d') . '-' . \Carbon\Carbon::now()->timestamp . '.' . $extensionFoto
                    // );

                    //Creamos una instancia de la libreria instalada
                    $image = \Image::make($fotoArchivo);
                    // Guardar
                    $image->save($path . $filename);
                    // $image->save($path);

                    $entregar->foto_rut = $path . $filename;

                }


                if ($entregar->save()) {
                    // Actualizar estado de inventario del VIN a entregar
                    $Vin_ = Vin::findOrFail($Vin->vin_id);
                    // $Vin_->vin_predespacho = false; // Nota: No descomentar. Esto se controla en otras funciones.
                    $Vin_->vin_estado_inventario_id = $estado_nuevo;
                    if (!$Vin_->update()) {
                        DB::rollBack();
                        $usersf = Array("Err" => 1, "Msg" => "Error al cambiando estado de VIN.");
                    }

                    // Anular el registro de Predespacho.
                    $predespacho = Predespacho::where('vin_id', $Vin->vin_id)->first();

                    if ($predespacho) {
                        if (!$predespacho->delete()) {
                            DB::rollBack();
                            $usersf = Array("Err" => 1, "Msg" => "Error cerrando predespacho.");
                        }
                    }

                    // Buscar si existe la asociación del VIN con alguna guía
                    $guiaVin = GuiaVin::where('vin_id', $Vin->vin_id)->first();

                    if ($guiaVin) {
                        $guiaAnteriorVin = Guia::find($guiaVin->guia_id);

                        if ($guiaAnteriorVin) {
                            $guiaNumero = $guiaAnteriorVin->guia_numero;
                        } else {
                            $guiaNumero = "N/A";
                        }


                        $emp = Vin::join('users', 'vins.user_id', '=', 'users.user_id')
                            ->join('empresas', 'users.empresa_id', '=', 'empresas.empresa_id')
                            ->where('vins.vin_id', $Vin->vin_id)
                            ->select('empresas.empresa_id', 'empresas.empresa_razon_social')
                            ->first();

                        $date = Carbon::now();
                        $fecha = $date->toDateString();
                        $hora = $date->toTimeString();

                        // Guardar historial del cambio
                        DB::insert('INSERT INTO historico_vins
                        (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                        origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                            [
                                $Vin->vin_id,
                                $Vin->vin_estado_inventario_id,
                                $fecha,
                                // Auth::user()->user_id,
                                $user->user_id,
                                null,
                                null,
                                $emp->empresa_id,
                                "Eliminada relación en base de datos del VIN a entregar con la Guía. Responsable: " . $user->user_nombre . " " . $user->user_apellido . ". Hora: " . $hora,
                                "Guía Nro: " . $guiaNumero . ", Empresa: " . $emp->empresa_razon_social . ".",
                                "VIN " . $Vin->vin_codigo . "."
                            ]
                        );

                        if (!$guiaVin->delete()) {
                            DB::rollback();
                            $usersf = Array("Err" => 1, "Msg" => 'Error eliminando relación con su guía para el VIN seleccionado: ' . $vin->vin_codigo . '. Por favor informe al administrador antes de continuar.');
                        } else {
                            $usersf = Array("Err" => 0, "Msg" => "Relación VIN-Guía desasociada con éxito.");
                        }
                    }

                   // $itemlist = self::ListVIN($request);

                   // $itemlistData = json_decode($itemlist->content(), true);

                    $ubic_patio = UbicPatio::where('vin_id', $Vin->vin_id)->first();

                    $ubicPatioVieja = null;

                    $bloque_id = null;

                    // Guardar historial del cambio
                    if ($estado_previo == 4 || $estado_previo == 5 || $estado_previo == 6) {
                        if ($ubic_patio) {
                            $bloque_id = $ubic_patio->bloque_id;

                            // Liberar la posición ocupada del patio.
                            $ubicPatioVieja = $ubic_patio;
                            $ubic_patio->ubic_patio_ocupada = false;
                            $ubic_patio->vin_id = null;
                            $ubic_patio->update();
                        } else {
                            $bloque_id = null;
                        }
                    }

                    if ($bloque_id != null) {
                        $bloqueOrigen = Bloque::find($bloque_id);

                        DB::insert('INSERT INTO historico_vins
                        (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                        origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                            [
                                $Vin->vin_id,
                                $estado_nuevo,
                                $entregar->entrega_fecha,
                                $user->user_id,
                                $bloque_id,
                                $bloque_id,
                                $user->empresa_id,
                                "VIN Entregado: " . $obs,
                                "Patio: " . $bloqueOrigen->onePatio->patio_nombre . ". Bloque: $bloqueOrigen->bloque_nombre. Fila: $ubicPatioVieja->ubic_patio_fila. Columna: $ubicPatioVieja->ubic_patio_columna.",
                                "VIN: " . $Vin->vin_codigo . " entregado.",
                            ]
                        );
                    } else {
                        DB::insert('INSERT INTO historico_vins
                        (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                        origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                            [
                                $Vin->vin_id,
                                $estado_nuevo,
                                $entregar->entrega_fecha,
                                $user->user_id,
                                $bloque_id,
                                $bloque_id,
                                $user->empresa_id,
                                "VIN Entregado: " . $obs,
                                "VIN sin ubicación (fuera de bloque) para realizar Entrega.",
                                "VIN Entregado."
                            ]
                        );
                    }

                    DB::commit();

                    $usersf = Array("Err" => 0, "Msg" => "Registrado Exitoso");
                } else {
                    DB::rollBack();
                    $usersf = Array("Err" => 1, "Msg" => "Error al registrar");
                }
            } else {
                $usersf = Array("Err" => 1, "Msg" => "Vin obligatorio");
            }

        }

        return response()->json($usersf);
    }

    public function BuscarTransportista(Request $request){
        $this->cors();

        $user_rut = $request->user_rut;

        if(empty($user_rut)){
            $usersf = Array("Err" => 1, "Msg" => "Users obligatorio");
        }else {

            $user = DB::table('users')->where('user_rut', '=', $user_rut)->select('users.*');

            $user = $user->first();

            if ($user) {

                $usersf = Array(
                    "Err" => 0,
                    "Msg" => "Datos Exitoso",
                    "users" => $user
                );


            } else {
                $usersf = Array("Err" => 1, "Msg" => "Usuario  no se encuentra");
            }

        }

        return response()->json($usersf);

    }

    public function Badge(Request $request) {
        $this->cors();

        $Vin =DB::table('tareas')
            ->select('tarea_id')
            ->where([
             ['user_id', '=', $request->user_id],
                ['tarea_finalizada', '=', false],
            ])->get();

        echo json_encode(Array("Err"=>0,"badgeData"=>Array("list"=>count($Vin), "car"=>0, "boat"=>0)));
    }

    public function ListMarcas(Request $request) {
        $this->cors();

        $Marcas =DB::table('marcas')
            ->select('marca_id','marca_nombre', 'marca_codigo')
            ->orderBy('marca_nombre')
            ->get();

        foreach ($Marcas as $marca){
            $marca->marca_nombre = strtoupper($marca->marca_nombre);
        }

        echo json_encode(Array("Err"=>0,"marcas"=>$Marcas));
    }

    public function ListarRutas(Request $request){
        $this->cors();

        $user_id = $request->user_conductor;

        if (empty($user_id)) {
            return response()->json(Array("Err" => 1, "Msg" => "Users obligatorio"));
        } else {
            $Conductors = DB::table('conductors')->select('conductor_id','user_id');
            $Conductors->where('user_id', '=', $user_id);
            $Conductors = $Conductors->first();



            if ($Conductors) {
                $Tour = DB::table('tours')->select('tour_id');
                $Tour->where('conductor_id',  $Conductors->user_id)
                    ->where('tour_finalizado', false);
                $Tour = $Tour->first();

                if ($Tour) {
                    $rutas = DB::table('rutas')
                        ->select('rutas.ruta_id as ruta_id', 'guia_numero', 'ruta_origen', 'ruta_destino', 'ruta_iniciada', 'ruta_finalizada', 'ruta_fecha_en_origen as tiempo')
                        ->join("ruta_guias", "ruta_guias.ruta_id", "=", "rutas.ruta_id")
                        ->join("guias", "guias.guia_id", "=", "ruta_guias.guia_id")
                        ->where('tour_id', $Tour->tour_id)
                        ->get();

                    if(count($rutas)>0)

                        return response()->json(Array("Err" => 0, "Msg" => "Exitoso", "List" => $rutas));

                    else

                        return response()->json(Array("Err" => 1, "Msg" => "No existen Rutas asociada al conductor"));

                } else {
                    return response()->json(Array("Err" => 1, "Msg" => "No existen Viajes asociado al conductor"));
                }
            } else {
                return response()->json(Array("Err" => 1, "Msg" => "El usuario no esta registrado como Conductor"));
            }

        }
    }

    public function DetallesListarRutas(Request $request){
        $this->cors();

        $ruta_id = $request->ruta_id;

                    $rutas = DB::table('rutas')
                        ->select('rutas.ruta_id as ruta_id', 'guia_numero', 'vin_codigo')
                        ->join("ruta_guias", "ruta_guias.ruta_id", "=", "rutas.ruta_id")
                        ->join("guias", "guias.guia_id", "=", "ruta_guias.guia_id")
                        ->join("guia_vins", "guia_vins.guia_id", "=", "guias.guia_id")
                        ->join("vins", "vins.vin_id", "=", "guia_vins.vin_id")
                        ->where('rutas.ruta_id', $ruta_id)
                        ->get();

                    if(count($rutas)>0)

                        return response()->json(Array("Err" => 0, "Msg" => "Exitoso", "List" => $rutas));

                    else

                        return response()->json(Array("Err" => 1, "Msg" => "No existen Rutas asociada al conductor"));




    }



    public function  InicioRutas(Request $request){
        $this->cors();

        $user_id = $request->input('user_id');
        $ruta_id =  $request->ruta_id;
        $origen = $request->input('origen');

        $rutas = DB::table('rutas')
            ->select('ruta_id')
            ->where('ruta_id', $ruta_id)
            ->get();

        if(count($rutas)>0){
            $Rutas= Ruta::findOrFail($ruta_id);
            $Rutas->ruta_en_origen = $origen;
            $Rutas->ruta_iniciada = true;
            $Rutas->ruta_fecha_en_origen = date('Y-m-d H:i:s', now()->timestamp);
            $Rutas->update();
            return response()->json(Array("Err" => 0, "Msg" => "Actualización Satisfactoria"));
        }else{
            return response()->json(Array("Err" => 1, "Msg" => "No se encuentra id de la ruta"));
        }
    }

    public function  FinRutas(Request $request){
        $this->cors();

        $user_id = $request->input('user_id');
        $ruta_id =  $request->ruta_id;

        $rutas = DB::table('rutas')
            ->select('ruta_id')
            ->where('ruta_id', $ruta_id)
            ->get();

        if(count($rutas)>0){
            $Rutas= Ruta::findOrFail($ruta_id);
            $Rutas->ruta_finalizada = true;
            $Rutas->update();
            return response()->json(Array("Err" => 0, "Msg" => "Actualización Satisfactoria"));
        }else{
            return response()->json(Array("Err" => 1, "Msg" => "No se encuentra id de la ruta"));
        }
    }

    public function GuardarLocalizacion(Request $request){
        $this->cors();

        $user_id = $request->input('user_id');
        $ruta_id =  $request->ruta_id;
        $coord_lon = $request->input('coord_lon');
        $coord_lat = $request->input('coord_lat');

        $rutas = DB::table('rutas')
            ->select('ruta_id')
            ->where('ruta_id', $ruta_id)
            ->get();

        if(count($rutas)>0){
            $ub = new Ubicacion();
            $ub->ubicacion_latitud = $coord_lat;
            $ub->ubicacion_longitud = $coord_lon;
            $ub->ruta_id = $ruta_id;
            $ub->fecha_ubicacion_actual = date('Y-m-d H:i:s', now()->timestamp);
            $ub->save();
            return response()->json(Array("Err" => 0, "Msg" => "Ubicacion Guardada"));
        }else{
            return response()->json(Array("Err" => 1, "Msg" => "No se encuentra id de la ruta"));
        }

    }

    public function RegistrarVehiculoNN(Request $request)
    {
        $this->cors();

        $vin_codigo = $request->input('vin');
        $user_id = $request->input('user_id');
        $patente = $request->input('patente');
        $modelo = $request->input('modelo');
        $color = $request->input('color');
        $marca = $request->input('marca');
        $motor = $request->input('motor');
        $procedencia = $request->input('procedencia');
        $destino = $request->input('destino');

        if(empty($vin_codigo) || $vin_codigo=="undefined"){
            return response()->json(Array("Err" => 1, "Msg" => "Código VIN Obligatorio"));
        }
        if(empty($user_id) || $user_id=="undefined"){
            return response()->json(Array("Err" => 1, "Msg" => "User Obligatorio"));
        }
        if(empty($patente) || $patente=="undefined"){
            return response()->json(Array("Err" => 1, "Msg" => "Patente Obligatorio"));
        }
        if(empty($modelo) || $modelo=="undefined"){
            return response()->json(Array("Err" => 1, "Msg" => "Módelo Obligatorio"));
        }
        if(empty($color) || $color=="undefined"){
            return response()->json(Array("Err" => 1, "Msg" => "Color Obligatorio"));
        }
        if(empty($marca) || $marca=="undefined"){
            return response()->json(Array("Err" => 1, "Msg" => "Marca Obligatorio"));
        }
        if(empty($motor) || $motor=="undefined"){
            return response()->json(Array("Err" => 1, "Msg" => "Marca Obligatorio"));
        }
        if(empty($procedencia) || $procedencia=="undefined"){
            return response()->json(Array("Err" => 1, "Msg" => "Procedencia Obligatorio"));
        }
        if(empty($destino) || $destino=="undefined"){
            return response()->json(Array("Err" => 1, "Msg" => "Destino Obligatorio"));
        }

        if (VehiculoNN::where('vin_codigo', $vin_codigo)->exists()){
            return response()->json(Array("Err" => 1, "Msg" => "Código VIN Ya esta registrado"));
         } else {
            $vinNN = new VehiculoNN();
            $vinNN->vin_codigo = $vin_codigo;
            $vinNN->vin_patente = $patente;
            $vinNN->vin_modelo = $modelo;
            $vinNN->vin_marca = $marca;
            $vinNN->vin_color = $color;
            $vinNN->vin_motor = $motor;
            $vinNN->vin_procedencia = $procedencia;
            $vinNN->vin_destino = $destino;
            $vinNN->vin_fec_ingreso = date('Y-m-d', now()->timestamp);
            $vinNN->user_id = $user_id;

            if ($vinNN->save()) {
                return response()->json(Array("Err" => 0, "Msg" => "Registro Satisfactorio", "vin_id"=>$vinNN->vin_id));
            }else{
                return response()->json(Array("Err" => 1, "Msg" => "Error al registrar"));
            }
        }
    }


    public function RegistrarImagenNN(Request $request)
    {
        $this->cors();

        $vin_codigo = $request->input('vin');
        $user_id = $request->input('user_id');
        $observaciones = $request->input('observaciones');
        $foto_coord_lon = $request->input('foto_coord_lon');
        $foto_coord_lat = $request->input('foto_coord_lat');



        if(empty($vin_codigo) || $vin_codigo=="undefined"){
            return response()->json(Array("Err" => 1, "Msg" => "Código VIN Obligatorio"));
        }
        if(empty($user_id) || $user_id=="undefined"){
            return response()->json(Array("Err" => 1, "Msg" => "User Obligatorio"));
        }

        if(empty($observaciones) || $observaciones=="undefined"){
            return response()->json(Array("Err" => 1, "Msg" => "Descripcion Obligatoria"));
        }

        if (VehiculoNN::where('vin_codigo', $vin_codigo)->exists()){

            $fotoArchivo = $request->file('file');
            $extensionFoto = $fotoArchivo->extension();
            $path = $fotoArchivo->storeAs(
                'fotos',
                "foto_nn_".'_'.$user_id.'_'.$vin_codigo."_".date('Y-m-d').'_'.\Carbon\Carbon::now()->timestamp.'.'.$extensionFoto
            );
           // $image = \Image::make($fotoArchivo);

            $foto = new FotoNN();
            $foto->foto_fecha = date('Y-m-d');
            $foto->foto_descripcion = $observaciones;
            $foto->foto_ubic_archivo = "fotos/";
            $foto->foto_coord_lat = $foto_coord_lat;
            $foto->foto_coord_lon = $foto_coord_lon;
            $foto->foto_ubic_archivo = $path;
            $foto->vin_codigo = $vin_codigo;
            $foto->save();

            if ($foto->save()) {
                return response()->json(Array("Err" => 0, "Msg" => "Foto Registrada"));
            }else{
                return response()->json(Array("Err" => 1, "Msg" => "Error al registrar"));
            }
        }  else {

            return response()->json(Array("Err" => 1, "Msg" => "Código VIN No se encuentra"));

        }
    }
}
