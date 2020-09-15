<?php

namespace App\Http\Controllers;

use App\Empresa;
use App\Marca;
use App\Remolque;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\CheckSession;
use App\Http\Requests\CrearRemolqueRequest;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RemolqueController extends Controller
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
        $remolques = Remolque::all(); 

        $empresas = Empresa::select('empresa_id', 'empresa_razon_social')
            ->orderBy('empresa_razon_social')
            ->pluck('empresa_razon_social', 'empresa_id');

        $marcas = Marca::select('marca_id', 'marca_nombre')
            ->orderBy('marca_nombre')
            ->pluck('marca_nombre', 'marca_id');

        return view('remolque.index', compact('remolques','empresas', 'marcas'));
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
            ->where('deleted_at', null)
            ->pluck('empresa_razon_social', 'empresa_id');


        return view('remolque.index', compact('remolque', 'empresa'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CrearRemolqueRequest $request)
    {
        $fotoRemolque = $request->file('remolque_foto_documento');
        $extensionFoto = $fotoRemolque->extension();
        $path = $fotoRemolque->storeAs(
            'documentosRemolque',
            "foto de documento ".'- '.Auth::id().' - '.date('Y-m-d').' - '.\Carbon\Carbon::now()->timestamp.'.'.$extensionFoto
        );

        try {

            $remolque = new Remolque();

            $remolque->remolque_patente = $request->remolque_patente;
            $remolque->remolque_modelo = $request->remolque_modelo;
            $remolque->remolque_marca = $request->marca_id;
            $remolque->remolque_anio = $request->remolque_anio;
            $remolque->remolque_fecha_circulacion = $request->remolque_fecha_circulacion;
            $remolque->remolque_fecha_revision = $request->remolque_fecha_revision;
            $remolque->remolque_capacidad = $request->remolque_capacidad;
            $remolque->empresa_id = $request->empresa_id;
            $remolque->remolque_foto_documentos = $path;

            $remolque->save();

            flash('Remolque registrado correctamente.')->success();
            return redirect('remolque');

        }catch (\Exception $e) {

            flash('Error al crear el remolque.')->error();
           //flash($e->getMessage())->error();
            return redirect('remolque');
        }
    }

    public function download($id)
    {
        $remolque_id =  Crypt::decrypt($id);
        $remolque = Remolque::findOrfail($remolque_id);
        
        $name = $remolque->remolque_foto_documentos;
        if(!is_null($name))
        {
            return Storage::download("$name");

        }else{
            flash('No se encontro documentación asociada al camión.')->error();
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

        $empresas = Empresa::select('empresa_id', 'empresa_razon_social')
            ->orderBy('empresa_razon_social')
            ->pluck('empresa_razon_social', 'empresa_id');

        $marcas = Marca::select('marca_id', 'marca_nombre')
            ->orderBy('marca_nombre')
            ->pluck('marca_nombre', 'marca_id');

        return view('remolque.edit', compact('remolque', 'empresas', 'marcas'));

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
            $remolque->remolque_marca = $request->marca_id;
            $remolque->remolque_anio = $request->remolque_anio;
            $remolque->empresa_id = $request->empresa_id;
            $remolque->remolque_capacidad = $request->remolque_capacidad;
            $remolque->remolque_fecha_circulacion = $request->remolque_fecha_circulacion;
            $remolque->remolque_fecha_revision = $request->remolque_fecha_revision;

            if(!is_null($request->remolque_foto_documento)){
                $fotoRemolque = $request->file('remolque_foto_documento');
                $extensionFoto = $fotoRemolque->extension();
    
                $path = $fotoRemolque->storeAs(
                    'documentosRemolque',
                    "foto de documento ".'- '.Auth::id().' - '.date('Y-m-d').' - '.\Carbon\Carbon::now()->timestamp.'.'.$extensionFoto
                );            
                $remolque->remolque_foto_documentos = $path;
            }

            $remolque->save();

            flash('Remolque actualizado correctamente.')->success();
            return redirect('remolque');

        }catch (\Exception $e) {

            flash('Error al actualizar el remolque.')->error();
           //flash($e->getMessage())->error();
            return redirect('remolque');
        }
    }

    public function trash($id){
        $remolque_id =  Crypt::decrypt($id);

        try{
            $remolque = Remolque::where('remolque_id', $remolque_id)->firstOrFail();
        
            $remolque->delete();

            flash('Los datos del remolque han sido eliminados satisfactoriamente.')->success();
            return redirect('remolque');
        }catch (\Exception $e) {

            flash('Error al intentar eliminación de los datos del remolque.')->error();
            //flash($e->getMessage())->error();
            return redirect('remolque');
        }
    }

    public function restore($id){
        $remolque_id =  Crypt::decrypt($id);

        try{
            $remolque = Remolque::onlyTrashed()->where('remolque_id', $remolque_id)->firstOrFail();
            
            $remolque->restore();

            flash('Datos del remolque restaurados satisfactoriamente.')->success();
            return redirect('remolque');
        }catch (\Exception $e) {

            flash('Error al intentar restaurar los datos del remolque.')->error();
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
            $remolque = Remolque::onlyTrashed()->where('remolque_id', $remolque_id)->firstOrFail();

            $remolque->forceDelete();

            flash('Los datos del remolque han sido eliminados definitivamente.')->success();
            return redirect('remolque');
        }catch (\Exception $e) {

            flash('Error al intentar eliminación definitiva del remolque.')->error();
            //flash($e->getMessage())->error();
            return redirect('remolque');
        }
    }
}
