<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\CheckSession;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\CrearUsuarioRequest;
use App\Http\Requests\EditarUsuarioRequest;


class UserController extends Controller
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
        $usuarios = User::all();


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
    public function store(CrearUsuarioRequest $request)
    {

        $validate = DB::table('users')->where('email', $request->user_email)->exists();



        if($validate == true)
        {
            flash('El usuario '.$request->usu_email.'  ya existe en la base de datos')->warning();
            return redirect('/usuarios');
        }
        elseif($request->user_pass != $request->user_pass_confirmation)
        {
            flash('Las contraseñas ingresadas no coinciden')->warning();
            return redirect('/usuarios');
        }else


        DB::beginTransaction();
        try {

            $user = new User();
            $user->user_nombre = $request->user_nombre;
            $user->user_apellido = $request->user_apellido;
            $user->user_rut = $request->user_rut;
            $user->user_cargo = $request->user_cargo;
            $user->user_estado = 1;
            $user->email = $request->user_email;
            $user->password = bcrypt($request->user_pass);
            $user->rol_id = $request->rol_id;
            $user->user_telefono = $request->user_telefono;
            $user->empresa_id = $request->empresa_id;
            $user->save();

        DB::commit();
        flash('El usuario ha sido creado correctamente.')->success();
        return redirect('usuarios');

        }catch (\Exception $e) {

            DB::rollback();

            flash('Error al crear usuario.')->error();
           // flash($e->getMessage())->error();
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
        $id =  Crypt::decrypt($id);
        $usuario = User::findOrfail($id);

        $roles = DB::table('roles')
            ->select('rol_id', 'rol_desc')
            ->pluck('rol_desc', 'rol_id');

        $empresa = DB::table('empresas')
            ->select('empresa_id', 'empresa_razon_social')
            ->pluck('empresa_razon_social', 'empresa_id');


        return view('usuarios.edit', compact('usuario', 'roles','empresa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {


        $user_id =  Crypt::decrypt($id);

        DB::beginTransaction();
        try {
            $user = User::findOrfail($user_id);
            $user->user_nombre = $request->user_nombre;
            $user->user_apellido = $request->user_apellido;
            $user->user_rut = $request->user_rut;
            $user->user_cargo = $request->user_cargo;
            $user->email = $request->user_email;
            if ($request->user_pass != ''){
                $user->password = bcrypt($request->user_pass);
            }
            $user->rol_id = $request->rol_id;
            $user->empresa_id = $request->empresa_id;
            $user->user_telefono = $request->user_telefono;
            $user->user_cargo = $request->user_cargo;
            $user->save();

            DB::commit();

            flash('Los datos del usuario han sido modificado correctamente.')->success();
            return redirect('usuarios');

        }catch (\Exception $e) {

            DB::rollback();
            flash('Error al actualizar los datos del usuario.')->error();
            //flash($e->getMessage())->error();
            return redirect('usuarios');
        }
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

        $user_id =  Crypt::decrypt($id);
        DB::beginTransaction();
        try {
            $user = User::findOrfail($user_id)->delete();
            DB::commit();
            flash('Los datos del usuario han sido eliminados satisfactoriamente.')->success();
            return redirect('usuarios');
        }catch (\Exception $e) {

            DB::rollback();
            flash('Error al intentar eliminar los datos del usuario.')->error();
            //flash($e->getMessage())->error();
            return redirect('usuarios');
        }
    }
}
