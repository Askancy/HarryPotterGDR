<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Show public profile.
     */
    public function show($slug)
    {
        $user = User::where('slug', $slug)->firstOrFail();

        // Check if profile is public or if viewing own profile
        if (!$user->profile_public && Auth::id() != $user->id) {
            abort(403, 'Questo profilo è privato.');
        }

        // Increment profile views if not own profile
        if (Auth::id() != $user->id) {
            $user->incrementProfileViews();
        }

        // Get equipped clothing
        $equippedClothing = $user->equippedClothing()->with('clothing')->get()->keyBy('slot');

        // Get clothing stats
        $clothingStats = $user->getClothingStats();

        // Get friends
        $friends = $user->friends();
        $friendsCount = $friends->count();

        // Check if current user is friend
        $isFriend = false;
        $hasPendingRequest = false;

        if (Auth::check() && Auth::id() != $user->id) {
            $isFriend = Auth::user()->isFriendsWith($user);

            // Check for pending request
            $hasPendingRequest = \App\Models\Friendship::where(function ($q) use ($user) {
                $q->where('user_id', Auth::id())->where('friend_id', $user->id);
            })->orWhere(function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('friend_id', Auth::id());
            })->where('status', 'pending')->exists();
        }

        return view('profile.public', compact(
            'user',
            'equippedClothing',
            'clothingStats',
            'friendsCount',
            'isFriend',
            'hasPendingRequest'
        ));
    }

    /**
     * Show own profile settings.
     */
    public function settings()
    {
        $user = Auth::user();
        return view('profile.settings', compact('user'));
    }

    /**
     * Update profile settings.
     */
    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'profile_public' => 'boolean',
            'show_inventory' => 'boolean',
            'show_stats' => 'boolean',
            'profile_title' => 'nullable|string|max:50',
            'biography' => 'nullable|string|max:1000',
        ]);

        $user->update($validated);

        return redirect()->back()->with('message', 'Impostazioni profilo aggiornate!');
    }
}
