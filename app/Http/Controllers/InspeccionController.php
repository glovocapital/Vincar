<?php

namespace App\Http\Controllers;

use App\Inspeccion;
use Illuminate\Http\Request;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\CheckSession;
use Auth;
use App\Empresa;
use App\User;
use App\Vin;
use Illuminate\Support\Facades\Crypt;
use DB;

class InspeccionController extends Controller
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
        $inspecciones = Inspeccion::all();

        $responsable = Auth::user();
        $responsable_nombres = $responsable->user_nombre.' '.$responsable->user_apellido;
        
        $vins = Vin::select('vin_id', 'vin_codigo')
            ->orderBy('vin_id')
            ->pluck('vin_codigo', 'vin_id')
            ->all();

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

        return view('inspeccion.index', compact('responsable', 'responsable_nombres', 'inspecciones', 'vins','users','empresas', 'estadosInventario', 'subEstadosInventario'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $responsable = Auth::user();
        $responsable_nombres = $responsable->user_nombre.' '.$responsable->user_apellido;

        $vins = Vin::select('vin_id', 'vin_codigo')
            ->orderBy('vin_id')
            ->pluck('vin_codigo', 'vin_id')
            ->all();

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

        $tipoDanos = DB::table('tipo_danos')
            ->select('tipo_dano_id', 'tipo_dano_descripcion')
            ->pluck('tipo_dano_descripcion', 'tipo_dano_id');
        
        $gravedades = DB::table('gravedades')
            ->select('gravedad_id', 'gravedad_descripcion')
            ->pluck('gravedad_descripcion', 'gravedad_id');

        $subAreas = DB::table('pieza_sub_areas')
            ->select('pieza_sub_area_id', 'pieza_sub_area_desc')
            ->pluck('pieza_sub_area_desc', 'pieza_sub_area_id');
        
        $piezaCategorias = DB::table('categoria_piezas')
            ->select('categoria_pieza_id', 'categoria_pieza_desc')
            ->pluck('categoria_pieza_desc', 'categoria_pieza_id');
        
        $piezaSubCategorias = DB::table('subcategoria_piezas')
            ->select('subcategoria_pieza_id', 'subcategoria_pieza_desc')
            ->pluck('subcategoria_pieza_desc', 'subcategoria_pieza_id');

        $piezas = DB::table('piezas')
            ->select('pieza_id', 'pieza_descripcion')
            ->pluck('pieza_descripcion', 'pieza_id');

        return view('inspeccion.create', compact('responsable', 'responsable_nombres', 'users', 'vins','empresas', 'estadosInventario', 'subEstadosInventario', 'tipoDanos', 'gravedades', 'subAreas', 'piezaCategorias', 'piezaSubCategorias', 'piezas'));
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
     * @param  \App\Inspeccion  $inspeccion
     * @return \Illuminate\Http\Response
     */
    public function show(Inspeccion $inspeccion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Inspeccion  $inspeccion
     * @return \Illuminate\Http\Response
     */
    public function edit(Inspeccion $inspeccion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Inspeccion  $inspeccion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Inspeccion $inspeccion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Inspeccion  $inspeccion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inspeccion $inspeccion)
    {
        //
    }
}
