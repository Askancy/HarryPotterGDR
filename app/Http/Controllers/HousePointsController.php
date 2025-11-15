<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HousePointsController extends Controller
{
    /**
     * Award or deduct points from a house
     *
     * @param int $houseId
     * @param int $points (can be negative)
     * @param string $type
     * @param string $reason
     * @param int|null $userId (user who received/lost points)
     * @param int|null $awardedBy (who awarded, defaults to Auth::id())
     * @param string|null $details
     * @return bool
     */
    public static function awardPoints(
        $houseId,
        $points,
        $type = 'manual',
        $reason = null,
        $userId = null,
        $awardedBy = null,
        $details = null
    ) {
        // Default awarded_by to current authenticated user
        if ($awardedBy === null && Auth::check()) {
            $awardedBy = Auth::id();
        }

        // Update house total points
        DB::table('houses')
            ->where('id', $houseId)
            ->increment('points', $points);

        // Log the points transaction
        DB::table('house_points_log')->insert([
            'house_id' => $houseId,
            'user_id' => $userId,
            'awarded_by' => $awardedBy,
            'points' => $points,
            'type' => $type,
            'reason' => $reason,
            'details' => $details,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return true;
    }

    /**
     * Get house points ranking
     */
    public function getRanking()
    {
        $houses = DB::table('houses')
            ->orderBy('points', 'desc')
            ->get();

        return response()->json([
            'houses' => $houses->map(function($house, $index) {
                return [
                    'id' => $house->id,
                    'name' => $house->name,
                    'points' => $house->points ?? 0,
                    'rank' => $index + 1,
                    'color' => $this->getHouseColor($house->id)
                ];
            })
        ]);
    }

    /**
     * Get recent points activity
     */
    public function getRecentActivity(Request $request)
    {
        $limit = $request->input('limit', 20);
        $houseId = $request->input('house_id');

        $query = DB::table('house_points_log')
            ->join('houses', 'house_points_log.house_id', '=', 'houses.id')
            ->leftJoin('users as recipient', 'house_points_log.user_id', '=', 'recipient.id')
            ->leftJoin('users as awarder', 'house_points_log.awarded_by', '=', 'awarder.id')
            ->select(
                'house_points_log.*',
                'houses.name as house_name',
                'recipient.name as recipient_name',
                'awarder.name as awarder_name'
            )
            ->orderBy('house_points_log.created_at', 'desc');

        if ($houseId) {
            $query->where('house_points_log.house_id', $houseId);
        }

        $activity = $query->limit($limit)->get();

        return response()->json([
            'activity' => $activity->map(function($log) {
                return [
                    'id' => $log->id,
                    'house_name' => $log->house_name,
                    'points' => $log->points,
                    'type' => $log->type,
                    'reason' => $log->reason,
                    'details' => $log->details,
                    'recipient_name' => $log->recipient_name,
                    'awarder_name' => $log->awarder_name,
                    'created_at' => Carbon::parse($log->created_at)->diffForHumans(),
                    'created_at_full' => Carbon::parse($log->created_at)->format('d M Y H:i')
                ];
            })
        ]);
    }

    /**
     * Get house statistics
     */
    public function getHouseStats($houseId)
    {
        $house = DB::table('houses')->where('id', $houseId)->first();

        if (!$house) {
            return response()->json(['error' => 'House not found'], 404);
        }

        // Get total points awarded this week
        $weekPoints = DB::table('house_points_log')
            ->where('house_id', $houseId)
            ->where('created_at', '>=', Carbon::now()->startOfWeek())
            ->sum('points');

        // Get total points awarded this month
        $monthPoints = DB::table('house_points_log')
            ->where('house_id', $houseId)
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->sum('points');

        // Get top contributors this month
        $topContributors = DB::table('house_points_log')
            ->join('users', 'house_points_log.user_id', '=', 'users.id')
            ->where('house_points_log.house_id', $houseId)
            ->where('house_points_log.created_at', '>=', Carbon::now()->startOfMonth())
            ->where('house_points_log.points', '>', 0)
            ->select(
                'users.id',
                'users.name',
                'users.avatar',
                DB::raw('SUM(house_points_log.points) as total_points')
            )
            ->groupBy('users.id', 'users.name', 'users.avatar')
            ->orderBy('total_points', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'house' => [
                'id' => $house->id,
                'name' => $house->name,
                'total_points' => $house->points ?? 0,
                'week_points' => $weekPoints ?? 0,
                'month_points' => $monthPoints ?? 0
            ],
            'top_contributors' => $topContributors
        ]);
    }

    /**
     * Get house color based on ID
     */
    private function getHouseColor($houseId)
    {
        $colors = [
            1 => '#dc2626', // Grifondoro - Red
            2 => '#166534', // Serpeverde - Green
            3 => '#1e40af', // Corvonero - Blue
            4 => '#eab308'  // Tassorosso - Yellow
        ];

        return $colors[$houseId] ?? '#6b7280';
    }
}
