<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\CheckSession;
use App\Pais;
use Auth;
use App\Empresa;
use App\Http\Requests\EmpresaRequest;
use App\Tipo_Proveedor;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;


class EmpresaController extends Controller
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
        $pais = DB::table('paises')
            ->select('pais_id', 'pais_nombre')
            ->pluck('pais_nombre', 'pais_id');

        $tipo_proveedor = DB::table('tipo_proveedores')
            ->select('tipo_proveedor_id','tipo_proveedor_desc')
            ->pluck('tipo_proveedor_desc','tipo_proveedor_id');



        $empresa = Empresa::all();


        return view('empresa.index', compact('empresa', 'pais', 'tipo_proveedor'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pais = DB::table('paises')
            ->select('pais_id', 'pais_nombre')
            ->pluck('pais_nombre', 'pais_id');

        $tipo_proveedor = DB::table('tipo_proveedores')
            ->select('tipo_proveedor_id','tipo_proveedor_desc')
            ->pluck('tipo_proveedor_desc','tipo_proveedor_id');

        return view('empresa.index', compact('pais','tipo_proveedor'));


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validate = DB::table('empresas')
            ->where('empresa_rut', $request->empresa_rut)
            ->where('deleted_at', '=', null)
            ->exists();

        if($validate == true)
        {
            flash('El rut '.$request->empresa_rut.'  ya existe en la base de datos')->warning();
            return redirect('/empresa');
        }else

        try {

            $empresa = new Empresa();
            $empresa->empresa_rut = $request->empresa_rut;
            $empresa->empresa_razon_social = $request->empresa_nombre;
            $empresa->empresa_giro = $request->empresa_giro;
            $empresa->pais_id = $request->pais_id;
            $empresa->empresa_direccion = $request->empresa_direccion;
            $empresa->empresa_nombre_contacto = $request->empresa_nombre_contacto;
            $empresa->empresa_telefono_contacto = $request->empresa_telefono_contacto;
            $empresa->empresa_email_contacto = $request->empresa_email_contacto;

            if($request->es_proveedor == "true")
            {
                $empresa->empresa_es_proveedor = true;
                $empresa->tipo_proveedor_id = $request->tipo_proveedor;
            }else
            {
                $empresa->empresa_es_proveedor = false;
                $empresa->tipo_proveedor_id = NULL;
            }

            $empresa->save();

            flash('La empresa se creo correctamente.')->success();
            return redirect('empresa');

        }catch (\Exception $e) {

            flash('Error al crear la empresa.')->error();
           //flash($e->getMessage())->error();
            return redirect('empresa');
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
        $empresa_id =  Crypt::decrypt($id);
        $empresa = Empresa::findOrfail($empresa_id);

        $tipo_proveedor = DB::table('tipo_proveedores')
        ->select('tipo_proveedor_id', 'tipo_proveedor_desc')
        ->pluck('tipo_proveedor_desc', 'tipo_proveedor_id');

        $pais = DB::table('paises')
        ->select('pais_id', 'pais_nombre')
        ->pluck('pais_nombre', 'pais_id');


        return view('empresa.edit', compact('empresa','tipo_proveedor','pais'));
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
        $empresa_id =  Crypt::decrypt($id);
        $empresa =  Empresa::findOrfail($empresa_id);



        try {

            $empresa->empresa_rut = $request->empresa_rut;
            $empresa->empresa_razon_social = $request->empresa_nombre;
            $empresa->empresa_giro = $request->empresa_giro;
            $empresa->pais_id = $request->pais_id;
            $empresa->empresa_direccion = $request->empresa_direccion;
            $empresa->empresa_nombre_contacto = $request->empresa_nombre_contacto;
            $empresa->empresa_telefono_contacto = $request->empresa_telefono_contacto;
            $empresa->empresa_email_contacto = $request->empresa_email_contacto;

            if($request->es_proveedor == 1)
            {
                $empresa->empresa_es_proveedor = 1;
                $empresa->tipo_proveedor_id = $request->tipo_proveedor;
            }else
            {
                $empresa->empresa_es_proveedor = 0;
                $empresa->tipo_proveedor_id = NULL;
            }

            $empresa->save();

            flash('La empresa se actualizó correctamente.')->success();
            return redirect('empresa');

        }catch (\Exception $e) {

            flash('Error al actualizar la empresa.')->error();
           //flash($e->getMessage())->error();
            return redirect('empresa');
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
        $empresa_id =  Crypt::decrypt($id);

        try {
            $empresa = Empresa::findOrfail($empresa_id)->delete();

            flash('Los datos de la empresa han sido eliminados satisfactoriamente.')->success();
            return redirect('empresa');
        }catch (\Exception $e) {

            flash('Error al intentar eliminar los datos de la empresa.')->error();
            //flash($e->getMessage())->error();
            return redirect('empresa');
        }
    }
}
