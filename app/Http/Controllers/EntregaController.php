<?php

namespace App\Http\Controllers;

use App\Vin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Middleware\CheckSession;
use App\Predespacho;
use Illuminate\Support\Facades\Crypt;

class EntregaController extends Controller
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
            ->join('users','vins.user_id','=','users.user_id')
            ->join('empresas','users.empresa_id','=','empresas.empresa_id')
            ->join('ubic_patios', 'vins.vin_id', '=', 'ubic_patios.vin_id')
            ->join('bloques', 'ubic_patios.bloque_id', '=', 'bloques.bloque_id')
            ->join('patios', 'bloques.patio_id', '=', 'patios.patio_id')
            ->leftjoin('predespachos', 'predespachos.vin_id', '=', 'vins.vin_id')
            ->where('vin_estado_inventario_id','!=', 8);

        if($request->from){
            $date = Carbon::createFromFormat('Y-m-d', $request->from);

            $queryAgendados->whereDate('vin_fecha_agendado', '>=', $date);
        }
        
        if($request->to){
            $date = Carbon::createFromFormat('Y-m-d', $request->to);
            
            $queryAgendados->whereDate('vin_fecha_agendado', '<=', $date);
        }
        
        $vin_agendados = $queryAgendados->orderBy('vin_fecha_agendado')
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

        if($request->from_entregado){
            $date = Carbon::createFromFormat('Y-m-d', $request->from_entregado);

            $queryAgendados->whereDate('vin_fecha_entrega', '>=', $date);
        }
        
        if($request->to_entregado){
            $date = Carbon::createFromFormat('Y-m-d', $request->to_entregado);
            
            $queryAgendados->whereDate('vin_fecha_entrega', '<=', $date);
        }

        return view('entrega.index', compact( 'vin_entregados', 'vin_entregados_dia','vin_agendados'));
    }

    public function infoPredespacho($id_vin)
    {
        $vin_id = Crypt::decrypt($id_vin);

        $predespacho = Predespacho::where('vin_id', $vin_id)
            ->orderBy('created_at', 'DESC')
            ->limit(1)
            ->first();

        if ($predespacho) {
            return view('entrega.info_predespacho', compact('predespacho'));
        } else {
            flash('Error. Información no encontrada.')->error();
            return back();
        }
    }
}
