<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Board
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
        if (Auth::check() && Auth::user()->permission == 2) {
            return $next($request);
        } else if (!Auth::check()){
            return redirect()->route('login');
        }
        abort(403, 'Unauthorized action.');
    }
}
