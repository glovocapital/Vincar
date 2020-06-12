<?php

namespace App\Http\Controllers;

use App\Exports\HistoricoVinLoteExport;
use App\HistoricoVin;
use App\Http\Middleware\CheckSession;
use App\Vin;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;

class HistoricoVinController extends Controller
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
        if(isset($request->vin_numero)){
            $vin = Vin::where('vin_codigo', $request->vin_numero)
                ->orWhere('vin_patente', $request->vin_numero)
                ->first();


            if($vin != null){
                $historico_vin = HistoricoVin::where('vin_id', $vin->vin_id);
            }

            return view('historico_vin.index', compact('vin', 'historico_vin'));
        }
    }

    /**
     * Devuelve el histórico para id_vin
     * 
     * @return \Illuminate\Http\Response
     */
    public function historicoVin($id_vin)
    {
        try {
            $vin_id = Crypt::decrypt($id_vin);
        } catch (DecryptException $e) {
            abort(404);
        }
        
        $vin = Vin::find($vin_id);
        
        if($vin != null){
            $registros = HistoricoVin::where('vin_id', $vin->vin_id)
                ->get();

            $historico_vin = [];
            $i = 0;

            foreach($registros as $registro){
                $historico_vin[$i]['vin_codigo'] = $registro->oneVin->vin_codigo;
                $historico_vin[$i]['historico_estado'] = $registro->oneVinEstadoInventario();
                $historico_vin[$i]['historico_fecha'] = $registro->historico_vin_fecha;
                $historico_vin[$i]['responsable'] = $registro->oneResponsable->user_nombre . " " . $registro->oneResponsable->user_apellido;
                if(isset($registro->origen_id)){
                    $historico_vin[$i]['origen'] = $registro->oneOrigen->bloque_nombre;
                }else{
                    $historico_vin[$i]['origen'] = "";
                }
                if(isset($registro->destino_id)){
                    $historico_vin[$i]['destino'] = $registro->oneDestino->bloque_nombre;
                }else{
                    $historico_vin[$i]['destino'] = ""; 
                }
                $historico_vin[$i]['empresa'] = $registro->oneEmpresa->empresa_razon_social;
                $historico_vin[$i]['descripcion'] = $registro->historico_vin_descripcion;
                $i++;
            }

            return response()->json([
                'success' => true,
                'message' => "Data histórica del VIN disponible",
                'historico_vin' => $historico_vin
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => "Data histórica del VIN no disponible",
            'historico_vin' => null
        ]);
    }

    /**
     * Histórico de Vins por lotes
    */
    public function exportHistoricoVinLote(Request $request)
    {
        $array_historicos= [];

        foreach($request->vin_ids as $vin_id){
            $elemento = HistoricoVin::where('vin_id', $vin_id)->orderBy('historico_vin_id')->get();
            $vin_codigo = Vin::where('vin_id',$vin_id)->select('vin_codigo')->value('vin_codigo');
            array_push($array_historicos, [$vin_codigo, $elemento]);
        }
        
        $array_historico_vins = [];
        $i = 0;
    
        foreach($array_historicos as $historico_vin){
            foreach($historico_vin[1] as $item){
                $array_historico_vins[$i]['historico_vin_id'] = $item->historico_vin_id;
                $array_historico_vins[$i]['historico_vin_fecha'] = $item->historico_vin_fecha;
                $array_historico_vins[$i]['codigo'] = $historico_vin[0];
                $array_historico_vins[$i]['vin_id'] = $item->vin_id;
                $array_historico_vins[$i]['cliente'] = $item->oneEmpresa->empresa_razon_social;
                $array_historico_vins[$i]['estado'] = $item->oneVinEstadoInventario();
                $array_historico_vins[$i]['responsable'] = $item->oneResponsable->user_nombre . " " . $item->oneResponsable->user_apellido;
                
                if($item->origen_id != null){
                    $array_historico_vins[$i]['origen'] = $item->oneOrigen;
                } else {
                    $array_historico_vins[$i]['origen'] = $item->origen_texto;
                }

                if($item->destino_id != null){
                    $array_historico_vins[$i]['destino'] = $item->oneDestino;
                } else {
                    $array_historico_vins[$i]['destino'] = $item->destino_texto;
                }
                
                $array_historico_vins[$i]['descripcion'] = $item->historico_vin_descripcion;     
                $i++;
            }
        }
        
        return Excel::download(new HistoricoVinLoteExport($array_historico_vins), 'busqueda_vins.xlsx');
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\HistoricoVin  $historicoVin
     * @return \Illuminate\Http\Response
     */
    public function show(HistoricoVin $historicoVin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\HistoricoVin  $historicoVin
     * @return \Illuminate\Http\Response
     */
    public function edit(HistoricoVin $historicoVin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\HistoricoVin  $historicoVin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HistoricoVin $historicoVin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\HistoricoVin  $historicoVin
     * @return \Illuminate\Http\Response
     */
    public function destroy(HistoricoVin $historicoVin)
    {
        //
    }
}
