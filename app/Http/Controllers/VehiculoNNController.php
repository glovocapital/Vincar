<?php

namespace App\Http\Controllers;

use App\FotoNN;
use App\VehiculoNN;
use Illuminate\Http\Request;

class VehiculoNNController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vins = VehiculoNN::orderBy('vin_id')->pluck('vin_codigo', 'vin_id');

        $vin_ids = VehiculoNN::orderBy('vin_id')->pluck('vin_id');

        return response()->json([
            'success' => true,
            'message' => "Data de VINs NN disponible",
            'vins' => $vins,
            'vin_ids' => $vin_ids,
        ]);
    }

    public function dataVinNN($vin_id)
    {
        $vin = VehiculoNN::find($vin_id);

        $user = $vin->user;

        $fotos = FotoNN::where('vin_codigo', $vin->vin_codigo)->orderBy('foto_id')->get();

        return response()->json([
            'success' => true,
            'message' => "Responsable de VIN NN disponible",
            'user' => $user,
            'fotos' => $fotos,
            'vin' => $vin,
            'marca' => strtoupper($vin->oneMarca->marca_nombre),
        ]);
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
        dd($request);
        if($request->ajax()) {
            dd($request);
        }
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
