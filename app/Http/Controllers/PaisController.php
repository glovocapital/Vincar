<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pais;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\CheckSession;
use Illuminate\Support\Facades\Crypt;
use DB;

class PaisController extends Controller
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
        $paises = Pais::all();

        return view('pais.index', compact('paises'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pais.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = DB::table('paises')->where('pais_nombre', $request->pais_nombre)->exists();

        if($validate == true)
        {
            flash('El País '.$request->pais_nombre.'  ya existe en la base de datos')->warning();
            return redirect('/pais');
        }else


        try {

            $pais = new Pais();
            $pais->pais_nombre = $request->pais_nombre;
            $pais->save();

            flash('El País se creo correctamente.')->success();
            return redirect('pais');

        }catch (\Exception $e) {



            flash('Error al crear el País.')->error();
           //flash($e->getMessage())->error();
            return redirect('pais');
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
        $pais_id =  Crypt::decrypt($id);
        $pais = Pais::findOrfail($pais_id);

        return view('pais.edit', compact('pais'));
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
        $pais_id =  Crypt::decrypt($id);
        $pais =  Pais::findOrfail($pais_id);

        try {

            $pais->pais_nombre = $request->pais_nombre;
            $pais->save();

            flash('El país se actualizó correctamente.')->success();
            return redirect('pais');

        }catch (\Exception $e) {

            flash('Error al actualizar el páis.')->error();
            //flash($e->getMessage())->error();
            return redirect('pais');
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
        $pais_id =  Crypt::decrypt($id);

        try {
            $pais = Pais::findOrfail($pais_id)->delete();

            flash('Los datos del pais han sido eliminados satisfactoriamente.')->success();
            return redirect('pais');
        }catch (\Exception $e) {


            flash('Error al intentar eliminar los datos del  pais.')->error();
            //flash($e->getMessage())->error();
            return redirect('pais');
        }
    }
}
