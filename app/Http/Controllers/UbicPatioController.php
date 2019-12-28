<?php

namespace App\Http\Controllers;

use App\UbicPatio;
use App\Bloque;
use Illuminate\Http\Request;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\CheckSession;
use App\Imports\UbicPatiosImport;
use App\Vin;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class UbicPatioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(PreventBackHistory::class);
        $this->middleware(CheckSession::class);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id_bloque)
    {
        try {
            $bloque_id = Crypt::decrypt($id_bloque);
        } catch (DecryptException $e) {
            abort(404);
        }

        $bloque = Bloque::find($bloque_id);

        $bloque_nombre = $bloque->bloque_nombre;

        $patio_id = $bloque->patio_id;

        $ubic_patios = UbicPatio::where('bloque_id',$bloque_id)
            ->where('deleted_at', null)
            ->get();

        if($ubic_patios->count() > 0) {
            return view('ubic_patio.index', compact('ubic_patios', 'bloque_nombre', 'patio_id'));
        } else {
            return view('ubic_patio.noubics',compact('patio_id'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id_bloque)
    {
        try {
            $bloque_id = Crypt::decrypt($id_bloque);
        } catch (DecryptException $e) {
            abort(404);
        }

        $bloque = Bloque::find($bloque_id);

        $bloque_nombre = $bloque->bloque_nombre;

        $ubicaciones = UbicPatio::where('bloque_id',$bloque_id)
            ->where('deleted_at', null)
            ->get();

        $totalUbicacionesBloque = (int)$bloque->bloque_filas * (int)$bloque->bloque_columnas;

        if($ubicaciones->count() < $totalUbicacionesBloque){
            return view('ubic_patio.create', compact('bloque_id', 'bloque_nombre'));
        } else {
            return view('ubic_patio.maxubics', compact('bloque_id'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            if(isset($request->vin_codigo)){
                $vin = Vin::where('vin_codigo', $request->vin_codigo)->first();
            }

             $ubic_patio = new UbicPatio();

            $ubic_patio->ubic_patio_fila = $request->ubic_patio_fila;
            $ubic_patio->ubic_patio_columna = $request->ubic_patio_columna;
            
            if(isset($vin)) {
                $ubic_patio->vin_id = $vin->vin_id;
            } else {
                $ubic_patio->vin_id = null;
            }

            if($request->ubic_patio_ocupada == 1){
                $ubic_patio->ubic_patio_ocupada = true;
            } else {
                $ubic_patio->ubic_patio_ocupada = false;
            }

            $ubic_patio->bloque_id = (int)$request->bloque_id;

            $ubic_patio->save();

            flash('Ubicación registrada correctamente.')->success();
            return redirect()->route('ubic_patio.index', ['id_bloque' => Crypt::encrypt($ubic_patio->bloque_id)]);
        } catch (\Exception $e) {

            flash('Error registrando la ubicación.')->error();
            flash($e->getMessage())->error();
            return redirect()->route('ubic_patio.index', ['id_bloque' => Crypt::encrypt($ubic_patio->bloque_id)]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UbicPatio  $ubicPatio
     * @return \Illuminate\Http\Response
     */
    public function show(UbicPatio $ubicPatio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UbicPatio  $ubicPatio
     * @return \Illuminate\Http\Response
     */
    public function edit($id_ubic_patio)
    {
        try {
            $ubic_patio_id = Crypt::decrypt($id_ubic_patio);
        } catch (DecryptException $e) {
            abort(404);
        }

        $ubic_patio = UbicPatio::findOrFail($ubic_patio_id);

        return view('ubic_patio.edit', compact('ubic_patio'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UbicPatio  $ubicPatio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id_ubic_patio)
    {
        try {
            $ubic_patio_id = Crypt::decrypt($id_ubic_patio);
        } catch (DecryptException $e) {
            abort(404);
        }

        $ubic_patio = UbicPatio::findOrFail($ubic_patio_id);

        if(isset($request->vin_codigo)){
            $vin = Vin::where('vin_codigo', $request->vin_codigo)->first();
        }

        try {
            
            $ubic_patio->ubic_patio_fila = $request->ubic_patio_fila;
            $ubic_patio->ubic_patio_columna = $request->ubic_patio_columna;
            $ubic_patio->ubic_patio_ocupada = $request->ubic_patio_ocupada;
            
            if(isset($vin)){
                $ubic_patio->vin_id = $vin->vin_id;
            } else {
                $ubic_patio->vin_id = null;
            }
            
            $ubic_patio->bloque_id = $request->bloque_id;

            $ubic_patio->save();

            flash('Ubicación modificada correctamente.')->success();
            return redirect()->route('ubic_patio.index', ['id_bloque' => Crypt::encrypt($request->bloque_id)]);

        }catch (\Exception $e) {

            flash('Error modificando la ubicación.')->error();
            flash($e->getMessage())->error();
            return redirect()->route('ubic_patio.index', ['id_bloque' => Crypt::encrypt($request->bloque_id)]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UbicPatio  $ubicPatio
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ubic_patio_id =  Crypt::decrypt($id);

        $ubic_patio = UbicPatio::findOrfail($ubic_patio_id);

        $bloque_id = $ubic_patio->bloque_id;

        $ubic_patio->delete();
        
        try {

            flash('Los datos de la Ubicación han sido eliminados satisfactoriamente.')->success();
            return redirect()->route('ubic_patio.index', ['id_bloque' => Crypt::encrypt($bloque_id)]);
        }catch (\Exception $e) {

            flash('Error al intentar eliminar los datos de la Ubicación.')->error();
            //flash($e->getMessage())->error();
            return redirect()->route('ubic_patio.index', ['id_bloque' => Crypt::encrypt($bloque_id)]);
        }
    }
}
