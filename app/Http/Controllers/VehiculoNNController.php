<?php

namespace App\Http\Controllers;

use App\FotoNN;
use App\VehiculoNN;
use App\Vin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        // if (VehiculoNN::onlyTrashed()->where('vin_codigo', $request->vin_codigo)->exists()){
            //  VehiculoNN::onlyTrashed()->where('vin_codigo', $request->vin_codigo)->restore();
        // } else {
            // Código para registrar un vehículo nuevo.
        // }
    }

    /**
     * Register a Vin N/N resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function registrarVin(Request $request)
    {
        if($request->ajax()) {
            DB::beginTransaction();
            
            $vin = new Vin();
            $vin->vin_codigo = $request->vin_codigo;
            $vin->vin_patente = $request->vin_patente;
            $vin->vin_modelo = $request->vin_modelo;
            $vin->vin_marca = $request->vin_marca;
            $vin->vin_color = $request->vin_color;
            $vin->vin_segmento = '';
            $vin->vin_fec_ingreso = date('Y-m-d', now()->timestamp);
            $vin->user_id = $request->user_id;
            $vin->vin_estado_inventario_id = 1;
            $vin->vin_predespacho = false;
            
            if ($vin->save()) {
                if ($this->guardarHistorialVin($vin)){
                    if (VehiculoNN::find($request->vin_id)->delete()){
                        DB::commit();
    
                        return response()->json([
                            'success' => true,
                            'message' => "VIN: " . $vin->vin_codigo . " registrado exitosamente.",
                        ]);
                    }
                }       
            }

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => "Error al intentar registrar el VIN: " . $vin->vin_codigo,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Error: Llamado a procedimiento no permitido",
            ]);
        }
    }

    protected function guardarHistorialVin(Vin $vin)
    {
        $fecha = date('Y-m-d');

        // Guardar historial del cambio
        $result = DB::insert('INSERT INTO historico_vins
            (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
            origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $vin->vin_id,
                $vin->vin_estado_inventario_id,
                $fecha,
                $vin->user_id,
                null,
                null,
                $vin->oneUser->belongsToEmpresa->empresa_id,
                "VIN Creado.",
                "Origen: Vehículos N/N.",
                "Patio: BLoque y Ubicación por asignar."
            ]
        );
        
        return $result;
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
