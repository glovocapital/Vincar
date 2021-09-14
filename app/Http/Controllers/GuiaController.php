<?php

namespace App\Http\Controllers;

use App\Empresa;
use App\Guia;
use App\GuiaVin;
use App\Http\Middleware\CheckSession;
use App\Vin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FileNotFoundException;

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

        if (!$request->from && !$request->to) {
            $query->where('guia_fecha',  '>', Carbon::now()->subDays(90)->toDateTimeString());
        }

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

        // Filtro de búsqueda VIN
        if($request->vin_numero){
            $vin_id = Vin::where('vin_codigo', $request->vin_numero)->value('vin_id');

            if ($vin_id) {
                $guia_vin = GuiaVin::where('vin_id', $vin_id)->first();
                $guia = Guia::where('guia_id', $guia_vin->guia_id)->first();

                $query->where('guia_id', $guia->guia_id);
            }
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

    public function downloadGuia($id)
    {
        $guia_id =  Crypt::decrypt($id);

        $guia = Guia::find($guia_id);

        if($guia)
        {
            try
            {
                return Storage::download($guia->guia_ruta);
            } catch (FileNotFoundException $e) {
                flash('Error: Archivo no encontrado. Informar al administrador.')->error();
                return redirect('guia');
            }
        } else {
            flash('Error: Guía ID: ' . $guia_id . ' no encontrada. Informar al administrador.')->error();
            return redirect('guia');
        }
    }
}
