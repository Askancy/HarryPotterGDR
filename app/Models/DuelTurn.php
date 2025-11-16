<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DuelTurn extends Model
{
    protected $fillable = [
        'duel_id',
        'turn_number',
        'player_id',
        'action_type',
        'spell_id',
        'item_used',
        'damage_dealt',
        'healing_done',
        'mana_used',
        'mana_restored',
        'is_critical',
        'is_dodged',
        'is_blocked',
        'effects_applied',
        'description',
        'player_health_after',
        'player_mana_after',
        'opponent_health_after',
        'opponent_mana_after',
    ];

    protected $casts = [
        'effects_applied' => 'array',
        'is_critical' => 'boolean',
        'is_dodged' => 'boolean',
        'is_blocked' => 'boolean',
    ];

    // Relationships
    public function duel(): BelongsTo
    {
        return $this->belongsTo(Duel::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player_id');
    }

    public function spell(): BelongsTo
    {
        return $this->belongsTo(Spell::class);
    }

    // Helper methods
    public function isSpellAction(): bool
    {
        return $this->action_type === 'spell';
    }

    public function isDefendAction(): bool
    {
        return $this->action_type === 'defend';
    }

    public function isItemAction(): bool
    {
        return $this->action_type === 'item';
    }

    public function isFleeAction(): bool
    {
        return $this->action_type === 'flee';
    }

    public function wasSuccessful(): bool
    {
        return !$this->is_dodged && !$this->is_blocked;
    }

    public function getActionDescription(): string
    {
        if ($this->description) {
            return $this->description;
        }

        $playerName = $this->player->name ?? 'Giocatore';

        switch ($this->action_type) {
            case 'spell':
                $spellName = $this->spell->name ?? 'Incantesimo';
                if ($this->is_critical) {
                    return "{$playerName} lancia {$spellName} con un COLPO CRITICO per {$this->damage_dealt} danni!";
                } elseif ($this->is_dodged) {
                    return "{$playerName} lancia {$spellName}, ma l'avversario schiva!";
                } elseif ($this->is_blocked) {
                    return "{$playerName} lancia {$spellName}, ma viene bloccato dalla difesa!";
                } elseif ($this->damage_dealt > 0) {
                    return "{$playerName} lancia {$spellName} infliggendo {$this->damage_dealt} danni!";
                } elseif ($this->healing_done > 0) {
                    return "{$playerName} lancia {$spellName} curando {$this->healing_done} HP!";
                } else {
                    return "{$playerName} lancia {$spellName}!";
                }

            case 'defend':
                return "{$playerName} si mette in posizione difensiva!";

            case 'item':
                return "{$playerName} usa {$this->item_used}!";

            case 'flee':
                return "{$playerName} fugge dal duello!";

            default:
                return "{$playerName} esegue un'azione.";
        }
    }
}
