<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestrictAdminPanel
{
    public function handle(Request $request, Closure $next)
    {

        if (Auth::check() && Auth::user()->email === 'admin@contuvo.com') {
            return $next($request);
        }

        abort(403, 'Acceso no autorizado.');
    }
}
