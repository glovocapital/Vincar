<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\CheckSession;
use App\Remolque;
use Illuminate\Support\Facades\Crypt;
use DB;

class RemolqueController extends Controller
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
        $empresa = DB::table('empresas')
        ->select('empresa_id', 'empresa_razon_social')
        ->pluck('empresa_razon_social', 'empresa_id');

        $remolque = Remolque::all();
        return view('remolque.index', compact('remolque','empresa'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $remolque = Remolque::all();

        $empresa = DB::table('empresas')
            ->select('empresa_id', 'empresa_razon_social')
            ->pluck('empresa_razon_social', 'empresa_id');


        return view('remolque.index', compact('remolque', 'camion'));
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

            $remolque = new Remolque();

            $remolque->remolque_patente = $request->remolque_patente;
            $remolque->remolque_modelo = $request->remolque_modelo;
            $remolque->remolque_marca = $request->remolque_marca;
            $remolque->remolque_anio = $request->remolque_anio;
            $remolque->remolque_capacidad = $request->remolque_capacidad;
            $remolque->empresa_id = $request->empresa_id;


            $remolque->save();

            flash('El remolque se creo correctamente.')->success();
            return redirect('remolque');

        }catch (\Exception $e) {

            flash('Error al crear el remolque.')->error();
           //flash($e->getMessage())->error();
            return redirect('remolque');
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
        $remolque_id =  Crypt::decrypt($id);
        $remolque = Remolque::findOrfail($remolque_id);

        $empresa = DB::table('empresas')
        ->select('empresa_id', 'empresa_razon_social')
        ->pluck('empresa_razon_social', 'empresa_id');

        return view('remolque.edit', compact('remolque', 'empresa'));

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
        //dd($request);
        $remolque_id =  Crypt::decrypt($id);
        $remolque =  Remolque::findOrfail($remolque_id);

        try {

            $remolque->remolque_patente = $request->remolque_patente;
            $remolque->remolque_modelo = $request->remolque_modelo;
            $remolque->remolque_marca = $request->remolque_marca;
            $remolque->remolque_anio = $request->remolque_anio;
            $remolque->empresa_id = $request->empresa_id;
            $remolque->remolque_capacidad = $request->remolque_capacidad;


            $remolque->save();

            flash('Los datos del remolque se editaron correctamente.')->success();
            return redirect('remolque');

        }catch (\Exception $e) {

            flash('Error al editar el remolque.')->error();
           //flash($e->getMessage())->error();
            return redirect('remolque');
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
        $remolque_id =  Crypt::decrypt($id);

        try {
            $remolque = Remolque::findOrfail($remolque_id)->delete();

            flash('Los datos del remolque  han sido eliminados satisfactoriamente.')->success();
            return redirect('camiones');
        }catch (\Exception $e) {

            flash('Error al intentar eliminar los datos del remolque.')->error();
            //flash($e->getMessage())->error();
            return redirect('camiones');
        }
    }
}
