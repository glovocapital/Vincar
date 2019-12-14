<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Crypt;
use App\Http\Middleware\CheckSession;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Camion;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

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


        $empresa = DB::table('empresas')
        ->select('empresa_id', 'empresa_razon_social')
        ->pluck('empresa_razon_social', 'empresa_id');

        $camion = Camion::all();
        return view('camion.index', compact('camion','empresa'));
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
            ->pluck('empresa_razon_social', 'empresa_id');


        return view('camion.index', compact('empresa', 'camion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
            $camion->camion_marca = $request->camion_marca;
            $camion->camion_anio = $request->camion_anio;
            $camion->camion_fecha_circulacion = $request->camion_fecha_circulacion;
            $camion->camion_fecha_revision = $request->camion_fecha_revision;
            $camion->empresa_id = $request->empresa_id;
            $camion->camion_foto_documentos = $path;

            $camion->save();

            flash('La camión se creo correctamente.')->success();
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
        $camiones = Camion::findOrfail($camion_id);

        $empresa = DB::table('empresas')
        ->select('empresa_id', 'empresa_razon_social')
        ->pluck('empresa_razon_social', 'empresa_id');

        return view('camion.edit', compact('camiones', 'empresa'));
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

        if(is_null($request->camion_foto_documento)){

            try {
                $camion->camion_patente = $request->camion_patente;
                $camion->camion_modelo = $request->camion_modelo;
                $camion->camion_marca = $request->camion_marca;
                $camion->camion_anio = $request->camion_anio;
                $camion->camion_fecha_circulacion = $request->camion_fecha_circulacion;
                $camion->camion_fecha_revision = $request->camion_fecha_revision;
                $camion->empresa_id = $request->empresa_id;
                //$camion->camion_foto_documentos = $path;
                $camion->save();

                flash('La camión se edito correctamente.')->success();
                return redirect('camiones');

            }catch (\Exception $e) {

                flash('Error al editar el camión.')->error();
            flash($e->getMessage())->error();
                return redirect('camiones');
            }
        }else{

            $fotoCamion = $request->file('camion_foto_documento');
            $extensionFoto = $fotoCamion->extension();
            $path = $fotoCamion->storeAs(
                'documentosCamion',
                "foto de documento ".'- '.Auth::id().' - '.date('Y-m-d').' - '.\Carbon\Carbon::now()->timestamp.'.'.$extensionFoto
            );

            try {
                $camion->camion_patente = $request->camion_patente;
                $camion->camion_modelo = $request->camion_modelo;
                $camion->camion_marca = $request->camion_marca;
                $camion->camion_anio = $request->camion_anio;
                $camion->camion_fecha_circulacion = $request->camion_fecha_circulacion;
                $camion->camion_fecha_revision = $request->camion_fecha_revision;
                $camion->empresa_id = $request->empresa_id;
                $camion->camion_foto_documentos = $path;
                $camion->save();

                flash('La camión se edito correctamente.')->success();
                return redirect('camiones');

            }catch (\Exception $e) {

                flash('Error al editar el camión.')->error();
            flash($e->getMessage())->error();
                return redirect('camiones');
            }

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
            $camion = Camion::findOrfail($camion_id)->delete();

            flash('Los datos del camión han sido eliminados satisfactoriamente.')->success();
            return redirect('camiones');
        }catch (\Exception $e) {

            flash('Error al intentar eliminar los datos del Camión.')->error();
            //flash($e->getMessage())->error();
            return redirect('camiones');
        }
    }
}
