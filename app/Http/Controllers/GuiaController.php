<?php

namespace App\Http\Controllers;

use App\Empresa;
use App\Guia;
use App\Http\Middleware\CheckSession;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GuiaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware(PreventBackHistory::class);
        $this->middleware(CheckSession::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /** Consultar todas las Guias (borradas con softdelete o no) */
        $query = Guia::withTrashed();

        // Filtro de búsqueda por fecha desde
        if($request->from){
            $date = Carbon::createFromFormat('Y-m-d', $request->from);

            $query->whereDate('guia_fecha', '>=', $date);
        }

        // Filtro de búsqueda por fecha hasta
        if($request->to){
            $date = Carbon::createFromFormat('Y-m-d', $request->to);
            
            $query->whereDate('guia_fecha', '<=', $date);
        }

        // Filtro de búsqueda por número de guía
        if($request->guia_numero){            
            $query->where('guia_numero', $request->guia_numero);
        }

        // Filtro de búsqueda por empresa
        if($request->empresa_id){            
            $query->where('empresa_id', $request->empresa_id);
        }
        
        $guias = $query->orderBy('guia_fecha')
            ->get();

        $empresas = Empresa::orderBy('empresa_razon_social')
            ->select('empresa_id', 'empresa_razon_social')
            ->pluck('empresa_razon_social', 'empresa_id');

        return view('guia.index', compact('guias', 'empresas'));
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
     * @param  \App\Guia  $guia
     * @return \Illuminate\Http\Response
     */
    public function show(Guia $guia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Guia  $guia
     * @return \Illuminate\Http\Response
     */
    public function edit(Guia $guia)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Guia  $guia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Guia $guia)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Guia  $guia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Guia $guia)
    {
        //
    }
}
