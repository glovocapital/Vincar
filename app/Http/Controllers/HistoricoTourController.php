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
     * Histórico de Tours por lotes
    */
    // public function exportHistoricoTourLote(Request $request)
    // {
    //     $array_historicos= [];

    //     foreach($request->tour_ids as $tour_id){
    //         $elemento = HistoricoTour::where('tour_id', $tour_id)->orderBy('historico_tour_id')->get();
    //         array_push($array_historicos, [$tour_id, $elemento]);
    //     }

    //     $array_historico_tours = [];
    //     $i = 0;

    //     foreach($array_historicos as $historico_tour){
    //         foreach($historico_tour[1] as $item){
    //             $array_historico_tours[$i]['tour'] = $item->oneTour->tour_id;

    //             if ($item->ruta_id) {
    //                 $array_historico_tours[$i]['ruta'] = 'Origen: '. $item->oneRuta->ruta_origen . '. Destino: ' . $item->oneRuta->ruta_destino;

    //                 if ($item->vin_id) {
    //                     $array_historico_tours[$i]['vin'] = $item->oneVin->vin_codigo;
    //                 } else {
    //                     $array_historico_tours[$i]['vin'] = '';
    //                 }
    //                 $array_historico_tours[$i]['cliente'] = $item->oneCliente->empresa_razon_social;
    //                 $array_historico_tours[$i]['numero_guia'] = $item->oneRuta->rutaGuia->oneGuia->guia_numero;

    //                 if ($item->oneRuta->ruta_finalizada){
    //                     $array_historico_tours[$i]['condicion_entrega'] = $item->historico_tour_condicion_entrega;
    //                 } else {
    //                     $array_historico_tours[$i]['condicion_entrega'] =  '';
    //                 }
    //             } else {
    //                 $array_historico_tours[$i]['ruta'] = '';
    //                 $array_historico_tours[$i]['vin'] = '';
    //                 $array_historico_tours[$i]['cliente'] = '';
    //                 $array_historico_tours[$i]['condicion_entrega'] =  '';
    //                 $array_historico_tours[$i]['numero_guia'] = '';
    //             }

    //             $i++;
    //         }
    //     }

    //     return Excel::download(new HistoricoTourLoteExport($array_historico_tours), 'hitorico_de_tours.xlsx');
    // }

    /**
     * create a new resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
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
