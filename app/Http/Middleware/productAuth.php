<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class productAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(empty($request->session()->get('auth_array')['products'] ) && empty($request->session()->get('sudo'))){

            return redirect('/admin');

        }
        return $next($request);
    }
}
