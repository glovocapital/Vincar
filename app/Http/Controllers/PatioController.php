<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\CheckSession;
use App\Imports\UbicPatiosImport;
use App\Patio;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PatioController extends Controller
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
    public function index()
    {
        $patios = Patio::all();

        $regiones = DB::table('regiones')
            ->select('region_id', 'region_nombre')
            ->orderBy('region_id')
            ->pluck('region_nombre', 'region_id')
            ->all();

        // $provincias = DB::table('provincias')
        //     ->select('provincia_id', 'provincia_nombre')
        //     ->orderBy('provincia_id')
        //     ->pluck('provincia_nombre', 'provincia_id')
        //     ->all();

        // $comunas = DB::table('comunas')
        //     ->select('comuna_id', 'comuna_nombre')
        //     ->orderBy('comuna_id')
        //     ->pluck('comuna_nombre', 'comuna_id')
        //     ->all();

        return view('patio.index', compact('patios', 'regiones'/*, 'provincias', 'comunas'*/));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $regiones = DB::table('regiones')
            ->select('region_id', 'region_nombre')
            ->orderBy('region_id')
            ->pluck('region_nombre', 'region_id')
            ->all();


        return view('patio.create', compact('regiones'));
    }

    public function comunas(Request $request, $id_region){
	    try {
            $region_id = Crypt::decrypt($id_region);
        } catch (DecryptException $e) {
            abort(404);
        }

        if ($request->ajax()){
            $comunas = DB::table('comunas')
                ->where('comunas.region_id', '=', $region_id)
                ->select('comunas.comuna_nombre', 'comunas.comuna_id')
                ->orderBy('comunas.comuna_id')
                ->pluck('comunas.comuna_nombre', 'comunas.comuna_id');

            $ids = DB::table('comunas')
                ->where('comunas.region_id', '=', $region_id)
                ->select('comunas.comuna_nombre', 'comunas.comuna_id')
                ->orderBy('comunas.comuna_id')
                ->pluck('comunas.comuna_id', 'comunas.comuna_nombre');

            return response()->json([
                'success' => true,
                'message' => "Data de usuarios por empresa disponible",
                'ids' => $ids,
                'comunas' => $comunas,
            ]);
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
            $region_id = Crypt::decrypt($request->region_id);
        } catch (DecryptException $e) {
            abort(404);
        }

        try {
            $patio = new Patio();
            $patio->patio_nombre = $request->patio_nombre;
            $patio->patio_bloques = $request->patio_bloques;
            $patio->patio_coord_lat = $request->patio_coord_lat;
            $patio->patio_coord_lon = $request->patio_coord_lon;
            $patio->patio_direccion = $request->patio_direccion;
            $patio->region_id = (int)$region_id;
            $patio->comuna_id = (int)$request->comuna_id;

            $patio->save();

            flash('Patio registrado correctamente.')->success();
            return redirect('patio');

        }catch (\Exception $e) {

            flash('Error registrando el patio.')->error();
            flash($e->getMessage())->error();
            return redirect('patio');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Patio  $patio
     * @return \Illuminate\Http\Response
     */
    public function show(Patio $patio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Patio  $patio
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $patio_id =  Crypt::decrypt($id);
        $patio = Patio::findOrfail($patio_id);

        $regiones = DB::table('regiones')
            ->select('region_id', 'region_nombre')
            ->orderBy('region_id')
            ->pluck('region_nombre', 'region_id')
            ->all();
        
        $comunas = DB::table('comunas')
            ->select('comuna_id', 'comuna_nombre')
            ->where('region_id', $patio->region_id)
            ->orderBy('comuna_id')
            ->pluck('comuna_nombre', 'comuna_id')
            ->all();

        return view('patio.edit', compact('patio', 'regiones', 'comunas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Patio  $patio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $patio_id = Crypt::decrypt($id);
            $region_id = Crypt::decrypt($request->region_id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $patio = Patio::findOrfail($patio_id);

        try {
            $patio->patio_nombre = $request->patio_nombre;
            $patio->patio_bloques = $request->patio_bloques;
            $patio->patio_coord_lat = $request->patio_coord_lat;
            $patio->patio_coord_lon = $request->patio_coord_lon;
            $patio->patio_direccion = $request->patio_direccion;
            $patio->region_id = $region_id;
            $patio->comuna_id = (int)$request->comuna_id;

            $patio->save();

            flash('Patio actualizado correctamente.')->success();
            return redirect('patio');

        }catch (\Exception $e) {

            flash('Error actualizando el patio.')->error();
            flash($e->getMessage())->error();
            return redirect('patio');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Patio  $patio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patio $patio)
    {
        //
    }

    /**
     * Carga Masiva de Vins
     */
    public function cargarPatios(){
        return view('patio.cargar_patios');
    }

    /**
     * Carga Masiva de Vins
     */
    public function storePatios(Request $request){
        $array = Excel::toArray(new UbicPatiosImport, request()->file('ENVIO GASTOS COMUNES ABRIL\'19 T-B (1).xls'));
        dd($array);


        $collection = Excel::toCollection(new UbicPatiosImport, request()->file('ENVIO GASTOS COMUNES ABRIL\'19 T-B (1).xls'));
    }
}
