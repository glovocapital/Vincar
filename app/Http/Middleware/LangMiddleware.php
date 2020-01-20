<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;

class LangMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (!empty(session()->get('lang'))) {
            \App::setLocale(session('lang'));
        }



        return $next($request);
    }
}
