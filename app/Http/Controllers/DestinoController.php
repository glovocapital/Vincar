<?php

namespace App\Http\Controllers;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\CheckSession;
use Illuminate\Support\Facades\Crypt;
use DB;
use App\Destino;

use Illuminate\Http\Request;

class DestinoController extends Controller
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
        $destino = Destino::all();
        return view('destinos.index', compact('destino'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('destinos.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = DB::table('destinos')
        ->where('destino_nombre', $request->destino_nombre)
        ->orWhere('destino_codigo', $request->destino_codigo)
        ->exists();

        if($validate == true)
        {
            flash('El nombre del destino '.$request->destino_nombre.' o el código del destino '.$request->destino_código.'  ya existe en la base de datos')->warning();
            return redirect('/destinos');
        }else

        try {

            $destino = new Destino();
            $destino->destino_nombre = $request->destino_nombre;
            $destino->destino_codigo = $request->destino_codigo;
            $destino->save();


            flash('El Destino se creo correctamente.')->success();
            return redirect('destinos');

        }catch (\Exception $e) {



            flash('Error al crear el Destino.')->error();
           //flash($e->getMessage())->error();
            return redirect('destinos');
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
        $destino_id =  Crypt::decrypt($id);
        $destino = Destino::findOrfail($destino_id);

        return view('destinos.edit', compact('destino'));
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
        $destino_id =  Crypt::decrypt($id);
        $destino =  Destino::findOrfail($destino_id);

        try {

            $destino->destino_codigo = $request->destino_codigo;
            $destino->destino_nombre = $request->destino_nombre;
            $destino->save();

            flash('El destino se actualizó correctamente.')->success();
            return redirect('destinos');

        }catch (\Exception $e) {

            flash('Error al actualizar el destino.')->error();
           //flash($e->getMessage())->error();
            return redirect('destinos');
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
        $destino_id =  Crypt::decrypt($id);

        try {
            $destino = Destino::findOrfail($destino_id)->delete();

            flash('Los datos del destino han sido eliminados satisfactoriamente.')->success();
            return redirect('destinos');
        }catch (\Exception $e) {


            flash('Error al intentar eliminar los datos del destino.')->error();
            //flash($e->getMessage())->error();
            return redirect('destinos');
        }
    }
}
