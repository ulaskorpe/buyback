<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class checkUser
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
        //  dd($request);
        if($request->session()->get('admin_id') == null){
            return redirect('/admin/login');
            //   }elseif($request->session()->get('user_id') != null){
            //   echo $request->session()->get('user_id');
            //return redirect('/');
        }

        return $next($request);
    }
}
