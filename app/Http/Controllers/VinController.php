<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\CheckSession;
use App\User;
use App\Empresa;
use App\Imports\VinsCollectionImport;
use App\Imports\VinsImport;
use App\Vin;
use Auth;
use Illuminate\Support\Facades\Crypt;
use DB;
use Maatwebsite\Excel\Facades\Excel;


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
    public function index()
    {
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

        return view('vin.index', compact('vins','users','empresas', 'estadosInventario', 'subEstadosInventario'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $vins = Vin::all();

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


        return view('vin.cargamasiva', compact(/*'vins', */'users','empresas', 'estadosInventario', 'subEstadosInventario'));


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
}
