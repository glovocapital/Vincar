<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Conductor;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class ConductorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $conductor = Conductor::all();

        $usuario = DB::table('users')
        ->select(DB::raw("CONCAT(user_nombre,' ',user_apellido) AS nombre"),'user_id')
        ->where('rol_id', 4)
        ->pluck('nombre', 'user_id');


        $tipo_licencia = DB::table('tipo_licencias')
        ->select('tipo_licencia_id','tipo_licencia_nombre')
        ->pluck('tipo_licencia_nombre','tipo_licencia_id');

        return view('conductor.index', compact('conductor','usuario','tipo_licencia'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $conductor = Conductor::all();

        $usuario = DB::table('users')
        ->select(DB::raw("CONCAT(user_nombre,' ',user_apellido) AS nombre"),'user_id')
        ->where('rol_id', 4)
        ->pluck('nombre', 'user_id');



        $tipo_licencia = DB::table('tipo_licencias')
        ->select('tipo_licencia_id','tipo_licencia_nombre')
        ->pluck('tipo_licencia_nombre','tipo_licencia_id');

        return view('conductor.index', compact('usuario','conductor','tipo_licencia'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = DB::table('conductors')->where('user_id', $request->user_id)->exists();

        if($validate == true)
        {
            flash('El País '.$request->pais_nombre.'  ya existe en la base de datos')->warning();
            return redirect('conductores');
        }else

        $fotoLicencia = $request->file('conductor_foto_documento');
        $extensionFoto = $fotoLicencia->extension();
        $path = $fotoLicencia->storeAs(
            'documentosConductor',
            "foto de documento ".'- '.Auth::id().' - '.date('Y-m-d').' - '.\Carbon\Carbon::now()->timestamp.'.'.$extensionFoto
        );
        try {

            $conductor = new Conductor();
            $conductor->user_id = $request->user_id;
            $conductor->tipo_licencia_id = $request->tipo_licencia_id;
            $conductor->conductor_fecha_vencimiento = $request->conductor_fecha_vencimiento;
            $conductor->conductor_foto_documento = $path;
            $conductor->save();

            flash('El conductor se creo correctamente.')->success();
            return redirect('conductores');

        }catch (\Exception $e) {

            flash('Error al crear el conductor.')->error();
            flash($e->getMessage())->error();
            return redirect('conductores');
        }
    }

    public function download($id)
    {
        $conductor_id =  Crypt::decrypt($id);
        $conductor = Conductor::findOrfail($conductor_id);
        $name = $conductor->conductor_foto_documento;
        if(!is_null($name))
        {
            return Storage::download("$name");

        }else{
            flash('No se encontro documentación asociada al conductor.')->error();
            return redirect('conductores');

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
        $conductor_id =  Crypt::decrypt($id);
        $conductor = Conductor::findOrfail($conductor_id);

        $usuario = DB::table('users')
        ->select(DB::raw("CONCAT(user_nombre,' ',user_apellido) AS nombre"),'user_id')
        ->where('rol_id', 4)
        ->pluck('nombre', 'user_id');


        $tipo_licencia = DB::table('tipo_licencias')
        ->select('tipo_licencia_id','tipo_licencia_nombre')
        ->pluck('tipo_licencia_nombre','tipo_licencia_id');

        return view('conductor.edit', compact('conductor','usuario','tipo_licencia'));

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
        $conductor_id =  Crypt::decrypt($id);
        $conductor =  Conductor::findOrfail($conductor_id);

        if(is_null($request->conductor_foto_documento)){

            try {

                $conductor->user_id = $request->user_id;
                $conductor->tipo_licencia_id = $request->tipo_licencia_id;
                $conductor->conductor_fecha_vencimiento = $request->conductor_fecha_vencimiento;
                $conductor->save();


                flash('Los datos del conductor se editaron correctamente.')->success();
                return redirect('conductores');

            }catch (\Exception $e) {

                flash('Error al editar el conductor.')->error();
               //flash($e->getMessage())->error();
                return redirect('conductores');
            }

        }else{

            $fotoLicencia = $request->file('conductor_foto_documento');
            $extensionFoto = $fotoLicencia->extension();
            $path = $fotoLicencia->storeAs(
                'documentosConductor',
                "foto de documento ".'- '.Auth::id().' - '.date('Y-m-d').' - '.\Carbon\Carbon::now()->timestamp.'.'.$extensionFoto
            );

            try {
                $conductor->user_id = $request->user_id;
                $conductor->tipo_licencia_id = $request->tipo_licencia_id;
                $conductor->conductor_fecha_vencimiento = $request->conductor_fecha_vencimiento;
                $conductor->conductor_foto_documento = $path;
                $conductor->save();


                flash('Los datos del conductor se editaron correctamente.')->success();
                return redirect('conductores');

            }catch (\Exception $e) {

                flash('Error al editar el conductor.')->error();
               //flash($e->getMessage())->error();
                return redirect('conductores');
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
        $conductor_id =  Crypt::decrypt($id);

        try {
            $conductor = Conductor::findOrfail($conductor_id)->delete();

            flash('Los datos del conductor  han sido eliminados satisfactoriamente.')->success();
            return redirect('conductores');
        }catch (\Exception $e) {

            flash('Error al intentar eliminar los datos del conductor.')->error();
            //flash($e->getMessage())->error();
            return redirect('conductores');
        }
    }
}
