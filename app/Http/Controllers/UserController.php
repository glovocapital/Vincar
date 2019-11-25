<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = \App\User::all();

        /*$usuarios = DB::table('users')
            ->select()
            ->where('email','!=','jadcve@gmail.com')
            ->Where('email','!=','crox.sanchez@gmail.com')
            ->Where('email','!=','asthar2010@gmail.com')
            ->paginate(10);
*/
        $roles = DB::table('roles')
            ->select('rol_id', 'rol_desc')
            ->pluck('rol_desc', 'rol_id');

        return view('usuarios.index', compact('roles','usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $roles = DB::table('roles')
            ->select('rol_id', 'rol_desc')
            ->pluck('rol_desc', 'rol_id');

        $empresa = DB::table('empresas')
            ->select('empresa_id', 'empresa_razon_social')
            ->pluck('empresa_razon_social', 'empresa_id');


        return view('usuarios.create', compact('roles','empresa'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validate = DB::table('users')->where('email', $request->user_email)->exists();

        if($validate == true)
        {
            flash('El usuario '.$request->usu_email.'  ya existe en la base de datos')->warning();
            return redirect('/usuarios');
        } else

        DB::beginTransaction();
        try {
            $user = new \App\User();
            $user->user_nombre = $request->user_nombre;
            $user->user_apellido = $request->user_apellido;
            $user->user_rut = $request->user_rut;
            $user->user_cargo = $request->user_cargo;
            $user->user_estado = 1;
            $user->email = $request->user_email;
            $user->password = Hash::make($request->user_password);
            $request->rol_id = $request->rol_id;
            $user->telefono = $request->user_telefono;
            $user->empresa_id = $request->empresa_id;
            $user->save();

        DB::commit();
        flash('El usuario ha sido creado correctamente.')->success();
        return redirect('usuarios');
        }catch (\Exception $e) {


            DB::rollback();

            flash('Error al crear usuario.')->error();
            //flash($e->getMessage())->error();
            return redirect('usuarios');
    }


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = \App\User::findOrfail($id);
        $roles = DB::table('rol')
            ->select('rol_id', 'rol_desc')
            ->pluck('rol_desc', 'rol_id');

        return view('usuarios.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = \App\User::findOrfail($request->user_id);

        $user->user_nombre = $request->user_nombre;
        $user->user_apellido = $request->user_apellido;
        $user->user_rut = $request->user_rut;
        $user->user_cargo = $request->user_cargo;

        $user->user_estado = $request->user_estado;

        $user->email = $request->email;

        if ($request->password != ''){
            $user->password = Hash::make($request->password);
        }

        $user->rol_id = $request->rol_id;

        $user->empresa_id = $request->empresa_id;

        $user->save();

        return redirect('usuarios');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $user = \App\User::findOrfail($id);

        $user->delete();

        return redirect('usuarios');
    }
}
