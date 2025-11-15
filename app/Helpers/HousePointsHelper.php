<?php

namespace App\Helpers;

use App\Http\Controllers\HousePointsController;
use Illuminate\Support\Facades\DB;

class HousePointsHelper
{
    /**
     * Award points when a user completes a quest
     */
    public static function onQuestComplete($userId, $questDifficulty = 'medium')
    {
        $user = DB::table('users')->where('id', $userId)->first();

        if (!$user || !$user->team) {
            return false;
        }

        $pointsMap = [
            'easy' => 5,
            'medium' => 10,
            'hard' => 20,
            'expert' => 30
        ];

        $points = $pointsMap[$questDifficulty] ?? 10;

        return HousePointsController::awardPoints(
            $user->team,
            $points,
            'quest_complete',
            "Quest completata (Difficoltà: {$questDifficulty})",
            $userId,
            null, // system
            "Quest completata con successo"
        );
    }

    /**
     * Award points when a user unlocks an achievement
     */
    public static function onAchievementUnlock($userId, $achievementRarity = 'common')
    {
        $user = DB::table('users')->where('id', $userId)->first();

        if (!$user || !$user->team) {
            return false;
        }

        $pointsMap = [
            'common' => 5,
            'uncommon' => 10,
            'rare' => 15,
            'epic' => 25,
            'legendary' => 50,
            'hidden' => 30
        ];

        $points = $pointsMap[$achievementRarity] ?? 5;

        return HousePointsController::awardPoints(
            $user->team,
            $points,
            'achievement',
            "Achievement sbloccato (Rarità: {$achievementRarity})",
            $userId,
            null,
            "Nuovo achievement ottenuto"
        );
    }

    /**
     * Award points for daily login
     */
    public static function onDailyLogin($userId)
    {
        $user = DB::table('users')->where('id', $userId)->first();

        if (!$user || !$user->team) {
            return false;
        }

        // Check if user already received login bonus today
        $today = now()->startOfDay();
        $alreadyAwarded = DB::table('house_points_log')
            ->where('user_id', $userId)
            ->where('type', 'attendance')
            ->where('created_at', '>=', $today)
            ->exists();

        if ($alreadyAwarded) {
            return false;
        }

        return HousePointsController::awardPoints(
            $user->team,
            2,
            'attendance',
            "Presenza giornaliera",
            $userId,
            null,
            "Login giornaliero completato"
        );
    }

    /**
     * Award points for winning an event/competition
     */
    public static function onEventWin($houseId, $eventName, $points = 50)
    {
        return HousePointsController::awardPoints(
            $houseId,
            $points,
            'event_win',
            "Vittoria: {$eventName}",
            null,
            null,
            "La casa ha vinto l'evento"
        );
    }

    /**
     * Award points for good behavior/roleplay
     */
    public static function onGoodBehavior($userId, $reason, $points = 5)
    {
        $user = DB::table('users')->where('id', $userId)->first();

        if (!$user || !$user->team) {
            return false;
        }

        return HousePointsController::awardPoints(
            $user->team,
            $points,
            'good_behavior',
            $reason,
            $userId,
            null,
            "Comportamento esemplare"
        );
    }

    /**
     * Deduct points for rule violation
     */
    public static function onRuleViolation($userId, $reason, $points = -10, $awardedBy = null)
    {
        $user = DB::table('users')->where('id', $userId)->first();

        if (!$user || !$user->team) {
            return false;
        }

        return HousePointsController::awardPoints(
            $user->team,
            $points, // negative
            'rule_violation',
            $reason,
            $userId,
            $awardedBy,
            "Violazione delle regole"
        );
    }

    /**
     * Award points for house competition/challenge
     */
    public static function onCompetitionResult($houseId, $position, $competitionName)
    {
        $pointsMap = [
            1 => 100,  // First place
            2 => 75,   // Second place
            3 => 50,   // Third place
            4 => 25    // Fourth place
        ];

        $points = $pointsMap[$position] ?? 10;

        $positionNames = [
            1 => '1° Posto',
            2 => '2° Posto',
            3 => '3° Posto',
            4 => '4° Posto'
        ];

        $positionText = $positionNames[$position] ?? "{$position}° Posto";

        return HousePointsController::awardPoints(
            $houseId,
            $points,
            'competition',
            "{$competitionName} - {$positionText}",
            null,
            null,
            "Competizione completata"
        );
    }

    /**
     * Award points when user learns a new spell
     */
    public static function onSpellLearned($userId, $spellRarity = 'common')
    {
        $user = DB::table('users')->where('id', $userId)->first();

        if (!$user || !$user->team) {
            return false;
        }

        $pointsMap = [
            'common' => 2,
            'uncommon' => 3,
            'rare' => 5,
            'epic' => 8,
            'legendary' => 15
        ];

        $points = $pointsMap[$spellRarity] ?? 2;

        return HousePointsController::awardPoints(
            $user->team,
            $points,
            'manual',
            "Incantesimo appreso (Rarità: {$spellRarity})",
            $userId,
            null,
            "Nuovo incantesimo padroneggiato"
        );
    }

    /**
     * Award points when user wins in combat arena
     */
    public static function onCombatVictory($userId, $enemyDifficulty = 'medium')
    {
        $user = DB::table('users')->where('id', $userId)->first();

        if (!$user || !$user->team) {
            return false;
        }

        $pointsMap = [
            'easy' => 3,
            'medium' => 5,
            'hard' => 8,
            'boss' => 15
        ];

        $points = $pointsMap[$enemyDifficulty] ?? 5;

        return HousePointsController::awardPoints(
            $user->team,
            $points,
            'manual',
            "Vittoria in duello (Difficoltà: {$enemyDifficulty})",
            $userId,
            null,
            "Duello vinto nell'arena"
        );
    }

    /**
     * Get house ranking with additional stats
     */
    public static function getHouseRanking()
    {
        $houses = DB::table('houses')
            ->orderBy('points', 'desc')
            ->get();

        return $houses->map(function($house, $index) {
            $memberCount = DB::table('users')->where('team', $house->id)->count();

            return [
                'id' => $house->id,
                'name' => $house->name,
                'points' => $house->points ?? 0,
                'rank' => $index + 1,
                'members' => $memberCount,
                'avg_points_per_member' => $memberCount > 0 ? round(($house->points ?? 0) / $memberCount, 2) : 0
            ];
        });
    }

    /**
     * Get user's contribution to their house
     */
    public static function getUserContribution($userId)
    {
        $user = DB::table('users')->where('id', $userId)->first();

        if (!$user) {
            return null;
        }

        $totalPoints = DB::table('house_points_log')
            ->where('user_id', $userId)
            ->sum('points');

        $thisWeek = DB::table('house_points_log')
            ->where('user_id', $userId)
            ->where('created_at', '>=', now()->startOfWeek())
            ->sum('points');

        $thisMonth = DB::table('house_points_log')
            ->where('user_id', $userId)
            ->where('created_at', '>=', now()->startOfMonth())
            ->sum('points');

        return [
            'total' => $totalPoints ?? 0,
            'this_week' => $thisWeek ?? 0,
            'this_month' => $thisMonth ?? 0
        ];
    }
}
