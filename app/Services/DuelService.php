<?php

namespace App\Services;

use App\Models\Duel;
use App\Models\DuelTurn;
use App\Models\Spell;
use App\Models\User;
use App\Models\UserSpell;
use Illuminate\Support\Facades\DB;

class DuelService
{
    /**
     * Create a new duel challenge.
     */
    public function createDuel(User $challenger, User $opponent, bool $isPractice = false): Duel
    {
        // Calculate rewards (only if not practice)
        $expReward = 0;
        $moneyReward = 0;
        $housePointsReward = 0;

        if (!$isPractice) {
            // EXP reward based on opponent level
            $expReward = (int) (50 * ($opponent->level / $challenger->level));

            // Money reward (5% of opponent's money, max 500)
            $moneyReward = min((int) ($opponent->money * 0.05), 500);

            // House points (5-15 based on level difference)
            $levelDiff = abs($challenger->level - $opponent->level);
            $housePointsReward = max(5, min(15, 10 + ($levelDiff * 2)));
        }

        $duel = Duel::create([
            'challenger_id' => $challenger->id,
            'opponent_id' => $opponent->id,
            'location_id' => $challenger->current_location_id,
            'status' => 'pending',
            'challenger_health' => $challenger->max_health,
            'challenger_mana' => $challenger->max_mana,
            'opponent_health' => $opponent->max_health,
            'opponent_mana' => $opponent->max_mana,
            'challenger_current_health' => $challenger->max_health,
            'challenger_current_mana' => $challenger->max_mana,
            'opponent_current_health' => $opponent->max_health,
            'opponent_current_mana' => $opponent->max_mana,
            'exp_reward' => $expReward,
            'money_reward' => $moneyReward,
            'house_points_reward' => $housePointsReward,
            'is_practice' => $isPractice,
            'expires_at' => now()->addMinutes(30),
        ]);

        // Notify opponent
        $opponent->notify(
            'duel_challenge',
            'Sfida a Duello!',
            "{$challenger->name} ti ha sfidato a un duello" . ($isPractice ? " di allenamento" : "") . "!",
            'fa-magic',
            "/duels/{$duel->id}",
            ['duel_id' => $duel->id, 'challenger_id' => $challenger->id]
        );

        return $duel;
    }

    /**
     * Accept a duel challenge.
     */
    public function acceptDuel(Duel $duel): bool
    {
        if ($duel->status !== 'pending') {
            return false;
        }

        DB::transaction(function () use ($duel) {
            $duel->update([
                'status' => 'active',
                'started_at' => now(),
                'current_turn' => 1,
                'current_player_id' => $duel->challenger_id, // Challenger starts
                'expires_at' => now()->addMinutes(30), // Reset expiration
            ]);

            // Notify challenger
            $duel->challenger->notify(
                'duel_accepted',
                'Duello Accettato!',
                "{$duel->opponent->name} ha accettato la tua sfida a duello!",
                'fa-check-circle',
                "/duels/{$duel->id}",
                ['duel_id' => $duel->id]
            );
        });

        return true;
    }

    /**
     * Decline a duel challenge.
     */
    public function declineDuel(Duel $duel): bool
    {
        if ($duel->status !== 'pending') {
            return false;
        }

        $duel->update(['status' => 'declined']);

        // Notify challenger
        $duel->challenger->notify(
            'duel_declined',
            'Duello Rifiutato',
            "{$duel->opponent->name} ha rifiutato la tua sfida a duello.",
            'fa-times-circle',
            '/duels',
            ['duel_id' => $duel->id]
        );

        return true;
    }

    /**
     * Execute a spell action in a duel.
     */
    public function castSpell(Duel $duel, User $player, Spell $spell): array
    {
        $opponent = $duel->getOpponentFor($player);

        // Check mana
        if ($duel->getCurrentManaFor($player) < $spell->mana_cost) {
            return [
                'success' => false,
                'message' => 'Mana insufficiente!'
            ];
        }

        // Get player's spell proficiency
        $userSpell = UserSpell::where('user_id', $player->id)
            ->where('spell_id', $spell->id)
            ->first();

        $proficiency = $userSpell ? $userSpell->proficiency : 50; // Default 50 if never used

        // Calculate damage
        $damageResult = $this->calculateDamage($player, $opponent, $spell, $proficiency, $duel);

        // Apply damage or healing
        if ($spell->type === 'healing') {
            $currentHealth = $duel->getCurrentHealthFor($player);
            $newHealth = min($currentHealth + $damageResult['damage'], $duel->{'challenger_id' === $player->id ? 'challenger_health' : 'opponent_health'});
            $duel->updateHealth($player, $newHealth);
            $healingDone = $newHealth - $currentHealth;
        } else {
            // Apply damage to opponent
            $currentHealth = $duel->getCurrentHealthFor($opponent);
            $finalDamage = max(0, $currentHealth - $damageResult['damage']);
            $duel->updateHealth($opponent, $finalDamage);
            $healingDone = 0;
        }

        // Use mana
        $currentMana = $duel->getCurrentManaFor($player);
        $duel->updateMana($player, $currentMana - $spell->mana_cost);

        // Reset defending status
        $duel->setDefending($player, false);

        // Create turn record
        $turn = DuelTurn::create([
            'duel_id' => $duel->id,
            'turn_number' => $duel->current_turn,
            'player_id' => $player->id,
            'action_type' => 'spell',
            'spell_id' => $spell->id,
            'damage_dealt' => $spell->type !== 'healing' ? $damageResult['damage'] : 0,
            'healing_done' => $healingDone,
            'mana_used' => $spell->mana_cost,
            'is_critical' => $damageResult['is_critical'],
            'is_dodged' => $damageResult['is_dodged'],
            'is_blocked' => $damageResult['is_blocked'],
            'player_health_after' => $duel->getCurrentHealthFor($player),
            'player_mana_after' => $duel->getCurrentManaFor($player),
            'opponent_health_after' => $duel->getCurrentHealthFor($opponent),
            'opponent_mana_after' => $duel->getCurrentManaFor($opponent),
        ]);

        // Update spell proficiency
        if ($userSpell) {
            $userSpell->increment('times_used');
            $userSpell->proficiency = min(100, $userSpell->proficiency + 1);
            $userSpell->save();
        }

        // Update statistics
        $stats = $player->getOrCreateDuelStatistics();
        $stats->total_spells_cast++;
        $stats->total_damage_dealt += $damageResult['damage'];
        if ($damageResult['is_critical']) {
            $stats->total_critical_hits++;
        }
        if ($damageResult['damage'] > $stats->highest_damage_single_spell) {
            $stats->highest_damage_single_spell = $damageResult['damage'];
        }
        $stats->updateFavoriteSpell($spell->id);
        $stats->save();

        // Check if duel is over
        if ($duel->getCurrentHealthFor($opponent) <= 0) {
            $this->endDuel($duel, $player);
        } else {
            // Next turn
            $duel->nextTurn();
        }

        return [
            'success' => true,
            'turn' => $turn,
            'damage' => $damageResult['damage'],
            'is_critical' => $damageResult['is_critical'],
            'is_dodged' => $damageResult['is_dodged'],
            'is_blocked' => $damageResult['is_blocked'],
        ];
    }

    /**
     * Execute a defend action.
     */
    public function defend(Duel $duel, User $player): array
    {
        // Set defending status
        $duel->setDefending($player, true);

        // Restore small amount of mana
        $manaRestored = (int) ($player->max_mana * 0.1); // 10% mana restore
        $currentMana = $duel->getCurrentManaFor($player);
        $maxMana = $player->id === $duel->challenger_id ? $duel->challenger_mana : $duel->opponent_mana;
        $newMana = min($currentMana + $manaRestored, $maxMana);
        $duel->updateMana($player, $newMana);
        $actualManaRestored = $newMana - $currentMana;

        // Create turn record
        $turn = DuelTurn::create([
            'duel_id' => $duel->id,
            'turn_number' => $duel->current_turn,
            'player_id' => $player->id,
            'action_type' => 'defend',
            'mana_restored' => $actualManaRestored,
            'player_health_after' => $duel->getCurrentHealthFor($player),
            'player_mana_after' => $duel->getCurrentManaFor($player),
            'opponent_health_after' => $duel->getCurrentHealthFor($duel->getOpponentFor($player)),
            'opponent_mana_after' => $duel->getCurrentManaFor($duel->getOpponentFor($player)),
        ]);

        // Next turn
        $duel->nextTurn();

        return [
            'success' => true,
            'turn' => $turn,
            'mana_restored' => $actualManaRestored,
        ];
    }

    /**
     * Flee from a duel.
     */
    public function flee(Duel $duel, User $player): bool
    {
        $opponent = $duel->getOpponentFor($player);

        // Create turn record
        DuelTurn::create([
            'duel_id' => $duel->id,
            'turn_number' => $duel->current_turn,
            'player_id' => $player->id,
            'action_type' => 'flee',
            'player_health_after' => $duel->getCurrentHealthFor($player),
            'player_mana_after' => $duel->getCurrentManaFor($player),
            'opponent_health_after' => $duel->getCurrentHealthFor($opponent),
            'opponent_mana_after' => $duel->getCurrentManaFor($opponent),
        ]);

        // End duel with opponent as winner
        $duel->update([
            'status' => 'fled',
            'winner_id' => $opponent->id,
            'ended_at' => now(),
        ]);

        // Update statistics
        $playerStats = $player->getOrCreateDuelStatistics();
        $playerStats->recordFlee();

        $opponentStats = $opponent->getOrCreateDuelStatistics();
        $opponentStats->recordVictory($duel);

        // Notify both players
        $player->notify(
            'duel_fled',
            'Sei Fuggito',
            'Sei fuggito dal duello. Hai perso 20 punti ranking.',
            'fa-running',
            '/duels'
        );

        $opponent->notify(
            'duel_won',
            'Vittoria!',
            "{$player->name} è fuggito dal duello. Hai vinto!",
            'fa-trophy',
            "/duels/{$duel->id}"
        );

        return true;
    }

    /**
     * Calculate damage for a spell.
     */
    private function calculateDamage(User $attacker, User $defender, Spell $spell, int $proficiency, Duel $duel): array
    {
        $baseDamage = $spell->power;

        // Magic power multiplier (attacker's magic_power stat)
        $magicMultiplier = 1 + ($attacker->magic_power / 100);

        // Proficiency bonus (0.5x to 1.5x based on proficiency)
        $proficiencyMultiplier = 0.5 + ($proficiency / 100);

        // Calculate base damage
        $damage = $baseDamage * $magicMultiplier * $proficiencyMultiplier;

        // Check for critical hit (based on dexterity)
        $criticalChance = min(30, $attacker->dexterity / 10); // Max 30% crit chance
        $isCritical = rand(1, 100) <= $criticalChance;

        if ($isCritical) {
            $damage *= 1.5;
        }

        // Check for dodge (based on defender's dexterity)
        $dodgeChance = min(20, $defender->dexterity / 15); // Max 20% dodge chance
        $isDodged = rand(1, 100) <= $dodgeChance;

        if ($isDodged) {
            $damage = 0;
        }

        // Check if defender is defending (50% damage reduction)
        $isBlocked = $duel->isDefending($defender);
        if ($isBlocked && !$isDodged) {
            $damage *= 0.5;
        }

        // Apply defender's defense stat (reduce damage by defense/10)
        if (!$isDodged) {
            $damageReduction = $defender->defense / 10;
            $damage = max(0, $damage - $damageReduction);
        }

        return [
            'damage' => (int) round($damage),
            'is_critical' => $isCritical,
            'is_dodged' => $isDodged,
            'is_blocked' => $isBlocked,
        ];
    }

    /**
     * End a duel with a winner.
     */
    private function endDuel(Duel $duel, User $winner): void
    {
        $loser = $duel->getOpponentFor($winner);

        $duel->update([
            'status' => 'completed',
            'winner_id' => $winner->id,
            'ended_at' => now(),
        ]);

        if (!$duel->is_practice) {
            // Award experience
            $winner->addExperience($duel->exp_reward);

            // Award money
            $winner->money += $duel->money_reward;
            $winner->save();

            // Deduct money from loser
            $loser->money = max(0, $loser->money - $duel->money_reward);
            $loser->save();

            // Award house points
            if ($duel->house_points_reward > 0 && $winner->team) {
                $team = $winner->house;
                if ($team) {
                    $team->increment('points', $duel->house_points_reward);
                }
            }
        }

        // Update statistics
        $winnerStats = $winner->getOrCreateDuelStatistics();
        $winnerStats->recordVictory($duel);

        $loserStats = $loser->getOrCreateDuelStatistics();
        $loserStats->recordDefeat($duel);

        // Record fastest victory
        $turnCount = $duel->current_turn;
        if (!$winnerStats->fastest_victory_turns || $turnCount < $winnerStats->fastest_victory_turns) {
            $winnerStats->fastest_victory_turns = $turnCount;
            $winnerStats->save();
        }

        // Record longest duel
        if ($turnCount > $winnerStats->longest_duel_turns) {
            $winnerStats->longest_duel_turns = $turnCount;
            $winnerStats->save();
        }
        if ($turnCount > $loserStats->longest_duel_turns) {
            $loserStats->longest_duel_turns = $turnCount;
            $loserStats->save();
        }

        // Notify both players
        $winner->notify(
            'duel_won',
            'Vittoria!',
            "Hai vinto il duello contro {$loser->name}!" .
            ($duel->is_practice ? "" : " Hai guadagnato {$duel->exp_reward} EXP, {$duel->money_reward} Galeoni e {$duel->house_points_reward} punti casa!"),
            'fa-trophy',
            "/duels/{$duel->id}"
        );

        $loser->notify(
            'duel_lost',
            'Sconfitta',
            "Hai perso il duello contro {$winner->name}." .
            ($duel->is_practice ? "" : " Hai perso {$duel->money_reward} Galeoni."),
            'fa-times-circle',
            "/duels/{$duel->id}"
        );
    }

    /**
     * Check and expire old pending duels.
     */
    public function expireOldDuels(): int
    {
        $expiredCount = Duel::where('status', 'pending')
            ->where('expires_at', '<', now())
            ->update(['status' => 'expired']);

        return $expiredCount;
    }
}
