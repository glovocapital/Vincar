<?php

namespace App\Http\Controllers;

use App\HistoricoTour;
use App\Tour;
use App\Http\Middleware\CheckSession;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;

class HistoricoTourController extends Controller
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
        if(isset($request->tour_id)){
            $tour = Tour::where('tour_id', $request->tour_id)
                ->first();

            if($tour != null){
                $historicoTour = HistoricoTour::where('tour_id', $tour->tour_id);
            }

            return view('historico_tour.index', compact('tour', 'historicoTour'));
        }
    }

    /**
     * Devuelve el histórico para id_tour
     *
     * @return \Illuminate\Http\Response
     */
    public function historicoTour($id_tour)
    {
        try {
            $tour_id = Crypt::decrypt($id_tour);
        } catch (DecryptException $e) {
            abort(404);
        }

        $tour = Tour::find($tour_id);

        if($tour != null){
            $registros = HistoricoTour::where('tour_id', $tour->tour_id)
                ->get();

            $historicoTour = [];
            $i = 0;

            foreach($registros as $registro){
                $historicoTour[$i]['tour'] = $registro->oneTour->tour_id;

                if ($registro->ruta_id) {
                    $historicoTour[$i]['ruta'] = 'Origen: '. $registro->oneRuta->ruta_origen . '. Destino: ' . $registro->oneRuta->ruta_destino;

                    if ($registro->vin_id) {
                        $historicoTour[$i]['vin'] = $registro->oneVin->vin_codigo;
                    } else {
                        $historicoTour[$i]['vin'] = '';
                    }
                    $historicoTour[$i]['cliente'] = $registro->oneCliente->empresa_razon_social;
                    $historicoTour[$i]['numero_guia'] = $registro->oneRuta->rutaGuia->oneGuia->guia_numero;

                    if ($registro->oneRuta->ruta_finalizada){
                        $historicoTour[$i]['condicion_entrega'] = $registro->historico_tour_condicion_entrega;
                    } else {
                        $historicoTour[$i]['condicion_entrega'] =  '';
                    }
                } else {
                    $historicoTour[$i]['ruta'] = '';
                    $historicoTour[$i]['vin'] = '';
                    $historicoTour[$i]['cliente'] = '';
                    $historicoTour[$i]['condicion_entrega'] =  '';
                    $historicoTour[$i]['numero_guia'] = '';
                }

                $historicoTour[$i]['fecha_inicio'] = $registro->historico_tour_fecha_inicio;
                $historicoTour[$i]['proveedor'] = $registro->oneProveedor->empresa_razon_social;
                $historicoTour[$i]['descripcion'] = $registro->historico_tour_descripcion;

                $i++;
            }

            return response()->json([
                'success' => true,
                'message' => "Data histórica del tour disponible",
                'historico_tour' => $historicoTour
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => "Data histórica del tour no disponible",
            'historico_tour' => null
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
                $array_historico_vins[$i]['origen'] = $item->origen_texto;
                $array_historico_vins[$i]['destino'] = $item->destino_texto;

                $array_historico_vins[$i]['descripcion'] = $item->historico_vin_descripcion;
                $i++;
            }
        }

        return Excel::download(new HistoricoVinLoteExport($array_historico_vins), 'hitorico_de_vins.xlsx');
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
