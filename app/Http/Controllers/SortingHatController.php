<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SortingHatController extends Controller
{
    /**
     * Show the sorting hat ceremony
     */
    public function show()
    {
        // Redirect if user already has a house
        if (Auth::user()->team) {
            return redirect()->route('house.common-room');
        }

        return view('auth.sorting-hat');
    }

    /**
     * Assign user to a house based on quiz results
     */
    public function assign(Request $request)
    {
        $request->validate([
            'house' => 'required|string|in:Grifondoro,Serpeverde,Corvonero,Tassorosso'
        ]);

        $user = Auth::user();

        // Redirect if user already has a house
        if ($user->team) {
            return response()->json([
                'error' => 'Sei già stato smistato in una casa!'
            ], 400);
        }

        // Map house names to IDs
        $houseMap = [
            'Grifondoro' => 1,
            'Serpeverde' => 2,
            'Corvonero' => 3,
            'Tassorosso' => 4
        ];

        $houseName = $request->input('house');
        $houseId = $houseMap[$houseName];

        // Update user's house
        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'team' => $houseId,
                'updated_at' => now()
            ]);

        // Award initial house points
        DB::table('houses')
            ->where('id', $houseId)
            ->increment('points', 10);

        // Create welcome announcement for the house
        DB::table('house_announcements')->insert([
            'house_id' => $houseId,
            'title' => 'Nuovo Membro!',
            'content' => "Diamo il benvenuto a {$user->name} nella nostra casa! 🎉",
            'priority' => 'medium',
            'created_at' => now(),
            'updated_at' => now(),
            'expires_at' => now()->addDays(3)
        ]);

        // Send system message in house chat
        DB::table('house_messages')->insert([
            'user_id' => $user->id,
            'house_id' => $houseId,
            'message' => "{$user->name} si è unito alla casa! Benvenuto/a! ✨",
            'message_type' => 'system',
            'is_pinned' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'house' => $houseName,
            'redirect' => route('house.common-room')
        ]);
    }

    /**
     * Show the house common room
     */
    public function commonRoom()
    {
        $user = Auth::user();

        // Redirect to sorting if user doesn't have a house
        if (!$user->team) {
            return redirect()->route('sorting-hat.show')
                ->with('info', 'Devi prima essere smistato in una casa!');
        }

        $house = DB::table('houses')->where('id', $user->team)->first();

        return view('front.house.common-room', [
            'house' => $house,
            'user' => $user
        ]);
    }
}
