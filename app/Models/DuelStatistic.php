<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DuelStatistic extends Model
{
    protected $fillable = [
        'user_id',
        'total_duels',
        'duels_won',
        'duels_lost',
        'duels_fled',
        'practice_duels',
        'total_damage_dealt',
        'total_damage_received',
        'total_healing_done',
        'total_spells_cast',
        'total_critical_hits',
        'total_dodges',
        'favorite_spell_id',
        'favorite_spell_uses',
        'current_winning_streak',
        'longest_winning_streak',
        'current_losing_streak',
        'longest_losing_streak',
        'ranking_points',
        'rank_position',
        'highest_damage_single_spell',
        'fastest_victory_turns',
        'longest_duel_turns',
        'house_duels_won',
        'house_points_earned',
        'total_exp_earned',
        'total_money_earned',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function favoriteSpell(): BelongsTo
    {
        return $this->belongsTo(Spell::class, 'favorite_spell_id');
    }

    // Scopes
    public function scopeTopRanked($query, $limit = 10)
    {
        return $query->orderByDesc('ranking_points')->limit($limit);
    }

    public function scopeByRank($query)
    {
        return $query->orderBy('rank_position');
    }

    // Helper methods
    public function getWinRate(): float
    {
        if ($this->total_duels === 0) {
            return 0.0;
        }

        return ($this->duels_won / $this->total_duels) * 100;
    }

    public function getAverageDamagePerDuel(): float
    {
        if ($this->total_duels === 0) {
            return 0.0;
        }

        return $this->total_damage_dealt / $this->total_duels;
    }

    public function getCriticalHitRate(): float
    {
        if ($this->total_spells_cast === 0) {
            return 0.0;
        }

        return ($this->total_critical_hits / $this->total_spells_cast) * 100;
    }

    public function getDodgeRate(): float
    {
        if ($this->total_duels === 0) {
            return 0.0;
        }

        return ($this->total_dodges / ($this->total_duels * 10)) * 100; // Assuming ~10 turns per duel
    }

    public function updateRankPosition(): void
    {
        $position = DuelStatistic::where('ranking_points', '>', $this->ranking_points)->count() + 1;
        $this->rank_position = $position;
        $this->save();
    }

    public function recordVictory(Duel $duel): void
    {
        $this->total_duels++;
        $this->duels_won++;
        $this->current_winning_streak++;
        $this->current_losing_streak = 0;

        if ($this->current_winning_streak > $this->longest_winning_streak) {
            $this->longest_winning_streak = $this->current_winning_streak;
        }

        if (!$duel->is_practice) {
            $this->total_exp_earned += $duel->exp_reward;
            $this->total_money_earned += $duel->money_reward;
            $this->house_points_earned += $duel->house_points_reward;

            if ($duel->house_points_reward > 0) {
                $this->house_duels_won++;
            }

            // ELO calculation (simple version)
            $this->ranking_points += $this->calculateEloGain($duel);
        } else {
            $this->practice_duels++;
        }

        $this->save();
        $this->updateRankPosition();
    }

    public function recordDefeat(Duel $duel): void
    {
        $this->total_duels++;
        $this->duels_lost++;
        $this->current_losing_streak++;
        $this->current_winning_streak = 0;

        if ($this->current_losing_streak > $this->longest_losing_streak) {
            $this->longest_losing_streak = $this->current_losing_streak;
        }

        if (!$duel->is_practice) {
            // ELO calculation (simple version)
            $this->ranking_points += $this->calculateEloLoss($duel);
            $this->ranking_points = max(0, $this->ranking_points); // Don't go below 0
        } else {
            $this->practice_duels++;
        }

        $this->save();
        $this->updateRankPosition();
    }

    public function recordFlee(): void
    {
        $this->total_duels++;
        $this->duels_fled++;
        $this->current_losing_streak++;
        $this->current_winning_streak = 0;

        if ($this->current_losing_streak > $this->longest_losing_streak) {
            $this->longest_losing_streak = $this->current_losing_streak;
        }

        // Penalty for fleeing
        $this->ranking_points -= 20;
        $this->ranking_points = max(0, $this->ranking_points);

        $this->save();
        $this->updateRankPosition();
    }

    private function calculateEloGain(Duel $duel): int
    {
        $opponent = $duel->getOpponentFor($this->user);
        $opponentStats = $opponent->duelStatistic ?? null;

        if (!$opponentStats) {
            return 15; // Default gain
        }

        $expectedScore = 1 / (1 + pow(10, ($opponentStats->ranking_points - $this->ranking_points) / 400));
        $kFactor = 32;

        return (int) round($kFactor * (1 - $expectedScore));
    }

    private function calculateEloLoss(Duel $duel): int
    {
        $opponent = $duel->getOpponentFor($this->user);
        $opponentStats = $opponent->duelStatistic ?? null;

        if (!$opponentStats) {
            return -10; // Default loss
        }

        $expectedScore = 1 / (1 + pow(10, ($opponentStats->ranking_points - $this->ranking_points) / 400));
        $kFactor = 32;

        return (int) round($kFactor * (0 - $expectedScore));
    }

    public function updateFavoriteSpell(int $spellId): void
    {
        if ($this->favorite_spell_id === $spellId) {
            $this->favorite_spell_uses++;
        } else {
            // Check if this spell is now used more than the current favorite
            $this->favorite_spell_id = $spellId;
            $this->favorite_spell_uses = 1;
        }

        $this->save();
    }
}
