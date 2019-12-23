<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\CheckSession;
use App\Imports\UbicPatiosImport;
use App\Patio;
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

        return view('patio.index', compact('patios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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
    public function edit(Patio $patio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Patio  $patio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Patio $patio)
    {
        //
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
