<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\CheckSession;
use App\Inspeccion;
use App\DanoPieza;
use App\Foto;
use App\Thumbnail;
use App\Empresa;
use App\Http\Requests\DanoPiezaCreateRequest;
use App\Http\Requests\InspeccionCreateRequest;
use App\User;
use App\Vin;
use DateTime;
use Illuminate\Support\Facades\Crypt;
use Auth;
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
            ->where('deleted_at', null)
            ->pluck('user_nombres', 'user_id')
            ->all();

        $empresas = Empresa::select('empresa_id', 'empresa_razon_social')
            ->orderBy('empresa_id')
            ->where('deleted_at', null)
            ->pluck('empresa_razon_social', 'empresa_id')
            ->all();

        $estadosInventario = DB::table('vin_estado_inventarios')
            ->select('vin_estado_inventario_id', 'vin_estado_inventario_desc')
            ->pluck('vin_estado_inventario_desc', 'vin_estado_inventario_id');

        $subEstadosInventario = DB::table('vin_sub_estado_inventarios')
            ->select('vin_sub_estado_inventario_id', 'vin_sub_estado_inventario_desc')
            ->pluck('vin_sub_estado_inventario_desc', 'vin_sub_estado_inventario_id');
        
        $danosArray = []; 
        foreach($inspecciones as $inspeccion){
            $danos = DanoPieza::where('inspeccion_id', $inspeccion->inspeccion_id)
                ->get();
            foreach($danos as $dano){
                array_push($danosArray, $dano);
            }
        }

        // dd($danosArray);

        return view('inspeccion.index', compact('responsable', 'responsable_nombres', 'inspecciones', 'vins','users','empresas', 'estadosInventario', 'subEstadosInventario', 'danosArray'));
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
            ->where('deleted_at', null)
            ->pluck('user_nombres', 'user_id')
            ->all();

        $empresas = Empresa::select('empresa_id', 'empresa_razon_social')
            ->orderBy('empresa_id')
            ->where('deleted_at', null)
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
            ->where('deleted_at', null)
            ->pluck('pieza_descripcion', 'pieza_id');

        return view('inspeccion.create', compact('responsable', 'responsable_nombres', 'users', 'vins','empresas', 'estadosInventario', 'subEstadosInventario', 'tipoDanos', 'gravedades', 'subAreas', 'piezaCategorias', 'piezaSubCategorias', 'piezas'));
    }

    // Continuar a partir de acá para arreglar el manejo de Categorías, Subcategorías y piezas.

    public function subcategorias(Request $request, $id_categoria){
        try {
            $categoria_id = Crypt::decrypt($id_categoria);
        } catch (DecryptException $e) {
            abort(404);
        }

        if ($request->ajax()){
            $subcategorias = DB::table('subcategoria_piezas')
                ->where('subcategoria_piezas.categoria_pieza_id', '=', $categoria_id)
                ->select('subcategoria_piezas.subcategoria_pieza_desc', 'subcategoria_piezas.subcategoria_pieza_id')
                ->orderBy('subcategoria_piezas.subcategoria_pieza_id')
                ->pluck('subcategoria_piezas.subcategoria_pieza_desc', 'subcategoria_piezas.subcategoria_pieza_id');

            $ids = DB::table('subcategoria_piezas')
                ->where('subcategoria_piezas.categoria_pieza_id', '=', $categoria_id)
                ->select('subcategoria_piezas.subcategoria_pieza_desc', 'subcategoria_piezas.subcategoria_pieza_id')
                ->orderBy('subcategoria_piezas.subcategoria_pieza_id')
                ->pluck('subcategoria_piezas.subcategoria_pieza_id', 'subcategoria_piezas.subcategoria_pieza_desc');

            return response()->json([
                'success' => true,
                'message' => "Data de subcategorías de piezas por cada categoría disponible",
                'ids' => $ids,
                'subcategorias' => $subcategorias,
            ]);
        }
    }

    public function piezas(Request $request, $id_subcategoria){
	    // try {
        //     $subcategoria_id = Crypt::decrypt($id_subcategoria);
        // } catch (DecryptException $e) {
        //     abort(404);
        // }

        $subcategoria_id = $id_subcategoria;

        if ($request->ajax()){
            $piezas = DB::table('piezas')
                ->where('piezas.subcategoria_pieza_id', '=', $subcategoria_id)
                ->select('piezas.pieza_id', 'piezas.pieza_descripcion')
                ->orderBy('piezas.pieza_id')
                ->pluck('piezas.pieza_descripcion', 'piezas.pieza_id');

            $ids = DB::table('piezas')
                ->where('piezas.subcategoria_pieza_id', '=', $subcategoria_id)
                ->select('piezas.pieza_id', 'piezas.pieza_descripcion')
                ->orderBy('piezas.pieza_id')
                ->pluck('piezas.pieza_id', 'piezas.pieza_descripcion');

            return response()->json([
                'success' => true,
                'message' => "Data de piezas disponible",
                'ids' => $ids,
                'piezas' => $piezas,
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InspeccionCreateRequest $request)
    {
        try {
            DB::beginTransaction();
            $datosInspeccion = $request->input('inspeccion');

            $id_vin_estado_inventario = Crypt::decrypt($datosInspeccion['vin_estado_inventario_id']);

            $inspeccion = new Inspeccion();
            $inspeccion->inspeccion_fecha = $datosInspeccion['inspeccion_fecha'];
            $inspeccion->responsable_id = $datosInspeccion['responsable_id'];
            $inspeccion->vin_id = $datosInspeccion['vin_id'];
            $inspeccion->cliente_id = $datosInspeccion['cliente_id'];
            $inspeccion->inspeccion_dano = $datosInspeccion['inspeccion_dano'];
            $inspeccion->vin_estado_inventario_id = $id_vin_estado_inventario;
            $inspeccion->vin_sub_estado_inventario_id = $datosInspeccion['vin_sub_estado_inventario_id'];

            if($inspeccion->save()){
                $vin = Vin::find($datosInspeccion['vin_id']);
                $vin->vin_estado_inventario_id = $inspeccion->vin_estado_inventario_id;
                if(isset($inspeccion->vin_sub_estado_inventario_id)){
                    $vin->vin_sub_estado_inventario_id = $inspeccion->vin_sub_estado_inventario_id;
                }
                $vin->save();

                if ($request->input('submit_2') !== null) {
                    try {

                        $datosDanoPieza = $request->input('dano_pieza');
                        $danoPieza = new DanoPieza();
                        $danoPieza->pieza_id = $datosDanoPieza['pieza_id'];
                        $danoPieza->tipo_dano_id = $datosDanoPieza['tipo_dano_id'];
                        $danoPieza->gravedad_id = $datosDanoPieza['gravedad_id'];
                        $danoPieza->pieza_sub_area_id = $datosDanoPieza['pieza_sub_area_id'];
                        $danoPieza->dano_pieza_observaciones = $datosDanoPieza['dano_pieza_observaciones'];
                        $danoPieza->inspeccion_id = $inspeccion->inspeccion_id;

                        $danoPieza->save();

                        DB::commit();
                        flash('Inspección y Daño Registrados Exitosamente.')->success();
                        return redirect()->route('inspeccion.index');
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        flash('Error anexando daño de pieza. Inspección no almacenada')->error();
                        return redirect()->route('inspeccion.create');
                    }
                } elseif ($request->input('submit_3') !== null){
                    try {

                        $datosDanoPieza = $request->input('dano_pieza');
                        $danoPieza = new DanoPieza();
                        $danoPieza->pieza_id = $datosDanoPieza['pieza_id'];
                        $danoPieza->tipo_dano_id = $datosDanoPieza['tipo_dano_id'];
                        $danoPieza->gravedad_id = $datosDanoPieza['gravedad_id'];
                        $danoPieza->pieza_sub_area_id = $datosDanoPieza['pieza_sub_area_id'];
                        $danoPieza->dano_pieza_observaciones = $datosDanoPieza['dano_pieza_observaciones'];
                        $danoPieza->inspeccion_id = $inspeccion->inspeccion_id;
                        $danoPieza->save();

                        $datosFoto = $request->input('foto');
                        $foto = new Foto();
                        $foto->foto_fecha = $datosFoto['foto_fecha'];
                        $foto->foto_descripcion = $datosFoto['foto_descripcion'];
                        $foto->foto_ubic_archivo = "fotos/";
                        $foto->foto_coord_lat = $datosFoto['foto_coord_lat'];
                        $foto->foto_coord_lon = $datosFoto['foto_coord_lon'];
                        $foto->dano_pieza_id = $danoPieza->dano_pieza_id;
                        $foto->save();

                        $fotoArchivo = $request->file('foto_nombre_archivo');
                        $extensionFoto = $fotoArchivo->extension();
                        $path = $fotoArchivo->storeAs(
                            'fotos',
                            "foto de inspeccion ".'- '.Auth::id().' - '.date('Y-m-d').' - '.\Carbon\Carbon::now()->timestamp.'.'.$extensionFoto
                        );

                        //Creamos una instancia de la libreria instalada   
                        $image = \Image::make($fotoArchivo);

                        //Ruta donde queremos guardar las imagenes
                        $path2 = storage_path().'/app/thumbnails/';
                        
                        // Cambiar de tamaño
                        $image->resize(240,200);
                        
                        // Guardar
                        $image->save($path2.'thumb_'.$fotoArchivo->getClientOriginalName());
                        
                        //Guardamos nombre y nombreOriginal en la BD
                        $thumbnail = new Thumbnail();
                        $thumbnail->thumbnail_nombre = $datosFoto['foto_descripcion'];
                        $thumbnail->thumbnail_imagen = $fotoArchivo->getClientOriginalName();
                        $thumbnail->foto_id = $foto->foto_id;
                        
                        $thumbnail->save();

                        $foto1 = Foto::find($foto->foto_id);

                        $foto1->foto_ubic_archivo = $path;

                        $foto1->save();

                        DB::commit();
                        flash('Inspección, Daño y fotografía Registrados Exitosamente.')->success();
                        return redirect()->route('inspeccion.index');
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        flash('Error anexando fotografía. Inspección no almacenada')->error();
                        return redirect()->route('inspeccion.create');
                    }
                }
            } else {
                DB::rollBack();
                flash('Error. Inspección no almacenada')->error();
                return redirect()->route('inspeccion.create');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            flash('Error. Inspección no almacenada')->error();
            return redirect()->route('inspeccion.create');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createDano($id_inspeccion)
    {
        try {
            $inspeccion_id = Crypt::decrypt($id_inspeccion);
        } catch (DecryptException $e) {
            abort(404);
        }

        $inspeccion = Inspeccion::find($inspeccion_id);

        $responsable = Auth::user();
        $responsable_nombres = $responsable->user_nombre.' '.$responsable->user_apellido;

        $vin = Vin::find($inspeccion->vin_id);

        $cliente = User::find($inspeccion->cliente_id);
        $cliente_nombres = $cliente->user_nombre.' '.$cliente->user_apellido;

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

        return view('inspeccion.create_dano', compact('inspeccion', 'responsable', 'responsable_nombres', 'vin', 'cliente_nombres','tipoDanos', 'gravedades', 'subAreas', 'piezaCategorias', 'piezaSubCategorias', 'piezas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeDano(Request $request)
    {
        try {
            DB::beginTransaction();
            
            if ($request->input('submit_2') !== null) {
                try {
                    $datosDanoPieza = $request->input('dano_pieza');
                    $danoPieza = new DanoPieza();
                    $danoPieza->pieza_id = $datosDanoPieza['pieza_id'];
                    $danoPieza->tipo_dano_id = $datosDanoPieza['tipo_dano_id'];
                    $danoPieza->gravedad_id = $datosDanoPieza['gravedad_id'];
                    $danoPieza->pieza_sub_area_id = $datosDanoPieza['pieza_sub_area_id'];
                    $danoPieza->dano_pieza_observaciones = $datosDanoPieza['dano_pieza_observaciones'];
                    $danoPieza->inspeccion_id = $inspeccion->inspeccion_id;

                    $danoPieza->save();

                    DB::commit();
                    flash('Inspección y Daño Registrados Exitosamente.')->success();
                    return redirect()->route('inspeccion.index');
                } catch (\Throwable $th) {
                    DB::rollBack();
                    flash('Error anexando daño de pieza. Inspección no almacenada')->error();
                    return redirect()->route('inspeccion.create');
                }
            } elseif ($request->input('submit_3') !== null){

                try {
                    
                    $datosDanoPieza = $request->input('dano_pieza');
                    $danoPieza = new DanoPieza();
                    $danoPieza->pieza_id = $datosDanoPieza['pieza_id'];
                    $danoPieza->tipo_dano_id = $datosDanoPieza['tipo_dano_id'];
                    $danoPieza->gravedad_id = $datosDanoPieza['gravedad_id'];
                    $danoPieza->pieza_sub_area_id = $datosDanoPieza['pieza_sub_area_id'];
                    $danoPieza->dano_pieza_observaciones = $datosDanoPieza['dano_pieza_observaciones'];
                    $danoPieza->inspeccion_id = $request->inspeccion_id;
                    $danoPieza->save();
                    
                    $datosFoto = $request->input('foto');
                    $foto = new Foto();
                    $foto->foto_fecha = $datosFoto['foto_fecha'];
                    $foto->foto_descripcion = $datosFoto['foto_descripcion'];
                    $foto->foto_ubic_archivo = "fotos/";
                    $foto->foto_coord_lat = $datosFoto['foto_coord_lat'];
                    $foto->foto_coord_lon = $datosFoto['foto_coord_lon'];
                    $foto->dano_pieza_id = $danoPieza->dano_pieza_id;
                    $foto->save();
                    
                    $fotoArchivo = $request->file('foto_nombre_archivo');
                    $extensionFoto = $fotoArchivo->extension();
                    
                    $path = $fotoArchivo->storeAs(
                        'fotos',
                        "foto de inspeccion ".'- '.Auth::id().' - '.date('Y-m-d').' - '.\Carbon\Carbon::now()->timestamp.'.'.$extensionFoto
                    );
                    
                    $foto1 = Foto::find($foto->foto_id);
                    
                    $foto1->foto_ubic_archivo = $path;
                    
                    $foto1->save();
                    
                    DB::commit();
                    flash('Inspección, Daño y fotografía Registrados Exitosamente.')->success();
                    return redirect()->route('inspeccion.index');
                } catch (\Throwable $th) {
                    DB::rollBack();
                    flash('Error anexando fotografía. Inspección no almacenada')->error();
                    return redirect()->route('inspeccion.create');
                }
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            flash('Error. Inspección no almacenada')->error();
            return redirect()->route('inspeccion.create');
        }     
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
    public function edit($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Inspeccion  $inspeccion
     * @return \Illuminate\Http\Response
     */
    public function editDano($id_dano_pieza)
    {
        $dano_pieza_id =  Crypt::decrypt($id_dano_pieza);
        $dano = DanoPieza::find($dano_pieza_id);

        $fotos = Foto::where('dano_pieza_id', $dano_pieza_id)->get();

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

        return view('inspeccion.edit_dano', compact('dano', 'fotos', 'tipoDanos', 'gravedades','subAreas', 'piezaCategorias', 'piezaSubCategorias', 'piezas'));
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Inspeccion  $inspeccion
     * @return \Illuminate\Http\Response
     */
    public function updateDano(Request $request, Inspeccion $inspeccion)
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Inspeccion  $inspeccion
     * @return \Illuminate\Http\Response
     */
    public function destroyDano(Inspeccion $inspeccion)
    {
        //
    }
}
