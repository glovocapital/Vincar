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
        //$users = \App\User::all();
        $users = DB::table('users')
            ->select()
            ->where('email','!=','jadcve@gmail.com')
            ->Where('email','!=','crox.sanchez@gmail.com')
            ->Where('email','!=','asthar2010@gmail.com')
            ->paginate(10);

        $roles = DB::table('rol')
            ->select('rol_id', 'rol_desc')
            ->pluck('rol_desc', 'rol_id');

        return view('users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = DB::table('rol')
            ->select('rol_id', 'rol_desc')
            ->pluck('rol_desc', 'rol_id');

        return view('users.create', compact('roles'));
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
        $user = new \App\User();

        $user->user_nombre = $request->user_nombre;
        $user->user_apellido = $request->user_apellido;
        $user->user_rut = $request->user_rut;
        $user->user_cargo = $request->user_cargo;
        
        if (isset($request->user_estado)){
            $user->user_estado = $request->user_estado;
        }
        
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        
        if (isset($request->rol_id)){
            $user->rol_id = $request->rol_id;
        }

        $user->empresa_id = $request->empresa_id;

        $user->save();

        return redirect('users');
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

        return view('users.edit', compact('user', 'roles'));
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

        return redirect('users');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function editUserRol($id)
    {
        $user = \App\User::findOrfail($id);
        $roles = DB::table('rol')
            ->select('rol_id', 'rol_desc')
            ->pluck('rol_desc', 'rol_id');

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function updateUserRol(Request $request)
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

        return redirect('users');
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

        return redirect('users');
    }
}
