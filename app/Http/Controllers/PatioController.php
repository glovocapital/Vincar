<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\CheckSession;
use App\Imports\PatiosImport;
use App\Patio;
use App\Vin;
use App\UbicPatio;
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

    private function ColorRamdom(){

        $rgbColor= Array(mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));

        return "rgb(". implode(",",$rgbColor).")";
    }

    public function dashboard(Request $request)
    {
        $patio = (isset($request->id_patio))?intval($request->id_patio):0;


        $capacidad = DB::table('patios')
            ->join('bloques','patios.patio_id','bloques.patio_id')
            ->select('patio_nombre','bloque_nombre','bloque_filas','bloque_columnas', 'patios.patio_id as patio_id');
        if($patio>0)
            $capacidad->where("patios.patio_id","=",$patio);
        $capacidad=$capacidad->get();

        $vehiculos_patio = DB::table('patios')
            ->join('bloques','patios.patio_id','bloques.patio_id')
            ->join('ubic_patios','ubic_patios.bloque_id','bloques.bloque_id')
            ->select(DB::raw("count(patio_nombre) AS can_vin"), "patio_nombre")
            ->where('ubic_patio_ocupada',true)
            ->groupBy('patio_nombre');

        if($patio>0)
            $vehiculos_patio->where('patios.patio_id',"=",$patio);

        $vehiculos_patio = $vehiculos_patio->get();

        $vehiculos30 = DB::table('vins')
            ->join('vin_estado_inventarios','vin_estado_inventarios.vin_estado_inventario_id','=', 'vins.vin_estado_inventario_id')
            ->select(DB::raw("count(vins.vin_id) AS can_vin"))
            ->where('vins.vin_estado_inventario_id','<>',1)
            ->where('vins.vin_estado_inventario_id','<>',8)
            ->where('vin_fec_ingreso','<',date('Y-m-d',strtotime( '-30 day' , strtotime ( date('Y-m-d') ) )));

        if($patio>0){
          $vehiculos30->join('ubic_patios','ubic_patios.vin_id','vins.vin_id');
          $vehiculos30->join('bloques','bloques.bloque_id','ubic_patios.bloque_id');
          $vehiculos30->where('bloques.patio_id',"=",$patio);

        }

        $vehiculos30 = $vehiculos30->get();

        $vehiculosTotal = DB::table('vins')
            ->join('vin_estado_inventarios','vin_estado_inventarios.vin_estado_inventario_id','=', 'vins.vin_estado_inventario_id')
            ->select(DB::raw("count(vins.vin_id) AS can_vin"))
            ->where('vins.vin_estado_inventario_id','<>',1)
            ->where('vins.vin_estado_inventario_id','<>',8);

        if($patio>0){
            $vehiculosTotal->join('ubic_patios','ubic_patios.vin_id','vins.vin_id');
            $vehiculosTotal->join('bloques','bloques.bloque_id','ubic_patios.bloque_id');
            $vehiculosTotal->where('bloques.patio_id',"=",$patio);

        }
        $vehiculosTotal = $vehiculosTotal->get();

        $Unidades_Danadas = DB::table('vins')
            ->select(DB::raw("count(vins.vin_id) AS can_vin"))
            ->where('vin_estado_inventario_id',"=",6)
            ->where('vin_estado_inventario_id',"=",7);

        if($patio>0){
            $Unidades_Danadas->join('ubic_patios','ubic_patios.vin_id','vins.vin_id');
            $Unidades_Danadas->join('bloques','bloques.bloque_id','ubic_patios.bloque_id');
            $Unidades_Danadas->where('bloques.patio_id',"=",$patio);
        }

        $Unidades_Danadas = $Unidades_Danadas->get();

        $colores = Array("#f79663","#feca7a","#16d8d8","#ff005c","#dee4e4","#ff0000","#26dbdb");

        $Capacidad_Total=0;
        $Espacios_Disponibles=0;

        $Porc_vehiculo=Array();
        $Capacidad=Array();

        $ip=0; $Vehiculos_Total_enpatio=0;
        foreach ($vehiculos_patio as $vh_pat){

            if($ip>=count($colores)) $colores[$ip] = self::ColorRamdom();

            $Porc_vehiculo[] = Array("Patio" => $vh_pat->patio_nombre, "Data" => $vh_pat->can_vin, "backgroundColor" => $colores[$ip]);
            $ip++;

            $Vehiculos_Total_enpatio +=$vh_pat->can_vin;
        }

        $ip=0;
        foreach ($capacidad as $cap){


                if ($ip >= count($colores)) $colores[$ip] = self::ColorRamdom();

                if (array_key_exists($cap->patio_nombre, $Capacidad))
                    $Capacidad[$cap->patio_nombre]["Data"] += ($cap->bloque_filas * $cap->bloque_columnas);
                else {
                    $Capacidad[$cap->patio_nombre] = Array("Patio" => $cap->patio_nombre, "Data" => ($cap->bloque_filas * $cap->bloque_columnas), "backgroundColor" => $colores[$ip]);
                    $ip++;
                }

                $Capacidad_Total += ($cap->bloque_filas * $cap->bloque_columnas);

        }

        $Espacios_Disponibles = $Capacidad_Total - $Vehiculos_Total_enpatio;

        if($patio>0){
            $Capacidad["Ocupado"] = Array("Patio" => "Ocupado", "Data" => $Vehiculos_Total_enpatio, "backgroundColor" => $colores[$ip]);
            $ip++;
            $Porc_vehiculo[] = Array("Patio" => "Total", "Data" => $Capacidad_Total, "backgroundColor" => $colores[$ip]);

        }

        $Porc_vehiculos=Array();
        $Capacidads=Array();
        foreach ($Capacidad as $Capac){
            $Capacidads[] = $Capac;

        }




        $datos = Array(

            'Capacidad_Total'=>$Capacidad_Total,
            'Espacios_Disponibles'=>$Espacios_Disponibles,
            'Porc_vehiculo'=>$Porc_vehiculo,
            'Capacidad'=>$Capacidads,
            'Vehiculos_30dias'=>Array(
                Array("Vehiculos"=>"Mas de 30 días", "Data"=>$vehiculos30[0]->can_vin, "backgroundColor"=>"#feca7a"),
                Array("Vehiculos"=>"Menos de 30 días", "Data"=>($vehiculosTotal[0]->can_vin-$vehiculos30[0]->can_vin), "backgroundColor"=>"#dee4e4")
            ),
            'Vehiculos_danos'=>Array(
                Array("Vehiculos"=>"Dañados", "Data"=>$Unidades_Danadas[0]->can_vin, "backgroundColor"=>"#ff0000"),
                Array("Vehiculos"=>"Optimos", "Data"=>($vehiculosTotal[0]->can_vin-$Unidades_Danadas[0]->can_vin), "backgroundColor"=>"#26dbdb")
            )

        );
        return response()->json($datos);
    }

    public function bloques(Request $request){

        $id_patio =   $request->get("patio_id");

        if ($request->ajax()){
            $bloques = DB::table('bloques')
                ->select('bloque_nombre','patio_id', 'bloque_id')
                ->where('patio_id', '=', $id_patio)
                ->where('bloques.deleted_at','=',null)
                ->orderBy('bloque_nombre')
                ->get();

            $request->id_patio=$id_patio;
            $dashboard_ =self::dashboard($request);
            $dashboard = json_decode($dashboard_->content(),true);


            return response()->json([
                'success' => true,
                'bloques' => $bloques,
                'dashboard'=>$dashboard
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
                 ->join("vin_estado_inventarios", "vin_estado_inventarios.vin_estado_inventario_id","=","vins.vin_estado_inventario_id")
                 ->select('vins.vin_id as vin_id','ubic_patio_columna','ubic_patio_fila', "vin_codigo", "vin_marca","ubic_patios.updated_at as vin_fec_ingreso","vins.vin_estado_inventario_id as vin_estado_inventario_id","bloque_id","vin_estado_inventario_desc")
                 ->whereIn('bloque_id', $grupo_bloques)
                 ->get();



            return response()->json([
                'success' => true,
                'bloques' => $bloques,
                'ubicados' => $ubicados
            ]);
        }



    }


    public function Vaciarbloques(Request $request){

        $id_patio =   $request->get("patio_id");

        $id_bloque =   $request->get("bloque_id");

        $id_estado =   $request->get("estado_id");

        if ($request->ajax()){

            $partes =  explode('_', $id_bloque);

            if(count($partes)==1){
                $ubicados = DB::table('ubic_patios')
                    ->join("vins", "ubic_patios.vin_id","=","vins.vin_id")
                    ->join("bloques", "bloques.bloque_id","=","ubic_patios.bloque_id")
                    ->select('vins.vin_id as vin_id')
                    ->where('patio_id', '=', $id_patio)
                    ->where('ubic_patios.bloque_id', '=', $partes[0])
                    ->get();
            }else {
                $ubicados = DB::table('ubic_patios')
                    ->join("vins", "ubic_patios.vin_id","=","vins.vin_id")
                    ->join("bloques", "bloques.bloque_id","=","ubic_patios.bloque_id")
                    ->select('vins.vin_id as vin_id')
                    ->where('patio_id', '=', $id_patio)
                    ->where('ubic_patios.bloque_id', '=', $partes[0])
                    ->where('ubic_patio_fila', '=', intval($partes[1]))
                    ->get();

            }


            $grupo_vin=Array(); $sep = "";
            foreach($ubicados as $v){
                $grupo_vin[] = $v->vin_id;
            }

            if($ubicados){

                foreach($ubicados as $v){
                    $Vin_= Vin::findOrFail($v->vin_id);
                    $Vin_->vin_estado_inventario_id = $id_estado;
                    $Vin_->update();

                    $UbicPatio = UbicPatio::where('vin_id','=', $v->vin_id)->get();
                    if(count($UbicPatio)>0){
                        $UbicPatios = UbicPatio::findOrFail($UbicPatio[0]->ubic_patio_id);
                        $UbicPatios->ubic_patio_ocupada = false;
                        $UbicPatios->vin_id = null;
                        $UbicPatios->update();
                    }

                }


                $usersf = Array("Err" => 0, "Msg" => "Vaciado Exitoso", "Vin"=>$partes);

            }else{
                $usersf = Array("Err" => 1, "Msg" => "Vin obligatorio");
            }



            return response()->json($usersf);
        }



    }
}
