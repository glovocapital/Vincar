<?php

namespace App\Http\Controllers;

use App\TipoCampania;
use Illuminate\Http\Request;
use App\Http\Middleware\CheckSession;
use App\Http\Middleware\PreventBackHistory;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class TipoCampaniaController extends Controller
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
        $tipoCampanias = TipoCampania::all();

        return view('tipo_campania.index', compact('tipoCampanias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tipo_campania.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tipoCampania = new TipoCampania();

        $tipoCampania->tipo_campania_descripcion = $request->tipo_campania_descripcion;
        
        if($tipoCampania->save()){
            flash('Tipo de Campaña creado exitosamente.')->success();
            
            return redirect()->action('TipoCampaniaController@index');
        } else {
            flash('Error creando nuevo tipo de campaña.')->error();
            
            return redirect()->action('TipoCampaniaController@index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TipoCampania  $tipoCampania
     * @return \Illuminate\Http\Response
     */
    public function show(TipoCampania $tipoCampania)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TipoCampania  $tipoCampania
     * @return \Illuminate\Http\Response
     */
    public function edit($id_tipo_campania)
    {
        try {
            $tipo_campania_id = Crypt::decrypt($id_tipo_campania);
        } catch (DecryptException $e) {
            abort(404);
        }

        $tipoCampania = TipoCampania::findOrFail($tipo_campania_id);

        return view('tipo_campania.edit', compact('tipoCampania'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TipoCampania  $tipoCampania
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $tipoCampania = TipoCampania::findOrFail($request->tipo_campania_id);

        $tipoCampania->tipo_campania_descripcion = $request->tipo_campania_descripcion;
        
        if($tipoCampania->save()){
            flash('Tipo de Campaña creado exitosamente.')->success();
            
            return redirect()->action('TipoCampaniaController@index');
        } else {
            flash('Error creando nuevo tipo de campaña.')->error();
            
            return redirect()->action('TipoCampaniaController@index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TipoCampania  $tipoCampania
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tipo_campania_id =  Crypt::decrypt($id);

        $tipoCampania = TipoCampania::findOrfail($tipo_campania_id);

        $tipoCampania->delete();
        
        try {

            flash('Los datos de la campaña han sido eliminados satisfactoriamente.')->success();
            return redirect()->action('TipoCampaniaController@index');
        }catch (\Exception $e) {

            flash('Error al intentar eliminar los datos de la campaña.')->error();
            return redirect()->action('TipoCampaniaController@index');
        }
    }
}
