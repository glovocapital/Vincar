<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRol
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */


    public function handle($request, Closure $next, $role)
    {
        $roles = array_slice(func_get_args(), 2);
        foreach ($roles as $key => $rol) {
            if( Auth::user()->oneRol->rol_desc == $rol ) {
                //if (! $request->user()->hasRole($role)) {
                    return $next($request);
                    //flash('No tiene el Rol para acceder a esta instancia')->error();
                    //return back()->withInput();
                }
        }



        //return $next($request);
        flash('No tiene el Rol para acceder a esta instancia')->error();
            return back()->withInput();




    }
}
