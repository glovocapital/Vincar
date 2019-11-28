<?php

namespace App\Http\Controllers;
use App\Tipo_Proveedor;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\CheckSession;
use Illuminate\Support\Facades\Crypt;
use DB;

use Illuminate\Http\Request;

class TipoProveedorController extends Controller
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
        $proveedores = Tipo_Proveedor::all();
        return view('tipoproveedor.index', compact('proveedores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tipoproveedor.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = DB::table('tipo_proveedores')->where('tipo_proveedor_desc', $request->tipo_proveedor_desc)->exists();

        if($validate == true)
        {
            flash('El Tipo de Servicio '.$request->tipo_proveedor_desc.'  ya existe en la base de datos')->warning();
            return redirect('/proveedor');
        }else

        try {

            $proveedor = new Tipo_Proveedor();
            $proveedor->tipo_proveedor_desc = $request->tipo_proveedor_desc;
            $proveedor->save();

            DB::commit();
            flash('El Proveedor se creo correctamente.')->success();
            return redirect('proveedor');

        }catch (\Exception $e) {



            flash('Error al crear el Proveedor.')->error();
           flash($e->getMessage())->error();
            return redirect('proveedor');
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
        $proveedor_id =  Crypt::decrypt($id);
        $proveedor = Tipo_Proveedor::findOrfail($proveedor_id);

        return view('tipoproveedor.edit', compact('proveedor'));
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
        $proveedor_id =  Crypt::decrypt($id);
        $proveedor =  Tipo_Proveedor::findOrfail($proveedor_id);



        try {

            $proveedor->tipo_proveedor_desc = $request->tipo_proveedor_desc;
            $proveedor->save();

            DB::commit();
            flash('El tipo de proveedor se actualizó correctamente.')->success();
            return redirect('proveedor');

        }catch (\Exception $e) {



            flash('Error al actualizar el tipo de proveedor.')->error();
           //flash($e->getMessage())->error();
            return redirect('proveedor');
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
        $proveedor_id =  Crypt::decrypt($id);

        try {
            $proveedor = Tipo_Proveedor::findOrfail($proveedor_id)->delete();

            flash('Los datos del tipo de proveedor han sido eliminados satisfactoriamente.')->success();
            return redirect('proveedor');
        }catch (\Exception $e) {


            flash('Error al intentar eliminar los datos del tipo de proveedor.')->error();
            //flash($e->getMessage())->error();
            return redirect('proveedor');
        }
    }
}
