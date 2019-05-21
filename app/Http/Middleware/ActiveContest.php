<?php

namespace App\Http\Middleware;

use Closure;

class ActiveContest
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
        if(!session('activeContest')){
            return redirect('/no-active-contest');
        }
        return $next($request);
    }
}
