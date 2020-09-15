<?php

namespace App\Http\Controllers;

use App\Camion;
use App\Empresa;
use App\Marca;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\CheckSession;
use App\Http\Requests\CrearCamionRequest;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CamionesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(CheckSession::class);
    }




    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $camiones = Camion::all();

        $empresas = Empresa::select('empresa_id', 'empresa_razon_social')
            ->orderBy('empresa_razon_social')
            ->pluck('empresa_razon_social', 'empresa_id');

        $marcas = Marca::select('marca_id', 'marca_nombre')
            ->orderBy('marca_nombre')
            ->pluck('marca_nombre', 'marca_id');

        return view('camion.index', compact('camiones', 'empresas', 'marcas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $camion = Camion::all();
        $empresa = DB::table('empresas')
            ->select('empresa_id', 'empresa_razon_social')
            ->where('deleted_at', null)
            ->pluck('empresa_razon_social', 'empresa_id');


        return view('camion.index', compact('empresa', 'camion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CrearCamionRequest $request)
    {
        $fotoCamion = $request->file('camion_foto_documento');
        $extensionFoto = $fotoCamion->extension();
        $path = $fotoCamion->storeAs(
            'documentosCamion',
            "foto de documento ".'- '.Auth::id().' - '.date('Y-m-d').' - '.\Carbon\Carbon::now()->timestamp.'.'.$extensionFoto
        );

        try {

            $camion = new Camion();
            
            $camion->camion_patente = $request->camion_patente;
            $camion->camion_modelo = $request->camion_modelo;
            $camion->camion_marca = $request->marca_id;
            $camion->camion_anio = $request->camion_anio;
            $camion->camion_fecha_circulacion = $request->camion_fecha_circulacion;
            $camion->camion_fecha_revision = $request->camion_fecha_revision;
            $camion->empresa_id = $request->empresa_id;
            $camion->camion_foto_documentos = $path;

            $camion->save();

            flash('Camión registrado correctamente.')->success();
            return redirect('camiones');

        }catch (\Exception $e) {

            flash('Error al crear el camión.')->error();
            //flash($e->getMessage())->error();
            return redirect('camiones');
        }
    }

    public function download($id)
    {
        $camion_id =  Crypt::decrypt($id);
        $camiones = Camion::findOrfail($camion_id);
        $name = $camiones->camion_foto_documentos;
        if(!is_null($name))
        {
            return Storage::download("$name");

        }else{
            flash('No se encontro documentación asociada al camión.')->error();
            return redirect('camiones');

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
        $camion_id =  Crypt::decrypt($id);
        $camion = Camion::findOrfail($camion_id);

        $empresas = Empresa::select('empresa_id', 'empresa_razon_social')
            ->orderBy('empresa_razon_social')
            ->pluck('empresa_razon_social', 'empresa_id');

        $marcas = Marca::select('marca_id', 'marca_nombre')
            ->orderBy('marca_nombre')
            ->pluck('marca_nombre', 'marca_id');

        return view('camion.edit', compact('camion', 'empresas', 'marcas'));
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
        $camion_id =  Crypt::decrypt($id);
        $camion =  Camion::findOrfail($camion_id);

        try {
            $camion->camion_patente = $request->camion_patente;
            $camion->camion_modelo = $request->camion_modelo;
            $camion->camion_marca = $request->marca_id;
            $camion->camion_anio = $request->camion_anio;
            $camion->camion_fecha_circulacion = $request->camion_fecha_circulacion;
            $camion->camion_fecha_revision = $request->camion_fecha_revision;
            $camion->empresa_id = $request->empresa_id;
            
            if(!is_null($request->camion_foto_documento)){
                $fotoCamion = $request->file('camion_foto_documento');
                $extensionFoto = $fotoCamion->extension();
                $path = $fotoCamion->storeAs(
                    'documentosCamion',
                    "foto de documento ".'- '.Auth::id().' - '.date('Y-m-d').' - '.\Carbon\Carbon::now()->timestamp.'.'.$extensionFoto
                );
                $camion->camion_foto_documentos = $path;
            }

            $camion->save();

            flash('Camión actualizado correctamente.')->success();
            return redirect('camiones');

        }catch (\Exception $e) {

            flash('Error al actualizar el camión.')->error();
           // flash($e->getMessage())->error();
            return redirect('camiones');
        }
    }

    public function trash($id){
        $camion_id =  Crypt::decrypt($id);

        try{
            $camion = Camion::where('camion_id', $camion_id)->firstOrFail();
        
            $camion->delete();

            flash('Los datos del camión han sido eliminados satisfactoriamente.')->success();
            return redirect('camiones');
        }catch (\Exception $e) {

            flash('Error al intentar eliminación de los datos del Camión.')->error();
            //flash($e->getMessage())->error();
            return redirect('camiones');
        }
    }

    public function restore($id){
        $camion_id =  Crypt::decrypt($id);

        try{
            $camion = Camion::onlyTrashed()->where('camion_id', $camion_id)->firstOrFail();
            
            $camkion->restore();

            flash('Datos del camión restaurados satisfactoriamente.')->success();
            return redirect('camiones');
        }catch (\Exception $e) {

            flash('Error al intentar restaurar los datos del Camión.')->error();
            //flash($e->getMessage())->error();
            return redirect('camiones');
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
        $camion_id =  Crypt::decrypt($id);

        try {
            $camion = Camion::onlyTrashed()->where('camion_id', $camion_id)->firstOrFail();

            $camion->forceDelete();

            flash('Los datos del camión han sido eliminados definitivamente.')->success();
            return redirect('camiones');
        }catch (\Exception $e) {

            flash('Error al intentar eliminación definitiva del Camión.')->error();
            //flash($e->getMessage())->error();
            return redirect('camiones');
        }
    }
}
