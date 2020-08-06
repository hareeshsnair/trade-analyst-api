<?php

namespace App\Http\Middleware;

use Closure;

class ValidateHeaders
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
        if(!$request->expectsJson()) {
            abort(400, "This request expects the application type JSON");
        }
        if(!$request->header('Device-id')) {
            abort(400, "Missing header device id");
        }
        if(!$request->header('App-version')) {
            abort(400, "Missing header app version");
        } else {
            if((config('constants.app_version.min') > $request->header('App-version')) ||
                ($request->header('App-version') > config('constants.app_version.current')))
            {
                abort(400, "App is outdated. Please update the app");
            }
        }
        return $next($request);
    }
}
