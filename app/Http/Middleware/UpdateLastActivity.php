<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UpdateLastActivity
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
        if (Auth::check()) {
            // Update last activity timestamp every 2 minutes to avoid too many DB writes
            $user = Auth::user();
            $lastActivity = $user->last_activity;

            if (!$lastActivity || $lastActivity->diffInMinutes(now()) >= 2) {
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['last_activity' => now()]);
            }
        }

        return $next($request);
    }
}
