<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $user = Auth::user();

            // Se l'utente non ha ancora una casa, portalo allo smistamento
            if (!$user->team) {
                return redirect()->route('sorting-hat.show');
            }

            // Altrimenti portalo alla sala comune della sua casa
            return redirect()->route('house.common-room');
        }

        return $next($request);
    }
}
