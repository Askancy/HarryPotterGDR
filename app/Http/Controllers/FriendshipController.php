<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friendship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendshipController extends Controller
{
    /**
     * Show friends list.
     */
    public function index()
    {
        return view('friends.index');
    }

    /**
     * Send friend request.
     */
    public function sendRequest(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = Auth::user();
        $targetUser = User::findOrFail($validated['user_id']);

        if ($user->id == $targetUser->id) {
            return response()->json([
                'success' => false,
                'message' => 'Non puoi inviare una richiesta di amicizia a te stesso!',
            ], 400);
        }

        $friendship = $user->sendFriendRequest($targetUser);

        if ($friendship) {
            return response()->json([
                'success' => true,
                'message' => 'Richiesta di amicizia inviata!',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Richiesta già esistente o già amici.',
        ], 400);
    }

    /**
     * Accept friend request.
     */
    public function acceptRequest($friendshipId)
    {
        $friendship = Friendship::findOrFail($friendshipId);

        if ($friendship->friend_id != Auth::id()) {
            abort(403, 'Non autorizzato.');
        }

        $friendship->accept();

        return redirect()->back()->with('message', 'Richiesta di amicizia accettata!');
    }

    /**
     * Decline friend request.
     */
    public function declineRequest($friendshipId)
    {
        $friendship = Friendship::findOrFail($friendshipId);

        if ($friendship->friend_id != Auth::id()) {
            abort(403, 'Non autorizzato.');
        }

        $friendship->decline();

        return redirect()->back()->with('message', 'Richiesta di amicizia rifiutata.');
    }

    /**
     * Remove friend.
     */
    public function removeFriend($userId)
    {
        $user = Auth::user();

        Friendship::where(function ($q) use ($user, $userId) {
            $q->where('user_id', $user->id)->where('friend_id', $userId);
        })->orWhere(function ($q) use ($user, $userId) {
            $q->where('user_id', $userId)->where('friend_id', $user->id);
        })->delete();

        return redirect()->back()->with('message', 'Amicizia rimossa.');
    }
}
