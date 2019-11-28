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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pais = Pais::all();
        return view('pais.index', compact('pais'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pais.create');
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

        DB::beginTransaction();
        try {

            $pais = new Pais();
            $pais->pais_nombre = $request->pais_nombre;
            $pais->save();

            DB::commit();
            flash('El País se creo correctamente.')->success();
            return redirect('pais');

        }catch (\Exception $e) {

            DB::rollback();

            flash('Error al crear el País.')->error();
           flash($e->getMessage())->error();
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


        DB::beginTransaction();
        try {

            $pais->pais_nombre = $request->pais_nombre;
            $pais->save();

            DB::commit();
            flash('El país se actualizó correctamente.')->success();
            return redirect('pais');

        }catch (\Exception $e) {

            DB::rollback();

            flash('Error al actualizar el páis.')->error();
           flash($e->getMessage())->error();
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
        DB::beginTransaction();
        try {
            $pais = Pais::findOrfail($pais_id)->delete();
            DB::commit();
            flash('Los datos del pais han sido eliminados satisfactoriamente.')->success();
            return redirect('pais');
        }catch (\Exception $e) {

            DB::rollback();
            flash('Error al intentar eliminar los datos del  pais.')->error();
            //flash($e->getMessage())->error();
            return redirect('pais');
        }
    }
}
