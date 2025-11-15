<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'currency',
        'balance_after',
        'description',
        'related_user_id',
        'related_shop_id',
        'reference_type',
        'reference_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relazione con l'utente
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relazione con l'utente correlato (per trasferimenti)
     */
    public function relatedUser()
    {
        return $this->belongsTo(User::class, 'related_user_id');
    }

    /**
     * Relazione con il negozio correlato
     */
    public function relatedShop()
    {
        return $this->belongsTo(LocationShop::class, 'related_shop_id');
    }

    /**
     * Relazione polimorfica con l'oggetto di riferimento
     */
    public function reference()
    {
        return $this->morphTo();
    }

    /**
     * Log transazione
     */
    public static function log(
        User $user,
        string $type,
        float $amount,
        string $description,
        ?User $relatedUser = null,
        ?LocationShop $relatedShop = null,
        ?Model $reference = null,
        string $currency = 'galleons'
    ): self {
        $wallet = Wallet::getOrCreateForUser($user);

        $transaction = static::create([
            'user_id' => $user->id,
            'type' => $type,
            'amount' => $amount,
            'currency' => $currency,
            'balance_after' => $wallet->getTotalInGalleons(),
            'description' => $description,
            'related_user_id' => $relatedUser?->id,
            'related_shop_id' => $relatedShop?->id,
            'reference_type' => $reference ? get_class($reference) : null,
            'reference_id' => $reference?->id,
        ]);

        return $transaction;
    }

    /**
     * Ottieni transazioni recenti dell'utente
     */
    public static function getRecentForUser(User $user, int $limit = 10)
    {
        return static::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Calcola guadagno totale per tipo
     */
    public static function getTotalByType(User $user, string $type): float
    {
        return static::where('user_id', $user->id)
            ->where('type', $type)
            ->sum('amount');
    }

    /**
     * Scope per filtrare per tipo
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope per filtrare guadagni
     */
    public function scopeEarnings($query)
    {
        return $query->whereIn('type', [
            'quest_reward',
            'level_up',
            'event_reward',
            'shop_sale',
            'job_payment',
            'transfer_received',
            'gift',
        ]);
    }

    /**
     * Scope per filtrare spese
     */
    public function scopeExpenses($query)
    {
        return $query->whereIn('type', [
            'shop_purchase',
            'transfer_sent',
            'tax',
            'fine',
        ]);
    }
}
