<?php

namespace App\Http\Controllers;

use App\Marca;
use Illuminate\Http\Request;
use DB;
use Crypt;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $marca_vehiculo = Marca::all();


        return view('marca.index', compact('marca_vehiculo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('marca.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = DB::table('marcas')
        ->where('marca_nombre', $request->marca_nombre)
        ->orWhere('marca_codigo', $request->marca_codigo)
        ->exists();

        if($validate == true)
        {
            flash('la marca '.$request->marca_nombre.'  ya existe en la base de datos')->warning();
            return redirect('/marcas');
        }else


        try {

            $marca = new Marca();
            $marca->marca_nombre = $request->marca_nombre;
            $marca->marca_codigo =$request->marca_codigo;
            $marca->save();

            flash('La marca se creo correctamente.')->success();
            return redirect('marcas');

        }catch (\Exception $e) {

            flash('Error al crear la marca.')->error();
           //flash($e->getMessage())->error();
            return redirect('marcas');
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
        $marca_id =  Crypt::decrypt($id);
        $marca = Marca::findOrfail($marca_id);


        return view('marca.edit', compact('marca'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {try {

        $marca_id =  Crypt::decrypt($id);
        $marca = Marca::findOrfail($marca_id);

        $marca->marca_nombre = $request->marca_nombre;
        $marca->marca_codigo =$request->marca_codigo;
        $marca->save();

        flash('La marca se actualizo correctamente.')->success();
        return redirect('marcas');

    }catch (\Exception $e) {

        flash('Error al actualizar la marca.')->error();
       //flash($e->getMessage())->error();
        return redirect('marcas');
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
        $marca_id =  Crypt::decrypt($id);

        try {
            $marca = Marca::findOrfail($marca_id)->delete();

            flash('Los datos de la marca han sido eliminados satisfactoriamente.')->success();
            return redirect('marcas');
        }catch (\Exception $e) {


            flash('Error al intentar eliminar los datos de la marca.')->error();
            //flash($e->getMessage())->error();
            return redirect('marcas');
        }
    }
}
