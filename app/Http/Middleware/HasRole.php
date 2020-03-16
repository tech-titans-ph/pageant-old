<?php

namespace App\Http\Middleware;

use Closure;

class HasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param mixed $role
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if (in_array(substr($role, 0, 1), ['a', 'e', 'i', 'o', 'u'])) {
            $method = 'isNotAn';
            $identifier = 'an';
        } else {
            $method = 'isNotA';
            $identifier = 'a';
        }

        abort_if(auth()->user()->$method($role), 403, 'Could not process request. Please make sure that the logged in user is ' . $identifier . ' ' . $role);

        return $next($request);
    }
}
