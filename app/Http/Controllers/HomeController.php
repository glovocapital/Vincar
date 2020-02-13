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


        $datos = Array(

            'Total_Recibido'=>Array("Cantidad"=>5, "Porcentaje"=>34),
            'Total_Salidas'=>Array("Cantidad"=>55, "Porcentaje"=>78),
            'Unidades_Danadas'=>Array("Cantidad"=>15, "Porcentaje"=>94),

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
