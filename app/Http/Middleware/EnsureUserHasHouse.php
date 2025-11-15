<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasHouse
{
    /**
     * Handle an incoming request.
     * Ensures that the authenticated user has been sorted into a house.
     * If not, redirects to the sorting hat ceremony.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Se l'utente non ha una casa, reindirizza allo smistamento
            if (!$user->team) {
                return redirect()->route('sorting-hat.show')
                    ->with('warning', 'Devi prima essere smistato in una casa per accedere a questa sezione!');
            }
        }

        return $next($request);
    }
}
