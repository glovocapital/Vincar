<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Modelo;
use Illuminate\Support\Facades\Crypt;

class ModeloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $modelo = Modelo::all();

        $marca = DB::table('marcas')
        ->select('marca_id','marca_nombre')
        ->where('deleted_at', null)
        ->pluck('marca_nombre','marca_id');

        return view('modelo.index', compact('marca','modelo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $modelo = Modelo::all();

        $marca = DB::table('marcas')
        ->select('marca_id','marca_nombre')
        ->where('deleted_at', null)
        ->pluck('marca_nombre','marca_id');

        return view('modelo.index', compact('marca','modelo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try{

            $modelo = new Modelo();
            $modelo->marca_id = $request->marca_id;
            $modelo->modelo_nombre = $request->modelo_nombre;
            $modelo->modelo_alias = $request->modelo_alias;
            $modelo->modelo_tipo = $request->modelo_tipo;
            $modelo->save();

            flash('el modelo se creo correctamente.')->success();
            return redirect('modelos');

        }catch (\Exception $e) {


            flash('Error al crear el modelo.')->error();
           //flash($e->getMessage())->error();
            return redirect('modelos');
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
        $modelo_id =  Crypt::decrypt($id);
        $modelo = Modelo::findOrfail($modelo_id);

        $marca = DB::table('marcas')
        ->select('marca_id','marca_nombre')
        ->where('deleted_at', null)
        ->pluck('marca_nombre','marca_id');

        return view('modelo.edit', compact('modelo','marca'));
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
        try{

            $modelo_id =  Crypt::decrypt($id);
            $modelo = Modelo::findOrfail($modelo_id);

            $modelo->marca_id = $request->marca_id;
            $modelo->modelo_nombre = $request->modelo_nombre;
            $modelo->modelo_alias = $request->modelo_alias;
            $modelo->modelo_tipo = $request->modelo_tipo;
            $modelo->save();

            flash('el modelo se modifico correctamente.')->success();
            return redirect('modelos');

        }catch (\Exception $e) {


            flash('Error al modificar el modelo.')->error();
           //flash($e->getMessage())->error();
            return redirect('modelos');
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
        $modelo_id =  Crypt::decrypt($id);

        try {
            $modelo = Modelo::findOrfail($modelo_id)->delete();

            flash('Los datos del modelo han sido eliminados satisfactoriamente.')->success();
            return redirect('modelos');
        }catch (\Exception $e) {


            flash('Error al intentar eliminar los datos del modelo.')->error();
            //flash($e->getMessage())->error();
            return redirect('modelos');
        }

    }

}
