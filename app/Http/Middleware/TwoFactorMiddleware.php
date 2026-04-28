<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
   {
    if (auth()->check() && auth()->user()->google2fa_enabled) {

        if (!session('2fa_verified')) {
            return redirect('/2fa/verify');
        }
    }

    return $next($request);
}
}
