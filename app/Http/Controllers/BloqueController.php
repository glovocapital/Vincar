<?php

namespace App\Http\Controllers;

use App\Bloque;
use App\Patio;
use Illuminate\Http\Request;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\CheckSession;
use App\Imports\UbicPatiosImport;
use App\UbicPatio;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class BloqueController extends Controller
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
    public function index($id_patio)
    {
        try {
            $patio_id = Crypt::decrypt($id_patio);
        } catch (DecryptException $e) {
            abort(404);
        }

        $patio = Patio::find($patio_id);

        $patio_nombre = $patio->patio_nombre;

        $bloques = Bloque::where('patio_id',$patio_id)
            ->where('deleted_at', null)
            ->get();

        if($bloques->count() > 0) {
            return view('bloque.index', compact('bloques', 'patio_nombre'));
        } else {
            return view('bloque.noblocks');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id_patio)
    {
        try {
            $patio_id = Crypt::decrypt($id_patio);
        } catch (DecryptException $e) {
            abort(404);
        }

        $patio = Patio::find($patio_id);

        $bloques = Bloque::where('patio_id',$patio_id)
            ->where('deleted_at', null)
            ->get();

        if($bloques->count() < (int)$patio->patio_bloques){
            return view('bloque.create', compact('patio_id'));
        } else {
            return view('bloque.maxblocks');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $bloque = new Bloque();
            $bloque->bloque_nombre = $request->bloque_nombre;
            $bloque->bloque_filas = $request->bloque_filas;
            $bloque->bloque_columnas = $request->bloque_columnas;
            $bloque->patio_id = $request->patio_id;
            
            $bloque->save();

            for ($i=1; $i <= $bloque->bloque_filas; $i++) { 
                for ($j=1; $j <= $bloque->bloque_columnas; $j++) { 
                    $ubic_patio = new UbicPatio();

                    $ubic_patio->ubic_patio_fila = $i;
                    $ubic_patio->ubic_patio_columna = $j;
                    $ubic_patio->bloque_id = $bloque->bloque_id;
                    
                    $ubic_patio->save();
                }
            }

            DB::commit();

            flash('Bloque registrado correctamente.')->success();
            return redirect()->route('bloque.index', ['id_patio' => Crypt::encrypt($request->patio_id)]);

        }catch (\Exception $e) {
            DB::rollBack();
            flash('Error registrando el bloque.')->error();
            flash($e->getMessage())->error();
            return redirect()->route('bloque.index', ['id_patio' => Crypt::encrypt($request->patio_id)]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Bloque  $bloque
     * @return \Illuminate\Http\Response
     */
    public function show(Bloque $bloque)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Bloque  $bloque
     * @return \Illuminate\Http\Response
     */
    public function edit($id_bloque)
    {
        try {
            $bloque_id = Crypt::decrypt($id_bloque);
        } catch (DecryptException $e) {
            abort(404);
        }

        $bloque = Bloque::findOrFail($bloque_id);

        return view('bloque.edit', compact('bloque'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Bloque  $bloque
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id_bloque)
    {
        try {
            $bloque_id = Crypt::decrypt($id_bloque);
        } catch (DecryptException $e) {
            abort(404);
        }

        $bloque = Bloque::findOrFail($bloque_id);

        try {
            
            $filas = 0;
            $columnas = 0;
            
            $bloque->bloque_nombre = $request->bloque_nombre;
            
            if($bloque->filas != $request->bloque_filas){
                $bloque->bloque_filas = $request->bloque_filas;
                $filas = $request->bloque_filas;
            }

            if($bloque->columnas != $request->bloque_columnas){
                $bloque->bloque_columnas = $request->bloque_columnas;
                $columnas = $request->bloque_columnas;
            }

            $bloque->patio_id = $request->patio_id;

            $bloque->save();



            flash('Bloque modificado correctamente.')->success();
            return redirect()->route('bloque.index', ['id_patio' => Crypt::encrypt($request->patio_id)]);

        }catch (\Exception $e) {

            flash('Error modificando el bloque.')->error();
            flash($e->getMessage())->error();
            return redirect()->route('bloque.index', ['id_patio' => Crypt::encrypt($request->patio_id)]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Bloque  $bloque
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bloque_id =  Crypt::decrypt($id);

        $bloque = Bloque::findOrfail($bloque_id);

        $patio_id = $bloque->patio_id;

        $bloque->delete();
        
        try {

            flash('Los datos del Bloque han sido eliminados satisfactoriamente.')->success();
            return redirect()->route('bloque.index', ['id_patio' => Crypt::encrypt($patio_id)]);
        }catch (\Exception $e) {

            flash('Error al intentar eliminar los datos del Bloque.')->error();
            //flash($e->getMessage())->error();
            return redirect()->route('bloque.index', ['id_patio' => Crypt::encrypt($patio_id)]);
        }
    }
}
