<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HousePointsController as BaseHousePointsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HousePointsController extends Controller
{
    /**
     * Show house points management page
     */
    public function index()
    {
        $houses = DB::table('houses')
            ->orderBy('points', 'desc')
            ->get();

        $recentActivity = DB::table('house_points_log')
            ->join('houses', 'house_points_log.house_id', '=', 'houses.id')
            ->leftJoin('users as recipient', 'house_points_log.user_id', '=', 'recipient.id')
            ->leftJoin('users as awarder', 'house_points_log.awarded_by', '=', 'awarder.id')
            ->select(
                'house_points_log.*',
                'houses.name as house_name',
                'recipient.name as recipient_name',
                'recipient.username as recipient_username',
                'awarder.name as awarder_name'
            )
            ->orderBy('house_points_log.created_at', 'desc')
            ->limit(50)
            ->get();

        return view('admin.house-points.index', [
            'houses' => $houses,
            'recentActivity' => $recentActivity
        ]);
    }

    /**
     * Award points to a house
     */
    public function award(Request $request)
    {
        $request->validate([
            'house_id' => 'required|integer|exists:houses,id',
            'points' => 'required|integer',
            'type' => 'required|string|in:manual,quest_complete,achievement,event_win,good_behavior,rule_violation,competition,attendance,system',
            'reason' => 'nullable|string|max:255',
            'user_id' => 'nullable|integer|exists:users,id',
            'details' => 'nullable|string'
        ]);

        BaseHousePointsController::awardPoints(
            $request->input('house_id'),
            $request->input('points'),
            $request->input('type'),
            $request->input('reason'),
            $request->input('user_id'),
            Auth::id(),
            $request->input('details')
        );

        // Create announcement in house chat
        $house = DB::table('houses')->where('id', $request->input('house_id'))->first();
        $points = $request->input('points');
        $reason = $request->input('reason') ?? 'Assegnazione punti';

        if ($points > 0) {
            DB::table('house_announcements')->insert([
                'house_id' => $request->input('house_id'),
                'title' => "🎉 {$points} Punti Guadagnati!",
                'content' => $reason,
                'priority' => $points >= 50 ? 'high' : 'medium',
                'created_at' => now(),
                'updated_at' => now(),
                'expires_at' => now()->addDays(3)
            ]);
        } elseif ($points < 0) {
            DB::table('house_announcements')->insert([
                'house_id' => $request->input('house_id'),
                'title' => "⚠️ {$points} Punti Persi",
                'content' => $reason,
                'priority' => 'urgent',
                'created_at' => now(),
                'updated_at' => now(),
                'expires_at' => now()->addDays(3)
            ]);
        }

        return redirect()->back()->with('success', "Punti assegnati con successo a {$house->name}!");
    }

    /**
     * Reset all house points (start of new year/semester)
     */
    public function reset(Request $request)
    {
        $request->validate([
            'confirm' => 'required|in:RESET'
        ]);

        // Archive current points
        DB::table('house_points_archive')->insert([
            'season' => $request->input('season', 'Anno ' . date('Y')),
            'houses_data' => json_encode(DB::table('houses')->get()),
            'created_at' => now()
        ]);

        // Reset all house points to 0
        DB::table('houses')->update(['points' => 0]);

        // Log the reset
        $houses = DB::table('houses')->get();
        foreach ($houses as $house) {
            DB::table('house_points_log')->insert([
                'house_id' => $house->id,
                'awarded_by' => Auth::id(),
                'points' => 0,
                'type' => 'system',
                'reason' => 'Reset punti - Nuovo anno scolastico',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return redirect()->back()->with('success', 'Punti di tutte le case resettati con successo!');
    }

    /**
     * Bulk award points to all members of a house
     */
    public function bulkAward(Request $request)
    {
        $request->validate([
            'house_id' => 'required|integer|exists:houses,id',
            'points_per_user' => 'required|integer',
            'reason' => 'required|string|max:255'
        ]);

        $houseId = $request->input('house_id');
        $pointsPerUser = $request->input('points_per_user');
        $reason = $request->input('reason');

        // Get all users in the house
        $users = DB::table('users')
            ->where('team', $houseId)
            ->get();

        $totalPoints = 0;

        foreach ($users as $user) {
            BaseHousePointsController::awardPoints(
                $houseId,
                $pointsPerUser,
                'manual',
                $reason,
                $user->id,
                Auth::id(),
                "Assegnazione di massa: {$pointsPerUser} punti a tutti i membri"
            );
            $totalPoints += $pointsPerUser;
        }

        $house = DB::table('houses')->where('id', $houseId)->first();

        return redirect()->back()->with('success',
            "Assegnati {$pointsPerUser} punti a {$users->count()} membri di {$house->name} (Totale: {$totalPoints} punti)");
    }
}
