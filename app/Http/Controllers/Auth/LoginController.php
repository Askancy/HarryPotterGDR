<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the post-login redirect path.
     * Redirects to sorting hat if user has no house,
     * otherwise to their house common room.
     *
     * @return string
     */
    protected function redirectTo()
    {
        $user = Auth::user();

        // Se l'utente non ha ancora una casa (team), portalo allo smistamento
        if (!$user || !$user->team) {
            return route('sorting-hat.show');
        }

        // Altrimenti portalo alla sala comune della sua casa
        return route('house.common-room');
    }

    public function showLoginForm()
    {
        return redirect('/');
    }
}
