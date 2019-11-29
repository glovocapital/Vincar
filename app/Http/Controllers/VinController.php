<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\CheckSession;
use App\User;
use App\Empresa;
use App\Vin;
use Auth;
use Illuminate\Support\Facades\Crypt;
use DB;

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


        return view('vin.index', compact('users','empresas', 'estadosInventario', 'subEstadosInventario'));
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

        try {

            $vin = new Vin();
            $vin->vin_codigo = $request->vin_codigo;
            $vin->vin_patente = $request->vin_patente;
            $vin->vin_modelo = $request->vin_modelo;
            $vin->vin_marca = $request->vin_marca;
            $vin->vin_color = $request->vin_color;
            $vin->vin_motor = $request->vin_motor;
            $vin->vin_segmento = $request->vin_segmento;
            $vin->vin_fec_ingreso = $request->vin_fec_ingreso;
            $vin->user_id = $request->user_id;
            $vin->vin_estado_inventario_id = $request->vin_estado_inventario_id;
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

        return view('vin.edit', compact('vin', 'users','empresas', 'estadosInventario', 'subEstadosInventario'));
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

        try {

            $vin->vin_codigo = $request->vin_codigo;
            $vin->vin_patente = $request->vin_patente;
            $vin->vin_modelo = $request->vin_modelo;
            $vin->vin_marca = $request->vin_marca;
            $vin->vin_color = $request->vin_color;
            $vin->vin_motor = $request->vin_motor;
            $vin->vin_segmento = $request->vin_segmento;
            $vin->vin_fec_ingreso = $request->vin_fec_ingreso;
            $vin->user_id = $request->user_id;
            $vin->vin_estado_inventario_id = $request->vin_estado_inventario_id;
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
}
