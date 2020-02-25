<?php

namespace App\Http\Controllers;
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


        $Vin =DB::table('vins')
            ->select('vins.*')
            ->where('vin_codigo','=',$vins)
            ->first();

        if($Vin){

            $UbicPatio_ = UbicPatio::where('bloque_id','=', $bloque)
                ->where('ubic_patio_fila','=', $posicion[0])
                ->where('ubic_patio_columna','=', $posicion[1])
                ->get();

            if(count($UbicPatio_)>0){
                if($UbicPatio_[0]->ubic_patio_ocupada){
                    $usersf = Array("Err" => 1, "Msg" => "Esta posición esta ocupada");
                }else{
                    $UbicPatio = UbicPatio::where('vin_id','=', $Vin->vin_id)->get();
                    if(count($UbicPatio)>0){
                        $UbicPatios = UbicPatio::findOrFail($UbicPatio[0]->ubic_patio_id);
                        $UbicPatios->ubic_patio_ocupada = false;
                        $UbicPatios->vin_id = null;
                        $UbicPatios->update();
                    }

                    $UbicPatios = UbicPatio::findOrFail($UbicPatio_[0]->ubic_patio_id);
                    $UbicPatios->ubic_patio_ocupada = true;
                    $UbicPatios->vin_id = $Vin->vin_id;
                    $UbicPatios->update();

                    $itemlist =self::ListVIN($request, $Vin->vin_codigo);

                    $itemlistData = json_decode($itemlist->content(),true);



                    $usersf = Array("Err" => 0, "Msg" => "Cambio Exitoso", "itemlistData"=>$itemlistData['items']);

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
            ->join('vin_estado_inventarios','vin_estado_inventarios.vin_estado_inventario_id','=', 'vins.vin_estado_inventario_id')
            ->leftJoin('ubic_patios', 'ubic_patios.vin_id', '=', 'vins.vin_id')

            ->select('vins.vin_estado_inventario_id as vin_estado_inventario_id','tarea_prioridad','tarea_id','tipo_tarea_descripcion as ico','vins.vin_codigo as vin','vins.vin_modelo as modelo','vins.vin_marca as marca', 'vins.created_at as fecha'
                ,'vin_estado_inventario_desc as estado','tipo_destino_descripcion as destino','vins.vin_color as color','ubic_patios.ubic_patio_fila', 'ubic_patios.ubic_patio_columna','ubic_patios.bloque_id'
                 )
            ->where('tareas.user_id',$request->user_id)
            ->where('tareas.tarea_finalizada',false)
            ->where('deleted_at',null)
            ->orderBy('vins.updated_at','desc')
            ->get();


        $bloques =DB::table('bloques')
            ->select('bloque_id', 'bloque_nombre', 'bloque_filas', 'bloque_columnas')
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
                ->get();

            $resul = Array("Err"=>0,"Bloques"=>$bloques);
        }

        return response()->json($resul);
    }

    public function ListVIN(Request $request, $vins_id)
    {
        $this->cors();

        if(empty($vins_id)){
            $usersf = Array("Err" => 1, "Msg" => "Vin obligatorio");
        }else{

            $bloques=null;
            $patios = Patio::select('patio_id','patio_nombre')->get();

            $Vin =DB::table('vins')
                ->join('vin_estado_inventarios','vin_estado_inventarios.vin_estado_inventario_id','=', 'vins.vin_estado_inventario_id')
                ->leftJoin('ubic_patios', 'ubic_patios.vin_id', '=', 'vins.vin_id' )
                ->select('vins.vin_id as vin_id','vins.vin_codigo as vin','vins.vin_modelo as modelo','vins.vin_marca as marca', 'vins.created_at as fecha'
                    ,'vin_estado_inventario_desc as estado', 'vins.vin_color as color','vins.vin_estado_inventario_id as vin_estado_inventario_id',
                    'ubic_patios.ubic_patio_fila', 'ubic_patios.ubic_patio_columna','ubic_patios.bloque_id' );

            if(strlen($vins_id)==6){
                $Vin->where('vins.vin_codigo', 'like', '%'.$vins_id);
            }else{
                $Vin->where('vins.vin_codigo', '=', $vins_id);
            }

            $vin = $Vin->get();

            if(count($vin)){

                $tarea =DB::table('tareas')
                    ->join('tipo_destinos', 'tipo_destinos.tipo_destino_id','=', 'tareas.tipo_destino_id')
                    ->select('tipo_destino_descripcion as destino')
                    ->where('tareas.vin_id',$vin[0]->vin_id)
                    ->get();
                $vin[0]->destino = $tarea[0]->destino;

               $_patio =DB::table('bloques')
                   ->join('patios', 'patios.patio_id','=','bloques.patio_id')
                    ->select('bloques.patio_id', 'bloque_nombre','patio_nombre')
                    ->where('bloque_id','=',$vin[0]->bloque_id)
                    ->get();

                $ubicados = DB::table('ubic_patios')
                    ->join("vins", "ubic_patios.vin_id","=","vins.vin_id")
                    ->join("bloques", "bloques.bloque_id","=","ubic_patios.bloque_id")
                    ->join("vin_estado_inventarios", "vin_estado_inventarios.vin_estado_inventario_id","=","vins.vin_estado_inventario_id")
                    ->select('vins.vin_id as vin_id','ubic_patio_columna','ubic_patio_fila', "vin_codigo", "vin_marca","ubic_patios.updated_at as vin_fec_ingreso","vins.vin_estado_inventario_id as vin_estado_inventario_id","bloques.bloque_id as bloque_id","vin_estado_inventario_desc", 'patio_id')
                    ->get();

               if(count($_patio)==0) $vin[0]->patio_id=null;
               else {
                   $vin[0]->bloque_nombre=$_patio[0]->bloque_nombre;
                   $vin[0]->patio_id=$_patio[0]->patio_id;
                   $vin[0]->patio_nombre=$_patio[0]->patio_nombre;
                   $vin[0]->posicion=$_patio[0]->bloque_nombre." Fil:".$vin[0]->ubic_patio_fila." Col:".$vin[0]->ubic_patio_columna;

                   $bloques =DB::table('bloques')
                       ->select('bloque_id', 'bloque_nombre', 'bloque_filas', 'bloque_columnas')
                       ->where('patio_id','=',$_patio[0]->patio_id)
                       ->get();

                   $ubicados = DB::table('ubic_patios')
                       ->join("vins", "ubic_patios.vin_id","=","vins.vin_id")
                       ->join("bloques", "bloques.bloque_id","=","ubic_patios.bloque_id")
                       ->join("vin_estado_inventarios", "vin_estado_inventarios.vin_estado_inventario_id","=","vins.vin_estado_inventario_id")
                       ->select('vins.vin_id as vin_id','ubic_patio_columna','ubic_patio_fila', "vin_codigo", "vin_marca","ubic_patios.updated_at as vin_fec_ingreso","vins.vin_estado_inventario_id as vin_estado_inventario_id","bloques.bloque_id as bloque_id","vin_estado_inventario_desc")
                       ->where('patio_id','=',$_patio[0]->patio_id)
                       ->get();


               }

                $vin[0]->activo = true;

             //  if($vin[0]->estado=="Arribado")  $vin[0]->activo = false;

                $usersf = Array("Err"=>0,"items"=>$vin[0], "patios"=>$patios, "bloques"=>$bloques, "ubicados"=>$ubicados);
            }else{
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
            $Tareas= Tarea::findOrFail($Tarea->tarea_id);
            $Tareas->tarea_finalizada = true;
            $Tareas->update();

            $request->user_id = $Tarea->user_id;

            $itemlist =self::Lists($request);

          $itemlistData = json_decode($itemlist->content(),true);

            $usersf = Array("Err" => 0, "Msg" => "Cambio Exitoso", "itemlistData"=>$itemlistData['listData']);

        }else{
            $usersf = Array("Err" => 1, "Msg" => "Tarea obligatorio");
        }

        return response()->json($usersf);

    }

    public function DarArribo(Request $request, $vins_codigo){
        $this->cors();

        $Vin =DB::table('vins')->select('vins.*');

        if(strlen($vins_codigo)==6){
            $Vin->where('vins.vin_codigo', 'like', '%'.$vins_codigo);
        }else{
            $Vin->where('vins.vin_codigo', '=', $vins_codigo);
        }

        $Vin=$Vin->first();

        if($Vin){
            $Vin_= Vin::findOrFail($Vin->vin_id);
            $Vin_->vin_estado_inventario_id = 2;
            $Vin_->update();

            $itemlist =self::ListVIN($request, $Vin->vin_codigo);

            $itemlistData = json_decode($itemlist->content(),true);

            $usersf = Array("Err" => 0, "Msg" => "Cambio Exitoso", "itemlistData"=>$itemlistData['items']);

        }else{
            $usersf = Array("Err" => 1, "Msg" => "Vin obligatorio");
        }

        return response()->json($usersf);

    }

    public function CargaInicialInspeccionar(Request $request, $vins_codigo){
        $this->cors();

        $Vin =DB::table('vins')->select('vins.*');

        if(strlen($vins_codigo)==6){
            $Vin->where('vins.vin_codigo', 'like', '%'.$vins_codigo);
        }else{
            $Vin->where('vins.vin_codigo', '=', $vins_codigo);
        }

        $Vin=$Vin->first();

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

            if($inspecciones) {
                $inspecciones->responsable = $users[$inspecciones->responsable_id];
                $inspecciones->cliente = $users[$inspecciones->cliente_id];
                $inspecciones->inspeccion_fecha = date('d/m/Y', strtotime($inspecciones->inspeccion_fecha));
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

    public function InpeccionarSinDano(Request $request,  $vins_id){

        $this->cors();

        $Vin =DB::table('vins')
            ->select('vins.*')
            ->where('vin_codigo','=', $vins_id)
            ->first();

        if($Vin){

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
                $Vin_->vin_estado_inventario_id = 4;
                $Vin_->update();

                $itemlist =self::ListVIN($request, $Vin->vin_codigo);

                $itemlistData = json_decode($itemlist->content(),true);

                $usersf = Array("Err" => 0, "Msg" => "Registrado Exitoso",  "itemlistData"=>$itemlistData['items']);
            }else{
                $usersf = Array("Err" => 0, "Msg" => "Error al registrar");
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


        $target_path = "uploads/";

        $target_path = $target_path . basename( $_FILES['file']['name']);

        if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {

            $data = ['success' => true, 'message' => 'Upload and move success'];

        } else{

            $data = ['success' => false, 'message' => 'There was an error uploading the file, please try again!'];

        }

        $Vin =DB::table('vins')
            ->select('vins.*')
            ->where('vin_codigo','=', $vins)
            ->first();

        if($Vin){

            $cliente_id = $request->input('user_id');

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


                $itemlist =self::ListVIN($request, $Vin->vin_codigo);

                $itemlistData = json_decode($itemlist->content(),true);

                $usersf = Array("Err" => 0, "Msg" => "Registrado Exitoso",  "itemlistData"=>$itemlistData['items'], 'foto'=>$data);


            }else{
                $usersf = Array("Err" => 0, "Msg" => "Error al registrar");
            }


        }else{
            $usersf = Array("Err" => 1, "Msg" => "Vin obligatorio");
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




}
