<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authrole
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
    	$userRole=Auth::user()->role;
    	if($userRole != 1){
    		Auth::logout();
    		return redirect()->guest('auth/login');
    	}
    	return $next($request);
    }
}
