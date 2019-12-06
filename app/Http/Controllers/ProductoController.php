<?php

namespace App\Http\Controllers;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\CheckSession;
use Illuminate\Support\Facades\Crypt;
use App\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
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
        $producto = Producto::all();
        return view('productos.index', compact('producto'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('productos.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validate = DB::table('productos')
        ->where('producto_codigo', $request->producto_codigo)
        ->exists();

        if($validate == true)
        {

            flash('El código del producto '.$request->producto_codigo   .'  ya existe en la base de datos')->warning();
            return redirect('/productos');
        }else

        try {

            $producto = new Producto();
            $producto->producto_descripcion = $request->producto_descripcion;
            $producto->producto_codigo = $request->producto_codigo;

            $producto->save();


            flash('El Producto se creo correctamente.')->success();
            return redirect('productos');

        }catch (\Exception $e) {



            flash('Error al crear el Producto.')->error();
           flash($e->getMessage())->error();
            return redirect('productos');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $producto_id =  Crypt::decrypt($id);
        $producto = Producto::findOrfail($producto_id);

        return view('productos.edit', compact('producto'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {


        $producto_id =  Crypt::decrypt($id);

        $producto =  Producto::findOrfail($producto_id);


        try {
            $producto->producto_descripcion = $request->producto_descripcion;
            $producto->producto_codigo = $request->producto_codigo;
            $producto->save();


            flash('El Producto se modifico correctamente.')->success();
            return redirect('productos');

        }catch (\Exception $e) {



            flash('Error al actializar el Producto.')->error();
           flash($e->getMessage())->error();
            return redirect('productos');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $producto_id =  Crypt::decrypt($id);

        try {
            $destino = Producto::findOrfail($producto_id)->delete();

            flash('Los datos del producto han sido eliminados satisfactoriamente.')->success();
            return redirect('productos');
        }catch (\Exception $e) {


            flash('Error al intentar eliminar los datos del producto.')->error();
            //flash($e->getMessage())->error();
            return redirect('productos');
        }
    }
}
