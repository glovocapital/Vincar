<?php

namespace App\Http\Controllers;

use App\Exports\VinsAgendadosExport;
use App\Vin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Middleware\CheckSession;
use App\Predespacho;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;

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
        $empresa_id = Auth::user()->empresa_id;
        $userRolId = Auth::user()->rol_id;

        /** Consultar VINs agendados para entrega */
        $queryAgendados = Vin::where('vin_predespacho', true)
            ->join('users','vins.user_id','=','users.user_id')
            ->join('empresas','users.empresa_id','=','empresas.empresa_id')
            ->join('ubic_patios', 'vins.vin_id', '=', 'ubic_patios.vin_id')
            ->join('bloques', 'ubic_patios.bloque_id', '=', 'bloques.bloque_id')
            ->join('patios', 'bloques.patio_id', '=', 'patios.patio_id')
            ->leftjoin('predespachos', 'predespachos.vin_id', '=', 'vins.vin_id')
            ->where('predespachos.deleted_at', null )
            ->where('vin_estado_inventario_id','!=', 8);


        // Si el rol del usuario es de cliente, entonces la consulta se filtra por la empresa del usuario.
        if($userRolId == 4){
            $queryAgendados->where('empresas.empresa_id',$empresa_id);
        }

        // Filtro de búsqueda por fecha desde
        if($request->from){
            $date = Carbon::createFromFormat('Y-m-d', $request->from);

            $queryAgendados->whereDate('vin_fecha_agendado', '>=', $date);
        }

        // Filtro de búsqueda por fecha hasta
        if($request->to){
            $date = Carbon::createFromFormat('Y-m-d', $request->to);
            
            $queryAgendados->whereDate('vin_fecha_agendado', '<=', $date);
        }
        
        $vin_agendados = $queryAgendados->orderBy('vin_fecha_agendado')
            ->get();

        $queryEntregadosDia = Vin::where('vin_estado_inventario_id', 8)
            ->join('users','vins.user_id','=','users.user_id')
            ->join('empresas','users.empresa_id','=','empresas.empresa_id')
            ->join('entregas','entregas.vin_id','=','vins.vin_id')
            ->where('vins.updated_at',  '>', Carbon::now()->subDays(1)->toDateTimeString());

        // Si el rol del usuario es de cliente, entonces la consulta se filtra por la empresa del usuario.
        if($userRolId == 4){
            $queryEntregadosDia->where('empresas.empresa_id',$empresa_id);
        }
        
        $vin_entregados_dia = $queryEntregadosDia->orderBy('vin_fecha_entrega')->get();

        $queryEntregados = Vin::where('vins.vin_estado_inventario_id', 8)
            ->where('historico_vins.vin_estado_inventario_id', 8)
            ->join('users','vins.user_id','=','users.user_id')
            ->join('empresas','users.empresa_id','=','empresas.empresa_id')
            ->join('entregas','entregas.vin_id','=','vins.vin_id')
            ->join('historico_vins', 'historico_vins.vin_id', 'vins.vin_id');

        // Si el rol del usuario es de cliente, entonces la consulta se filtra por la empresa del usuario.
        if($userRolId == 4){
            $queryEntregados->where('empresas.empresa_id',$empresa_id);
        }

        if (!$request->from_entregado && !$request->to_entregado) {
            $queryEntregados->where('entregas.entrega_fecha',  '>', Carbon::now()->subDays(1)->toDateTimeString());
        } else {
            // Filtro de búsqueda por fecha desde
            if($request->from_entregado){
                $date = Carbon::createFromFormat('Y-m-d', $request->from_entregado);

                $queryEntregados->whereDate('entrega_fecha', '>=', $date);
            }
            
            // Filtro de búsqueda por fecha hasta
            if($request->to_entregado){
                $date = Carbon::createFromFormat('Y-m-d', $request->to_entregado);
                
                $queryEntregados->whereDate('entrega_fecha', '<=', $date);
            }
        }

        

        $vin_entregados = $queryEntregados->orderBy('vin_fecha_entrega')->get();

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

    public function  agendadosExport(Request $request)
    {
        $array_vins = [];

        $empresa_id = Auth::user()->empresa_id;
        $userRolId = Auth::user()->rol_id;

        /** Consultar VINs agendados para entrega */
        $queryAgendados = Vin::join('users','vins.user_id','=','users.user_id')
            ->join('empresas','users.empresa_id','=','empresas.empresa_id')
            ->join('ubic_patios', 'vins.vin_id', '=', 'ubic_patios.vin_id')
            ->join('bloques', 'ubic_patios.bloque_id', '=', 'bloques.bloque_id')
            ->join('patios', 'bloques.patio_id', '=', 'patios.patio_id')
            ->leftjoin('predespachos', 'predespachos.vin_id', '=', 'vins.vin_id')
            ->where('vin_predespacho', true)
            ->where('vin_estado_inventario_id','!=', 8);


        // Si el rol del usuario es de cliente, entonces la consulta se filtra por la empresa del usuario.
        if($userRolId == 4){
            $queryAgendados->where('empresas.empresa_id',$empresa_id);
        }
        
        $vinsAgendados = $queryAgendados->orderBy('vin_fecha_agendado')
            ->get();

        foreach($vinsAgendados as $vinAgendado){
            if($vinAgendado){
                $vinAgendadoExport = [];

                $vinAgendadoExport['vin_codigo'] = $vinAgendado->vin_codigo;
                $vinAgendadoExport['vin_patente'] = $vinAgendado->vin_codigo;
                $vinAgendadoExport['vin_marca'] = strtoupper($vinAgendado->oneMarca->marca_nombre);
                $vinAgendadoExport['vin_color'] = $vinAgendado->vin_color;
                $vinAgendadoExport['vin_fecha_agendado'] = date( 'd-m-Y', strtotime($vinAgendado->vin_fecha_agendado));
                $vinAgendadoExport['empresa_razon_social'] = strtoupper($vinAgendado->empresa_razon_social);
                $vinAgendadoExport['patio_nombre'] = strtoupper($vinAgendado->patio_nombre);
                $vinAgendadoExport['ubicacion'] = "BLOQUE: $vinAgendado->bloque_nombre. FILA: $vinAgendado->ubic_patio_fila. COLUMNA: $vinAgendado->ubic_patio_columna.";

                if ($vinAgendado->tipo_agendamiento == 1) {
                    $vinAgendadoExport['tipo_agendamiento'] = "RETIRO";
                } else if ($vinAgendado->tipo_agendamiento == 1) {
                    $vinAgendadoExport['tipo_agendamiento'] = "TRASLADO";
                } else {
                    $vinAgendadoExport['tipo_agendamiento'] = '';
                }

                if ($vinAgendado->predespacho_origen) {
                    $vinAgendadoExport['desde'] = $vinAgendado->predespacho_origen;
                } else {
                    $vinAgendadoExport['desde'] = '';
                }

                if ($vinAgendado->predespacho_destino) {
                    $vinAgendadoExport['hacia'] = $vinAgendado->predespacho_destino;
                } else {
                    $vinAgendadoExport['hacia'] = '';
                }
            
                array_push($array_vins, $vinAgendadoExport);
            }

        }

        $data= json_decode( json_encode($array_vins), true);

        return Excel::download(new VinsAgendadosExport($data), 'lista_de_vins_agendados.xlsx');
    }
}
