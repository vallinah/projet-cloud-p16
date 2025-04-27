<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfAuthenticated
{
    public function handle($request, Closure $next)
    {
        if (session()->has('user_id')) {
            return redirect()->route('home')->with('info', 'Vous êtes déjà connecté.');
        }

        return $next($request);
    }
}
