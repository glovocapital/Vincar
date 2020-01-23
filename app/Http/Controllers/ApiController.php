<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Vin;
use App\UbicPatio;
use App\Patio;


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

                    $usersf = Array("Err" => 0, "Msg" => "Datos Correctos", Array(
                        "user_id" => $user->user_id,
                        "user_nombre" => $user->user_nombre,
                        "user_apellido" => $user->user_apellido,
                        "user_cargo" => $user->user_cargo,
                        "user_estado" => $user->user_estado));


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

                    $usersf = Array("Err" => 0, "Msg" => "Cambio Exitoso");

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

        $Vin =DB::table('vins')
             ->leftJoin('ubic_patios', 'ubic_patios.vin_id', '=', 'vins.vin_id' )
            ->join('vin_estado_inventarios','vin_estado_inventarios.vin_estado_inventario_id','=', 'vins.vin_estado_inventario_id')
            ->join('bloques', 'bloques.bloque_id','=', 'ubic_patios.bloque_id')
            ->select('vins.vin_codigo as vin','vins.vin_modelo as modelo','vins.vin_marca as marca', 'vins.created_at as fecha'
                ,'vin_estado_inventario_desc as estado',
                'ubic_patios.ubic_patio_fila', 'ubic_patios.ubic_patio_columna','bloque_nombre' )
            ->orderBy('vins.updated_at','desc')
            ->get();


        foreach($Vin as $Vins){


            $dias = (strtotime($Vins->fecha)-strtotime(date('Y-m-d H:i:s')))/86400;
            $dias = abs($dias); $dias = floor($dias);

            if($dias<30){
                $Vins->bandera = "green";
            }elseif($dias<60){
                $Vins->bandera = "yellow";
            }else{
                $Vins->bandera = "red";
            }


            $Vins->ico = "picking";
            $Vins->check ="false";
        }

      /*  $list=Array(
            Array("vin"=>"10101019010",
                "modelo"=>"MITSUBISHI",
                "marca"=>"MONTERO",
                "destino"=>"OFICINA",
                "posicion"=>"OFICINA010",
                "bandera"=>"red",
                "ico"=>"picking",
                "check"=>"true"),
         */



        echo json_encode(Array("Err"=>0,"listData"=>$Vin));

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
                    ,'vin_estado_inventario_desc as estado', 'vins.vin_color',
                    'ubic_patios.ubic_patio_fila', 'ubic_patios.ubic_patio_columna','ubic_patios.bloque_id' );

            if(strlen($vins_id)==6){
                $Vin->where('vins.vin_codigo', 'like', '%'.$vins_id);
            }else{
                $Vin->where('vins.vin_codigo', '=', $vins_id);
            }

            $vin = $Vin->get();

            if(count($vin)){

               $_patio =DB::table('bloques')
                   ->join('patios', 'patios.patio_id','=','bloques.patio_id')
                    ->select('bloques.patio_id', 'bloque_nombre','patio_nombre')
                    ->where('bloque_id','=',$vin[0]->bloque_id)
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


               }

                $usersf = Array("Err"=>0,"items"=>$vin[0], "patios"=>$patios, "bloques"=>$bloques);
            }else{
                $usersf = Array("Err" => 1, "Msg" => "No se encuentra el Vin");
            }
        }

        return response()->json($usersf);

    }

    public function Badge() {
        $this->cors();
        echo json_encode(Array("Err"=>0,"badgeData"=>Array("list"=>2, "car"=>3, "boat"=>2)));
    }


}
