<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InertiaMiddleware
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
        if ($request->is('user/profile')) {
            $isConnected = (bool) auth()->user()->google_access_token;
            inertia()->share('google_calendar', $isConnected);
        }

        return $next($request);
    }
}
