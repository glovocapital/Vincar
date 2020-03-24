<?php

namespace App\Http\Controllers;

use App\Camion;
use Illuminate\Http\Request;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\CheckSession;
use App\User;
use App\Empresa;
use App\Remolque;
use DB;

class TourController extends Controller
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

        $users = User::select(DB::raw("CONCAT(user_nombre,' ', user_apellido) AS user_nombres"), 'user_id')
        ->orderBy('user_id')
        ->where('rol_id',5)
        ->pluck('user_nombres', 'user_id')
        ->all();

        $empresas = Empresa::select('empresa_id', 'empresa_razon_social')
        ->orderBy('empresa_id')
        ->pluck('empresa_razon_social', 'empresa_id')
        ->all();


        $camion = Camion::select('camion_id', 'camion_patente')
        ->orderBy('camion_id')
        ->pluck('camion_patente', 'camion_id')
        ->all();

        $remolque = Remolque::select('remolque_id', 'remolque_patente')
        ->orderBy('remolque_id')
        ->pluck('remolque_patente', 'remolque_id')
        ->all();

        $transporte = Empresa::select('empresa_id', 'empresa_razon_social')
        ->orderBy('empresa_id')
        ->where('empresa_es_proveedor', true)
        ->where('tipo_proveedor_id', 8)
        ->pluck('empresa_razon_social', 'empresa_id')
        ->all();

        return view('transporte.index', compact('empresas','users','camion','transporte','remolque'));
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
        //
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
