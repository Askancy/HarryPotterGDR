<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventParticipation extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_event_id',
        'user_id',
        'status',
        'score',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relazione con l'evento
     */
    public function event()
    {
        return $this->belongsTo(SchoolEvent::class, 'school_event_id');
    }

    /**
     * Relazione con l'utente
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Segna come partecipato
     */
    public function markAttended(?int $score = null): void
    {
        $this->update([
            'status' => 'attended',
            'score' => $score,
        ]);
    }

    /**
     * Segna come assente
     */
    public function markMissed(?string $notes = null): void
    {
        $this->update([
            'status' => 'missed',
            'notes' => $notes,
        ]);
    }
}
