<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\CheckSession;
use App\Imports\PatiosImport;
use App\Patio;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Row;
use Illuminate\Support\Facades\Storage;
class PatioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware(PreventBackHistory::class);
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
    public function destroy($id)
    {
        $patio_id =  Crypt::decrypt($id);
        try {
            $patio = Patio::findOrfail($patio_id)->delete();
            flash('Los datos del Patio han sido eliminados satisfactoriamente.')->success();
            return redirect('patio');
        }catch (\Exception $e) {
            flash('Error al intentar eliminar los datos del Patio.')->error();
            //flash($e->getMessage())->error();
            return redirect('patio');
        }
    }
    /**
     * Carga Masiva de Patios
     */
    public function cargarPatios(){
        return view('patio.cargar_patios');
    }
    /**
     * Carga Masiva de Patios
     */
    public function storePatios(Request $request){
        Excel::import(new PatiosImport, request()->file('file'));
        flash('Los patios fueron cargados exitosamente.')->success();
        // return view('patio.index');
        return redirect()->action('PatioController@index');
    }

    public function indexVinsPatio()
    {
        $patios = Patio::all();

        return view('patio.vins_patio', compact('patios'));
    }

    public function dashboard()
    {

        $datos = Array(
            'Capacidad_Total'=>300,
            'Espacios_Disponibles'=>100,
            'Porc_vehiculo'=>Array(
                Array("Patio"=>"Patio 1", "Data"=>20, "backgroundColor"=>"#f79663"),
                Array("Patio"=>"Patio 2", "Data"=>30, "backgroundColor"=>"#feca7a"),
                Array("Patio"=>"Patio 3", "Data"=>30, "backgroundColor"=>"#16d8d8"),
                Array("Patio"=>"Patio 4", "Data"=>50, "backgroundColor"=>"#ff005c")
            ),
            'Capacidad'=>Array(
                Array("Patio"=>"Patio 1", "Data"=>300, "backgroundColor"=>"#ff005c"),
                Array("Patio"=>"Patio 2", "Data"=>400, "backgroundColor"=>"#16d8d8")
            ),
            'Vehiculos_30dias'=>Array(
                Array("Vehiculos"=>"Mas de 30 días", "Data"=>20, "backgroundColor"=>"#feca7a"),
                Array("Vehiculos"=>"Menos de 30 días", "Data"=>30, "backgroundColor"=>"#dee4e4")
            ),
            'Vehiculos_danos'=>Array(
                Array("Vehiculos"=>"Dañados", "Data"=>20, "backgroundColor"=>"#ff0000"),
                Array("Vehiculos"=>"Optimos", "Data"=>60, "backgroundColor"=>"#26dbdb")
            )

        );
        return json_encode($datos);
    }

    public function bloques(Request $request){

        $id_patio =   $request->get("patio_id");

        if ($request->ajax()){
            $bloques = DB::table('bloques')
                ->where('patio_id', '=', $id_patio)
                ->select('bloque_nombre','patio_id')
                ->orderBy('bloque_nombre')
                ->get();


            return response()->json([
                'success' => true,
                'bloques' => $bloques,
            ]);
        }
    }

    public function downloadFile()
    {
        return Storage::response("PlanillasDescargas/CargaPatios.xlsx");
    }

    public function Todosbloques(Request $request){

        $id_patio =   $request->get("patio_id");

        $id_bloque =   $request->get("bloque_id");

        if ($request->ajax()){
            $bloques = Array();

            if($id_bloque!='')
                $bloques = DB::table('bloques')
                ->where('patio_id', '=', $id_patio)
                ->where('bloque_id', '=', $id_bloque)
                ->select('patio_id','bloque_nombre','bloque_filas', 'bloque_columnas')
                ->orderBy('bloque_nombre')
                ->get();
            else
                $bloques = DB::table('bloques')
                    ->where('patio_id', '=', $id_patio)
                    ->select('patio_id','bloque_nombre','bloque_filas', 'bloque_columnas', 'bloque_id')
                    ->orderBy('bloque_nombre')
                    ->get();

            $grupo_bloques=Array(); $sep = "";
            foreach($bloques as $bloque){
                $grupo_bloques[] = $bloque->bloque_id;
            }



             $ubicados = DB::table('ubic_patios')
                 ->join("vins", "ubic_patios.vin_id","=","vins.vin_id")
                 ->select('vins.vin_id as vin_id','ubic_patio_columna','ubic_patio_fila', "vin_codigo", "vin_marca","ubic_patios.updated_at as vin_fec_ingreso","vin_estado_inventario_id","bloque_id")
                 ->whereIn('bloque_id', $grupo_bloques)
                 ->get();



            return response()->json([
                'success' => true,
                'bloques' => $bloques,
                'ubicados' => $ubicados
            ]);
        }



    }
}
