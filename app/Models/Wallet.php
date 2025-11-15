<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'galleons',
        'sickles',
        'knuts',
        'total_earned',
        'total_spent',
    ];

    protected $casts = [
        'galleons' => 'decimal:2',
        'sickles' => 'decimal:2',
        'knuts' => 'decimal:2',
        'total_earned' => 'decimal:2',
        'total_spent' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Conversione valute magiche:
     * 1 Galleon = 17 Sickles
     * 1 Sickle = 29 Knuts
     * 1 Galleon = 493 Knuts
     */
    const SICKLES_PER_GALLEON = 17;
    const KNUTS_PER_SICKLE = 29;
    const KNUTS_PER_GALLEON = 493; // 17 * 29

    /**
     * Relazione con l'utente
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relazione con le transazioni
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id', 'user_id');
    }

    /**
     * Ottieni il totale in Galleons
     */
    public function getTotalInGalleons(): float
    {
        return $this->galleons
            + ($this->sickles / self::SICKLES_PER_GALLEON)
            + ($this->knuts / self::KNUTS_PER_GALLEON);
    }

    /**
     * Aggiungi denaro
     */
    public function addMoney(float $amount, string $currency = 'galleons'): void
    {
        $this->increment($currency, $amount);
        $this->increment('total_earned', $this->convertToGalleons($amount, $currency));

        // Aggiorna anche user.money per compatibilità
        $this->user->increment('money', $this->convertToGalleons($amount, $currency));
    }

    /**
     * Sottrai denaro
     */
    public function subtractMoney(float $amount, string $currency = 'galleons'): bool
    {
        $currentAmount = $this->$currency;

        if ($currentAmount < $amount) {
            // Prova a convertire da altre valute
            if (!$this->hasEnoughMoney($amount, $currency)) {
                return false;
            }
        }

        $this->decrement($currency, $amount);
        $this->increment('total_spent', $this->convertToGalleons($amount, $currency));

        // Aggiorna anche user.money per compatibilità
        $this->user->decrement('money', $this->convertToGalleons($amount, $currency));

        return true;
    }

    /**
     * Verifica se ha abbastanza denaro
     */
    public function hasEnoughMoney(float $amount, string $currency = 'galleons'): bool
    {
        $requiredInGalleons = $this->convertToGalleons($amount, $currency);
        return $this->getTotalInGalleons() >= $requiredInGalleons;
    }

    /**
     * Converti valuta in Galleons
     */
    protected function convertToGalleons(float $amount, string $currency): float
    {
        return match($currency) {
            'galleons' => $amount,
            'sickles' => $amount / self::SICKLES_PER_GALLEON,
            'knuts' => $amount / self::KNUTS_PER_GALLEON,
            default => 0,
        };
    }

    /**
     * Crea o ottieni il wallet dell'utente
     */
    public static function getOrCreateForUser(User $user): self
    {
        return static::firstOrCreate(
            ['user_id' => $user->id],
            ['galleons' => $user->money ?? 100] // Inizializza con il denaro esistente
        );
    }
}
