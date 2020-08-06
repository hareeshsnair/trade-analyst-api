<?php

namespace App\Http\Middleware;

use Closure;

class Authorize
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
        if(auth()->user()->currentAccessToken()->name !== $request->header('Device-id')) {
            throw new \Illuminate\Auth\Access\AuthorizationException();
        }
        return $next($request);
    }
}
