<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Duel extends Model
{
    protected $fillable = [
        'challenger_id',
        'opponent_id',
        'winner_id',
        'status',
        'location_id',
        'current_turn',
        'current_player_id',
        'challenger_health',
        'challenger_mana',
        'opponent_health',
        'opponent_mana',
        'challenger_current_health',
        'challenger_current_mana',
        'opponent_current_health',
        'opponent_current_mana',
        'challenger_effects',
        'opponent_effects',
        'exp_reward',
        'money_reward',
        'house_points_reward',
        'is_practice',
        'challenger_defending',
        'opponent_defending',
        'started_at',
        'ended_at',
        'expires_at',
    ];

    protected $casts = [
        'challenger_effects' => 'array',
        'opponent_effects' => 'array',
        'is_practice' => 'boolean',
        'challenger_defending' => 'boolean',
        'opponent_defending' => 'boolean',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    // Relationships
    public function challenger(): BelongsTo
    {
        return $this->belongsTo(User::class, 'challenger_id');
    }

    public function opponent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'opponent_id');
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function currentPlayer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'current_player_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function turns(): HasMany
    {
        return $this->hasMany(DuelTurn::class)->orderBy('turn_number');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('challenger_id', $userId)
              ->orWhere('opponent_id', $userId);
        });
    }

    // Helper methods
    public function isPlayerTurn(User $user): bool
    {
        return $this->current_player_id === $user->id;
    }

    public function getOpponentFor(User $user): ?User
    {
        if ($this->challenger_id === $user->id) {
            return $this->opponent;
        }
        return $this->challenger;
    }

    public function getCurrentHealthFor(User $user): int
    {
        if ($this->challenger_id === $user->id) {
            return $this->challenger_current_health;
        }
        return $this->opponent_current_health;
    }

    public function getCurrentManaFor(User $user): int
    {
        if ($this->challenger_id === $user->id) {
            return $this->challenger_current_mana;
        }
        return $this->opponent_current_mana;
    }

    public function isDefending(User $user): bool
    {
        if ($this->challenger_id === $user->id) {
            return $this->challenger_defending;
        }
        return $this->opponent_defending;
    }

    public function getEffectsFor(User $user): array
    {
        if ($this->challenger_id === $user->id) {
            return $this->challenger_effects ?? [];
        }
        return $this->opponent_effects ?? [];
    }

    public function updateHealth(User $user, int $newHealth): void
    {
        if ($this->challenger_id === $user->id) {
            $this->challenger_current_health = max(0, min($newHealth, $this->challenger_health));
        } else {
            $this->opponent_current_health = max(0, min($newHealth, $this->opponent_health));
        }
        $this->save();
    }

    public function updateMana(User $user, int $newMana): void
    {
        if ($this->challenger_id === $user->id) {
            $this->challenger_current_mana = max(0, min($newMana, $this->challenger_mana));
        } else {
            $this->opponent_current_mana = max(0, min($newMana, $this->opponent_mana));
        }
        $this->save();
    }

    public function setDefending(User $user, bool $defending): void
    {
        if ($this->challenger_id === $user->id) {
            $this->challenger_defending = $defending;
        } else {
            $this->opponent_defending = $defending;
        }
        $this->save();
    }

    public function nextTurn(): void
    {
        $this->current_turn++;

        // Switch current player
        if ($this->current_player_id === $this->challenger_id) {
            $this->current_player_id = $this->opponent_id;
        } else {
            $this->current_player_id = $this->challenger_id;
        }

        $this->save();
    }

    public function getWinPercentage(): float
    {
        if ($this->challenger_current_health <= 0) {
            return 100.0;
        }
        if ($this->opponent_current_health <= 0) {
            return 0.0;
        }

        $challengerHealthPercent = ($this->challenger_current_health / $this->challenger_health) * 100;
        $opponentHealthPercent = ($this->opponent_current_health / $this->opponent_health) * 100;

        return $opponentHealthPercent / ($challengerHealthPercent + $opponentHealthPercent) * 100;
    }
}
