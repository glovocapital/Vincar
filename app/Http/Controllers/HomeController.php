<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $lasthomework =DB::table('tareas')
            ->join('vins', 'vins.vin_id','=', 'tareas.vin_id')
            ->join('tipo_tareas', 'tipo_tareas.tipo_tarea_id','=', 'tareas.tipo_tarea_id')
            ->join('users', 'users.user_id','=', 'tareas.user_id')
            ->select('tarea_fecha_finalizacion','tipo_tarea_descripcion', 'user_nombre', 'user_apellido', 'vin_codigo')
            ->where('tarea_finalizada',true)
            ->orderBy('tarea_fecha_finalizacion','desc')
            ->get();

        $cantidad = $lasthomework->count();

        return view('home',compact('lasthomework','cantidad'));
    }

    public function dashboard()
    {


        $Total_Recibido = DB::table('vins')
            ->select(DB::raw("count(vin_id) AS recibido"))
            ->where('vin_estado_inventario_id',"=",2)
            ->get();

        $Unidades_Danadas = DB::table('vins')
            ->select(DB::raw("count(vin_id) AS danadas"))
            ->where('vin_estado_inventario_id',"=",6)
            ->where('vin_estado_inventario_id',"=",7)
            ->get();

        $Total_Salidas = DB::table('vins')
            ->select(DB::raw("count(vin_id) AS salidas"))
            ->where('vin_estado_inventario_id',"=",8)
            ->where('vin_estado_inventario_id',"=",3)
            ->get();

        $totales = $Total_Recibido[0]->recibido + $Unidades_Danadas[0]->danadas + $Total_Salidas[0]->salidas;


        $datos = Array(

            'Total_Recibido'=>Array("Cantidad"=>$Total_Recibido[0]->recibido, "Porcentaje"=>(($totales>0)?$Total_Recibido[0]->recibido*100/$totales:0)),
            'Total_Salidas'=>Array("Cantidad"=>$Total_Salidas[0]->salidas, "Porcentaje"=>(($totales>0)?$Total_Salidas[0]->salidas*100/$totales:0)),
            'Unidades_Danadas'=>Array("Cantidad"=>$Unidades_Danadas[0]->danadas, "Porcentaje"=>(($totales>0)?$Unidades_Danadas[0]->danadas*100/$totales:0)),

            'Tareas'=>35,
            'DyP'=>70,
            'Lavados'=>67,
            'Carga'=>78,

            'Total'=>234,
            'Pendiente'=>1256,
            'Gestionados'=>34,
            'Rechazados'=>9,

            'Ruta_dia'=>Array(
                Array("Ruta"=>"Ruta 1", "Latitud"=>40.712784, "Longitud"=> -40.712784),
                Array("Ruta"=>"Ruta 1", "Latitud"=>70.712784, "Longitud"=> -20.712784)
            )
        );
        return json_encode($datos);
    }
}
