<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
        $rol_desc=Auth::user()->oneRol->rol_desc;

        $empresa_id=Auth::user()->empresa_id;

        $lasthomework =DB::table('tareas')
            ->join('vins', 'vins.vin_id','=', 'tareas.vin_id')
            ->join('tipo_tareas', 'tipo_tareas.tipo_tarea_id','=', 'tareas.tipo_tarea_id')
            ->join('users', 'users.user_id','=', 'tareas.user_id')
            ->select('tarea_fecha_finalizacion','tipo_tarea_descripcion', 'user_nombre', 'user_apellido', 'vin_codigo')
            ->where('tarea_finalizada',true)
            ->orderBy('tarea_fecha_finalizacion','desc');

        $lasthomework = $lasthomework->get();

        $cantidad = $lasthomework->count();

        return view('home',compact('lasthomework','cantidad'));
    }

    public function dashboard()
    {

        $rol_desc=Auth::user()->oneRol->rol_desc;
        $empresa_id=Auth::user()->empresa_id;
        $user_id=Auth::user()->user_id;

        $Total_Recibido = DB::table('vins')
            ->select(DB::raw("count(vin_id) AS recibido"))
            ->where('vin_estado_inventario_id',"=",2);
        if($rol_desc=='Customer')
            $Total_Recibido = $Total_Recibido->where('user_id',"=",$user_id);

        $Total_Recibido = $Total_Recibido->get();

        $Unidades_Danadas = DB::table('vins')
            ->select(DB::raw("count(vin_id) AS danadas"))
            ->where('vin_estado_inventario_id',"=",6)
            ->where('vin_estado_inventario_id',"=",7);

        if($rol_desc=='Customer')
            $Unidades_Danadas = $Unidades_Danadas->where('user_id',"=",$user_id);

        $Unidades_Danadas =     $Unidades_Danadas->get();

        $Total_Salidas = DB::table('vins')
            ->select(DB::raw("count(vin_id) AS salidas"))
            ->where('vin_estado_inventario_id',"=",8)
            ->where('vin_estado_inventario_id',"=",3);

        if($rol_desc=='Customer')
            $Total_Salidas = $Total_Salidas->where('user_id',"=",$user_id);

        $Total_Salidas = $Total_Salidas->get();

        $totales = $Total_Recibido[0]->recibido + $Unidades_Danadas[0]->danadas + $Total_Salidas[0]->salidas;



        $leadtime = DB::table('tareas')
            ->join('tipo_tareas', 'tipo_tareas.tipo_tarea_id','=', 'tareas.tipo_tarea_id')
            ->select(DB::raw("count(tareas.tarea_id) AS cantTareas"),'tareas.tipo_tarea_id as tipo_tarea_id')
            ->where('tarea_finalizada',true)
            ->groupBy('tareas.tipo_tarea_id')
            ->get();

        $leadtime_total = DB::table('tareas')
            ->join('tipo_tareas', 'tipo_tareas.tipo_tarea_id','=', 'tareas.tipo_tarea_id')
            ->select(DB::raw("count(tareas.tarea_id) AS cantTareas"),'tareas.tipo_tarea_id as tipo_tarea_id')
            ->groupBy('tareas.tipo_tarea_id')
            ->get();



        $lts=Array(0,0,0,0,0,0);
        foreach ($leadtime_total as $ltt){
            foreach ($leadtime as $lt){
                if($lt->tipo_tarea_id==$ltt->tipo_tarea_id){
                    $lts[$lt->tipo_tarea_id] = $lt->canttareas * 100 / $ltt->canttareas;
                }

            }
        }




        $datos = Array(

            'Total_Recibido'=>Array("Cantidad"=>$Total_Recibido[0]->recibido, "Porcentaje"=>(($totales>0)?$Total_Recibido[0]->recibido*100/$totales:0)),
            'Total_Salidas'=>Array("Cantidad"=>$Total_Salidas[0]->salidas, "Porcentaje"=>(($totales>0)?$Total_Salidas[0]->salidas*100/$totales:0)),
            'Unidades_Danadas'=>Array("Cantidad"=>$Unidades_Danadas[0]->danadas, "Porcentaje"=>(($totales>0)?$Unidades_Danadas[0]->danadas*100/$totales:0)),

            'Tareas'=>$lts[1],
            'DyP'=>$lts[2],
            'Lavados'=>$lts[3],
            'Carga'=>$lts[4],

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
