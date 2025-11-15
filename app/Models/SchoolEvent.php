<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_year_id',
        'school_term_id',
        'name',
        'type',
        'description',
        'event_date',
        'duration_minutes',
        'participants',
        'rewards',
        'is_mandatory',
        'completed',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'participants' => 'array',
        'rewards' => 'array',
        'is_mandatory' => 'boolean',
        'completed' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relazione con l'anno scolastico
     */
    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    /**
     * Relazione con il termine
     */
    public function term()
    {
        return $this->belongsTo(SchoolTerm::class, 'school_term_id');
    }

    /**
     * Relazione con le partecipazioni
     */
    public function participations()
    {
        return $this->hasMany(EventParticipation::class);
    }

    /**
     * Utenti partecipanti
     */
    public function attendees()
    {
        return $this->belongsToMany(User::class, 'event_participations')
            ->withPivot('status', 'score', 'notes')
            ->withTimestamps();
    }

    /**
     * Registra un utente all'evento
     */
    public function registerUser(User $user): EventParticipation
    {
        return EventParticipation::create([
            'school_event_id' => $this->id,
            'user_id' => $user->id,
            'status' => 'registered',
        ]);
    }

    /**
     * Segna l'evento come completato e distribuisci ricompense
     */
    public function complete(): void
    {
        $this->update(['completed' => true]);

        // Distribuisci ricompense agli utenti che hanno partecipato
        if ($this->rewards) {
            foreach ($this->participations()->where('status', 'attended')->get() as $participation) {
                $this->distributeRewards($participation->user);
            }
        }
    }

    /**
     * Distribuisci ricompense a un utente
     */
    protected function distributeRewards(User $user): void
    {
        if (!$this->rewards) {
            return;
        }

        foreach ($this->rewards as $rewardType => $amount) {
            switch ($rewardType) {
                case 'money':
                    $user->addMoney($amount, "Ricompensa evento: {$this->name}");
                    break;
                case 'exp':
                    $user->addExperience($amount);
                    break;
                case 'house_points':
                    // Aggiungi punti alla casa
                    if ($user->team) {
                        // Questo richiede il sistema house_points esistente
                    }
                    break;
            }
        }
    }
}
