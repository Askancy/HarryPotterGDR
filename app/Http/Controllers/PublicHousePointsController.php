<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PublicHousePointsController extends Controller
{
    /**
     * Show public house points leaderboard and history
     */
    public function index()
    {
        // Get current house rankings
        $houses = DB::table('houses')
            ->orderBy('points', 'desc')
            ->get();

        // Add member count and rank to each house
        $houses = $houses->map(function($house, $index) {
            $memberCount = DB::table('users')->where('team', $house->id)->count();
            $house->rank = $index + 1;
            $house->members = $memberCount;
            return $house;
        });

        // Get recent points activity (last 50)
        $recentActivity = DB::table('house_points_log')
            ->join('houses', 'house_points_log.house_id', '=', 'houses.id')
            ->leftJoin('users as recipient', 'house_points_log.user_id', '=', 'recipient.id')
            ->select(
                'house_points_log.*',
                'houses.name as house_name',
                'houses.id as house_id',
                'recipient.name as recipient_name'
            )
            ->orderBy('house_points_log.created_at', 'desc')
            ->limit(50)
            ->get();

        // Get this week's top contributors
        $weeklyContributors = DB::table('house_points_log')
            ->join('users', 'house_points_log.user_id', '=', 'users.id')
            ->join('houses', 'house_points_log.house_id', '=', 'houses.id')
            ->where('house_points_log.created_at', '>=', Carbon::now()->startOfWeek())
            ->where('house_points_log.points', '>', 0)
            ->select(
                'users.id',
                'users.name',
                'users.avatar',
                'houses.name as house_name',
                'houses.id as house_id',
                DB::raw('SUM(house_points_log.points) as total_points')
            )
            ->groupBy('users.id', 'users.name', 'users.avatar', 'houses.name', 'houses.id')
            ->orderBy('total_points', 'desc')
            ->limit(10)
            ->get();

        // Get archived seasons (if any)
        $archivedSeasons = DB::table('house_points_archive')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('front.house-points.public', [
            'houses' => $houses,
            'recentActivity' => $recentActivity,
            'weeklyContributors' => $weeklyContributors,
            'archivedSeasons' => $archivedSeasons
        ]);
    }
}
