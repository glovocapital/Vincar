<?php

namespace App\Http\Controllers;

use App\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\CheckSession;
use Illuminate\Support\Facades\Crypt;


class ServicioController extends Controller
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

        $servicios = Servicio::all();

        $marca = DB::table('marcas')
        ->select('marca_id', 'marca_nombre')
        ->pluck('marca_nombre', 'marca_id');

        $divisa = DB::table('divisas')
        ->select('divisa_id', 'divisa_tipo')
        ->pluck('divisa_tipo', 'divisa_id');

        $cliente = DB::table('empresas')
        ->select('empresa_id', 'empresa_razon_social')
        ->pluck('empresa_razon_social', 'empresa_id');

        $valor_asociado = DB::table('valores_asociados')
        ->select('valor_asociado_id', 'valor_asociado_tipo')
        ->pluck('valor_asociado_tipo', 'valor_asociado_id');

        $producto = DB::table('productos')
        ->select('producto_id', 'producto_codigo')
        ->pluck('producto_codigo', 'producto_id');



        return view('servicios.index', compact('servicios','marca','divisa','cliente','valor_asociado','producto'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $marca = DB::table('marcas')
        ->select('marca_id', 'marca_nombre')
        ->pluck('marca_nombre', 'marca_id');

        $divisa = DB::table('divisas')
        ->select('divisa_id', 'divisa_tipo')
        ->pluck('divisa_tipo', 'divisa_id');

        $cliente = DB::table('empresas')
        ->select('empresa_id', 'empresa_razon_social')
        ->pluck('empresa_razon_social', 'empresa_id');

        $valor_asociado = DB::table('valores_asociados')
        ->select('valor_asociado_id', 'valor_asociado_tipo')
        ->pluck('valor_asociado_tipo', 'valor_asociado_id');

        $producto = DB::table('productos')
        ->select('producto_id', 'producto_codigo')
        ->pluck('producto_codigo', 'producto_id');


        return view('servicios.index', compact('servicios','marca','divisa','cliente','valor_asociado','producto'));
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

            $servicio = new Servicio();
            $servicio->producto_id = $request->producto_id;
            $servicio->cliente_id = $request->cliente_id;
            $servicio->divisa_id = $request->divisa_id;
            $servicio->marca_id = $request->marca_id;
            $servicio->valor_asociado_id = $request->valor_asociado_id;
            $servicio->servicios_precio = $request->servicio_costo;

            $servicio->save();

        flash('El servicio ha sido registrado correctamente.')->success();
        return redirect('servicios');

        }catch (\Exception $e) {



            flash('Error al crear servicio.')->error();
           // flash($e->getMessage())->error();
            return redirect('servicios');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Servicio  $servicio
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Servicio  $servicio
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $servicio_id =  Crypt::decrypt($id);

        $servicio = Servicio::findOrfail($servicio_id);


        $marca = DB::table('marcas')
        ->select('marca_id', 'marca_nombre')
        ->pluck('marca_nombre', 'marca_id');

        $divisa = DB::table('divisas')
        ->select('divisa_id', 'divisa_tipo')
        ->pluck('divisa_tipo', 'divisa_id');

        $cliente = DB::table('empresas')
        ->select('empresa_id', 'empresa_razon_social')
        ->pluck('empresa_razon_social', 'empresa_id');

        $valor_asociado = DB::table('valores_asociados')
        ->select('valor_asociado_id', 'valor_asociado_tipo')
        ->pluck('valor_asociado_tipo', 'valor_asociado_id');

        $producto = DB::table('productos')
        ->select('producto_id', 'producto_codigo')
        ->pluck('producto_codigo', 'producto_id');

        return view('servicios.edit', compact('servicio','marca','divisa','cliente','valor_asociado','producto'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Servicio  $servicio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $servicio_id =  Crypt::decrypt($id);
        $servicio = Servicio::findOrfail($servicio_id);

        try {
            $servicio->producto_id = $request->producto_id;
            $servicio->cliente_id = $request->cliente_id;
            $servicio->divisa_id = $request->divisa_id;
            $servicio->marca_id = $request->marca_id;
            $servicio->valor_asociado_id = $request->valor_asociado_id;
            $servicio->servicios_precio = $request->servicio_costo;

            $servicio->save();

        flash('El servicio ha sido registrado correctamente.')->success();
        return redirect('servicios');

        }catch (\Exception $e) {
            flash('Error al crear servicio.')->error();
           // flash($e->getMessage())->error();
            return redirect('servicios');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Servicio  $servicio
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $servicio_id =  Crypt::decrypt($id);

        try {
            $servicio = Servicio::findOrfail($servicio_id)->delete();

            flash('Los datos del servicio han sido eliminados satisfactoriamente.')->success();
            return redirect('servicios');
        }catch (\Exception $e) {

            flash('Error al intentar eliminar los datos del servicio.')->error();
            //flash($e->getMessage())->error();
            return redirect('servicios');
        }
    }
}
