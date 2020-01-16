<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\CheckSession;
use App\User;
use App\Empresa;
use App\Imports\VinsCollectionImport;
use App\Imports\VinsImport;
use App\Patio;
use App\TipoCampania;
use App\Vin;
use Auth;
use Illuminate\Support\Facades\Crypt;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection as Collection;




class VinController extends Controller
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
    public function index(Request $request)
    {
        $vins = Vin::all();

        $tipo_campanias = TipoCampania::select('tipo_campania_id', 'tipo_campania_descripcion')
            ->pluck('tipo_campania_descripcion', 'tipo_campania_id');

        $patios = DB::table('patios')
            ->select('patio_id', 'patio_nombre')
            ->pluck('patio_nombre', 'patio_id');


        $users = User::select(DB::raw("CONCAT(user_nombre,' ', user_apellido) AS user_nombres"), 'user_id')
            ->orderBy('user_id')
            ->pluck('user_nombres', 'user_id')
            ->all();

        $empresas = Empresa::select('empresa_id', 'empresa_razon_social')
            ->orderBy('empresa_id')
            ->pluck('empresa_razon_social', 'empresa_id')
            ->all();

        $estadosInventario = DB::table('vin_estado_inventarios')
            ->select('vin_estado_inventario_id', 'vin_estado_inventario_desc')
            ->pluck('vin_estado_inventario_desc', 'vin_estado_inventario_id');

        $subEstadosInventario = DB::table('vin_sub_estado_inventarios')
            ->select('vin_sub_estado_inventario_id', 'vin_sub_estado_inventario_desc')
            ->pluck('vin_sub_estado_inventario_desc', 'vin_sub_estado_inventario_id');

        $marcas = DB::table('marcas')
            ->select('marca_id', 'marca_nombre')
            ->pluck('marca_nombre', 'marca_id');


/***********A partir de aqui las consultas del cuadro de busqueda ***************/



        $estado = DB::table('vin_estado_inventarios')
            ->where('vin_estado_inventario_id',$request->estadoinventario_id)
            ->get();



        if(!empty($estado[0]->vin_estado_inventario_id)){
            $estado_id = $estado[0]->vin_estado_inventario_id;
        }else{
            $estado_id = 0;
        }

        $marca = DB::table('marcas')
            ->where('marca_id',$request->marca_id)
            ->get();


        if(!empty($marca[0]->marca_nombre))
        {
            $marca_nombre = $marca[0]->marca_nombre;
        }else{
            $marca_nombre = 'Sin marca';
        }

        $user = DB::table('users')
            ->join('empresas','users.empresa_id','=','empresas.empresa_id')
            ->where('user_id',$request->user_id)
            ->get();



        if(!empty($user[0]->empresa_id))
        {
            $user_empresa_id = $user[0]->empresa_id;
        }else{
            $user_empresa_id = 0;
        }

        $patio = DB::table('patios')
            ->where('patio_id', $request->patio_id)
            ->get();

        if(!empty($patio[0]->patio_nombre))
        {
            $patio_id = $patio[0]->patio_id;
        }else{
            $patio_id = 0;
        }


        if(!empty($request->vin_numero)){

            foreach(explode(',',$request->vin_numero) as $row){
                $arreglo_vins[] = trim($row);
            }

            foreach($arreglo_vins as $v){

                $validate = DB::table('vins')
                    ->where('vin_codigo', $v)
                    ->exists();

                if($validate == true){

                    $vins_selec = DB::table('vins')
                        ->join('users','users.user_id','=','vins.user_id')
                        ->join('vin_estado_inventarios','vins.vin_estado_inventario_id','=','vin_estado_inventarios.vin_estado_inventario_id')
                        ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                        ->where('vin_codigo',$v)
                        ->orWhere('vins.user_id',$user_empresa_id)
                        ->orWhere('vin_marca',$marca_nombre)
                        ->orWhere('vins.vin_estado_inventario_id',$estado_id)
                        ->first();
                    $tabla_vins[] = $vins_selec;
                } else {

                    $tabla_vins = DB::table('vins')
                        ->join('users','users.user_id','=','vins.user_id')
                        ->join('vin_estado_inventarios','vins.vin_estado_inventario_id','=','vin_estado_inventarios.vin_estado_inventario_id')
                        ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                        ->where('vins.user_id',$user_empresa_id)
                        ->orWhere('vin_marca',$marca_nombre)
                        ->orWhere('vins.vin_estado_inventario_id',$estado_id)
                        ->get();

                }
            }
        }else{

            $query = DB::table('vins')
            ->join('users','users.user_id','=','vins.user_id')
            ->join('vin_estado_inventarios','vins.vin_estado_inventario_id','=','vin_estado_inventarios.vin_estado_inventario_id')
            ->join('empresas','users.empresa_id','=','empresas.empresa_id')
            ->where('vins.user_id',$user_empresa_id)
            ->orWhere('vin_marca',$marca_nombre)
            ->orWhere('vins.vin_estado_inventario_id',$estado_id);

            if($estado_id == 4 || $estado_id == 5) {
                $query->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
                    ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
                    ->join('patios','bloques.patio_id','=','patios.patio_id')
                    ->orWhere('patios.patio_id',$patio_id)
                    ->get();

            }else{
                $query->get();
            }


            $tabla_vins = $query->get();
        }



/*
        $tabla_vins = DB::table('vins')
            // ->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
            // ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
            // ->join('patios','bloques.patio_id','=','patios.patio_id')
            ->join('users','users.user_id','=','vins.user_id')
            ->join('vin_estado_inventarios','vins.vin_estado_inventario_id','=','vin_estado_inventarios.vin_estado_inventario_id')
            ->join('empresas','users.empresa_id','=','empresas.empresa_id')
            ->where('vins.user_id',$user_empresa_id)
            ->orWhere('vin_marca',$marca_nombre)
            ->orWhere('vins.vin_estado_inventario_id',$estado_id)
            // ->orWhere('patios.patio_id',$patio_id)
            ->get();
*/

        // dd($tabla_vins);

        return view('vin.index', compact('tabla_vins', 'tipo_campanias','users','empresas', 'estadosInventario', 'subEstadosInventario', 'patios', 'marcas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $vins = Vin::all();

        $vins = Vin::all();

        $users = User::select(DB::raw("CONCAT(user_nombre,' ', user_apellido) AS user_nombres"), 'user_id')
            ->orderBy('user_id')
            ->pluck('user_nombres', 'user_id')
            ->all();

        $empresas = Empresa::select('empresa_id', 'empresa_razon_social')
            ->orderBy('empresa_id')
            ->pluck('empresa_razon_social', 'empresa_id')
            ->all();

        $estadosInventario = DB::table('vin_estado_inventarios')
            ->select('vin_estado_inventario_id', 'vin_estado_inventario_desc')
            ->pluck('vin_estado_inventario_desc', 'vin_estado_inventario_id');

        $subEstadosInventario = DB::table('vin_sub_estado_inventarios')
            ->select('vin_sub_estado_inventario_id', 'vin_sub_estado_inventario_desc')
            ->pluck('vin_sub_estado_inventario_desc', 'vin_sub_estado_inventario_id');

        $marcas = DB::table('marcas')
            ->select('marca_id', 'marca_nombre')
            ->pluck('marca_nombre', 'marca_id');

        $patios = DB::table('patios')
            ->select('patio_id', 'patio_nombre')
            ->pluck('patio_nombre', 'patio_id');


        return view('vin.create', compact(/*'vins', */'users','empresas', 'estadosInventario', 'subEstadosInventario'));
    }

    public function empresa(Request $request, $id_empresa){
	    try {
            $empresa_id = Crypt::decrypt($id_empresa);
        } catch (DecryptException $e) {
            abort(404);
        }

        if ($request->ajax()){
            $users = DB::table('users')
                ->where('users.user_estado', '=', 1)
                ->where('users.deleted_at', '=', null)
                ->where('users.empresa_id', '=', $empresa_id)
                ->select(DB::raw("CONCAT(users.user_nombre,' ',users.user_apellido) AS user_nombres"), 'users.user_id')
                ->orderBy('users.user_id')
                ->pluck('user_nombres', 'users.user_id');

            $ids = DB::table('users')
                ->where('users.user_estado', '=', 1)
                ->where('users.deleted_at', '=', null)
                ->where('users.empresa_id', '=', $empresa_id)
                ->select(DB::raw("CONCAT(users.user_nombre,' ',users.user_apellido) AS user_nombres"), 'users.user_id')
                ->orderBy('users.user_id')
                ->pluck('users.user_id', 'user_nombres');

            return response()->json([
                'success' => true,
                'message' => "Data de usuarios por empresa disponible",
                'ids' => $ids,
                'users' => $users,
            ]);
        }
    }

    public function estadoInventario(Request $request, $id_estado_inventario){
	    try {
            $estado_inventario_id = Crypt::decrypt($id_estado_inventario);
        } catch (DecryptException $e) {
            abort(404);
        }

        if ($request->ajax()){
            if($estado_inventario_id == 4 || $estado_inventario_id == 5){
                $subEstados = DB::table('vin_sub_estado_inventarios')
                    ->where('vin_sub_estado_inventarios.vin_estado_inventario_id', '=', $estado_inventario_id)
                    ->select('vin_sub_estado_inventarios.vin_sub_estado_inventario_id', 'vin_sub_estado_inventarios.vin_sub_estado_inventario_desc')
                    ->orderBy('vin_sub_estado_inventarios.vin_sub_estado_inventario_id')
                    ->pluck('vin_sub_estado_inventarios.vin_sub_estado_inventario_desc', 'vin_sub_estado_inventarios.vin_sub_estado_inventario_id');

                $ids = DB::table('vin_sub_estado_inventarios')
                    ->where('vin_sub_estado_inventarios.vin_estado_inventario_id', '=', $estado_inventario_id)
                    ->select('vin_sub_estado_inventarios.vin_sub_estado_inventario_id', 'vin_sub_estado_inventarios.vin_sub_estado_inventario_desc')
                    ->orderBy('vin_sub_estado_inventarios.vin_sub_estado_inventario_id')
                    ->pluck('vin_sub_estado_inventarios.vin_sub_estado_inventario_id', 'vin_sub_estado_inventarios.vin_sub_estado_inventario_desc');

                return response()->json([
                    'success' => true,
                    'message' => "Data de Sub Estados de Inventario disponible",
                    'ids' => $ids,
                    'subEstados' => $subEstados,
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => "No hay data de inventario para esta opción",
                    'ids' => null,
                    'subEstados' => null,
                ]);
            }
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
        $validate = DB::table('vins')->where('vin_codigo', $request->vin_codigo)->exists();

        if($validate == true)
        {
            flash('El código '.$request->vin_codigo.'  ya existe en la base de datos')->warning();
            return redirect('/vin');
        }else

        $id_estado_inventario =  Crypt::decrypt($request->vin_estado_inventario_id);

        try {

            $vin = new Vin();
            $vin->vin_codigo = $request->vin_codigo;
            $vin->vin_patente = $request->vin_patente;
            $vin->vin_marca = $request->vin_marca;
            $vin->vin_modelo = $request->vin_modelo;
            $vin->vin_color = $request->vin_color;
            $vin->vin_motor = $request->vin_motor;
            $vin->vin_segmento = $request->vin_segmento;
            $vin->vin_fec_ingreso = $request->vin_fec_ingreso;
            $vin->user_id = (int)$request->user_id;
            $vin->vin_estado_inventario_id = $id_estado_inventario;
            $vin->vin_sub_estado_inventario_id = $request->vin_sub_estado_inventario_id;

            $vin->save();

            flash('El VIN se registró correctamente.')->success();
            return redirect('vin');

        }catch (\Exception $e) {

            flash('Error registrando el VIN.')->error();
           flash($e->getMessage())->error();
            return redirect('vin');
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
        $vin_id =  Crypt::decrypt($id);
        $vin = Vin::findOrfail($vin_id);

        $user = User::find($vin->user_id)->first();

        $users = User::select(DB::raw("CONCAT(user_nombre,' ', user_apellido) AS user_nombres"), 'user_id')
            ->orderBy('user_id')
            ->pluck('user_nombres', 'user_id')
            ->all();

        $empresas = Empresa::select('empresa_id', 'empresa_razon_social')
            ->orderBy('empresa_id')
            ->pluck('empresa_razon_social', 'empresa_id')
            ->all();

        $estadosInventario = DB::table('vin_estado_inventarios')
            ->select('vin_estado_inventario_id', 'vin_estado_inventario_desc')
            ->pluck('vin_estado_inventario_desc', 'vin_estado_inventario_id');

        $subEstadosInventario = DB::table('vin_sub_estado_inventarios')
            ->select('vin_sub_estado_inventario_id', 'vin_sub_estado_inventario_desc')
            ->pluck('vin_sub_estado_inventario_desc', 'vin_sub_estado_inventario_id');

        return view('vin.edit', compact('vin', 'user', 'users','empresas', 'estadosInventario', 'subEstadosInventario'));
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
        $vin_id =  Crypt::decrypt($id);
        $vin = Vin::findOrfail($vin_id);

        $id_estado_inventario =  Crypt::decrypt($request->vin_estado_inventario_id);

        try {

            $vin->vin_codigo = $request->vin_codigo;
            $vin->vin_patente = $request->vin_patente;
            $vin->vin_marca = $request->vin_marca;
            $vin->vin_modelo = $request->vin_modelo;
            $vin->vin_color = $request->vin_color;
            $vin->vin_motor = $request->vin_motor;
            $vin->vin_segmento = $request->vin_segmento;
            $vin->vin_fec_ingreso = $request->vin_fec_ingreso;
            $vin->user_id = (int)$request->user_id;
            $vin->vin_estado_inventario_id = $id_estado_inventario;
            $vin->vin_sub_estado_inventario_id = $request->vin_sub_estado_inventario_id;

            $vin->save();

            flash('VIN actualizado correctamente.')->success();
            return redirect('vin');

        }catch (\Exception $e) {

            flash('Error al actualizar el VIN.')->error();
            flash($e->getMessage())->error();
            return redirect('vin');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vin_id =  Crypt::decrypt($id);

        try {
            $vin = Vin::findOrfail($vin_id)->delete();

            flash('Los datos del VIN han sido eliminados satisfactoriamente.')->success();
            return redirect('vin');
        }catch (\Exception $e) {

            flash('Error al intentar eliminar los datos del VIN.')->error();
            //flash($e->getMessage())->error();
            return redirect('vin');
        }
    }

    public function cargamasiva(){

        $users = User::select(DB::raw("CONCAT(user_nombre,' ', user_apellido) AS user_nombres"), 'user_id')
            ->orderBy('user_id')
            ->pluck('user_nombres', 'user_id')
            ->all();

        $empresas = Empresa::select('empresa_id', 'empresa_razon_social')
            ->orderBy('empresa_id')
            ->pluck('empresa_razon_social', 'empresa_id')
            ->all();

        $estadosInventario = DB::table('vin_estado_inventarios')
            ->select('vin_estado_inventario_id', 'vin_estado_inventario_desc')
            ->pluck('vin_estado_inventario_desc', 'vin_estado_inventario_id');

        $subEstadosInventario = DB::table('vin_sub_estado_inventarios')
            ->select('vin_sub_estado_inventario_id', 'vin_sub_estado_inventario_desc')
            ->pluck('vin_sub_estado_inventario_desc', 'vin_sub_estado_inventario_id');


        return view('vin.cargamasiva');


    }

    public function loadexcel(Request $request)
    {

        global $validar;
        global $columna;
        $columna = '';
        $validar = false;
        $archivo = $_FILES["carga_masiva"]["name"];
        $carbon = new \Carbon\Carbon();
        $date = $carbon->now();

        //list($anio_actual, $mes_actual) = explode('-',$request->fecha_up_hidden);


        $nombre_archivo = $request->file('carga_masiva')->getClientOriginalName();
        $extension = $request->file('carga_masiva')->getClientOriginalExtension();

        $archivo = substr($nombre_archivo, 0, -(strlen('.'.$extension))) .  '.' . $extension;

        if ($extension == 'xlsx' || $extension == 'XLSX' || $extension == 'xls' || $extension == 'XLS') {


            $excelVins = $request->file('carga_masiva');
            $extensionArchivo = $excelVins->extension();
            $path = $excelVins->storeAs(
                'CargaVins',
                "CargaVins" . ' - '.date('Y-m-d').' - '.Auth::id().' - '.\Carbon\Carbon::now()->timestamp.' - '.$request->vin_estado_inventario_id.' - '.$request->vin_sub_estado_inventario_id.'-'. '.'.$extensionArchivo
            );


           (new VinsCollectionImport)->import(request()->file('carga_masiva'));
           flash('Los vins fueron cargados exitosamente.')->success();
           return redirect('vin');

            //(new VinsImport)->import(request());

            //Excel::import(new VinsImport, $path);


        }else
        {
            flash('Formato de archivo invalido.')->danger();
           return redirect('vin');
        }

    }

    public function downloadFile()
    {

        return Storage::downloadFile();
    }

    public function search(Request $request)
    {
        $estado = DB::table('vin_estado_inventarios')
            ->where('vin_estado_inventario_id',$request->estadoinventario_id)
            ->get();

        if(!empty($estado[0]->vin_estado_inventario_id)){
            $estado_id = $estado[0]->vin_estado_inventario_id;
        }else{
            $estado_id = 0;
        }

        $marca = DB::table('marcas')
            ->where('marca_id',$request->marca_id)
            ->get();

        if(!empty($marca[0]->marca_nombre))
        {
            $marca_nombre = $marca[0]->marca_nombre;
        }else{
            $marca_nombre = 'Sin marca';
        }

        $user = DB::table('users')
            ->where('user_id',$request->user_id)
            ->get();

        if(!empty($user[0]->user_id))
        {
            $user_id = $user[0]->user_id;
        }else{
            $user_id = 0;
        }

        $patio = DB::table('patios')
            ->where('patio_id', $request->patio_id)
            ->get();

        if(!empty($patio[0]->patio_nombre))
        {
            $patio_id = $patio[0]->patio_id;
        }else{
            $patio_id = 0;
        }

        $tabla_vins = DB::table('vins')
            ->join('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
            ->join('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
            ->join('patios','bloques.patio_id','=','patios.patio_id')
            ->where('user_id',$user_id)
            ->orWhere('vin_marca',$marca_nombre)
            ->orWhere('vin_estado_inventario_id',$estado_id)
            ->orWhere('patios.patio_id',$patio_id)
            ->get();

        return view('vin.index', compact('tabla_vins'));

    }

    public function editarestado($id)
    {
        $vin_id =  Crypt::decrypt($id);
        $vin = Vin::findOrfail($vin_id);

        $user = User::find($vin->user_id)->first();

        $users = User::select(DB::raw("CONCAT(user_nombre,' ', user_apellido) AS user_nombres"), 'user_id')
            ->orderBy('user_id')
            ->pluck('user_nombres', 'user_id')
            ->all();

        $empresas = Empresa::select('empresa_id', 'empresa_razon_social')
            ->orderBy('empresa_id')
            ->pluck('empresa_razon_social', 'empresa_id')
            ->all();

        $estadosInventario = DB::table('vin_estado_inventarios')
            ->select('vin_estado_inventario_id', 'vin_estado_inventario_desc')
            ->pluck('vin_estado_inventario_desc', 'vin_estado_inventario_id');

        return view('vin.cambiaestado', compact('vin', 'user', 'users','empresas', 'estadosInventario'));
    }

    public function cambiaestado(Request $request, $id)
    {
        $vin_id =  Crypt::decrypt($id);
        $vin = Vin::findOrfail($vin_id);
        try {
            $vin->vin_estado_inventario_id = $request->vin_estado_inventario_id;
            $vin->save();

            flash('Estrado actualizado correctamente.')->success();
            return redirect('vin');

        }catch (\Exception $e) {

            flash('Error al actualizar el estado del VIN.')->error();
            flash($e->getMessage())->error();
            return redirect('vin');
        }
    }
}
