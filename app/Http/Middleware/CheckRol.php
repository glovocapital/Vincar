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
        if (! $request->user()->hasRole($role)) {
            flash('No tiene el Rol para acceder a esta instancia')->error();
            return back()->withInput();
        }

        return $next($request);




    }
}
