<?php

namespace App\Http\Middleware;

use Closure;

class EnsureMobileVerified
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
        if(!auth()->user()->is_mobile_verified) {
            return abort(403, "Your mobile number is not verified");
        }
        return $next($request);
    }
}
