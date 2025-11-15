<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopInventory extends Model
{
    use HasFactory;

    protected $table = 'shop_inventory';

    protected $fillable = [
        'shop_id',
        'item_type',
        'item_id',
        'quantity',
        'base_price',
        'sell_price',
        'profit_margin',
        'is_available',
        'restock_quantity',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'sell_price' => 'decimal:2',
        'is_available' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relazione con il negozio
     */
    public function shop()
    {
        return $this->belongsTo(LocationShop::class, 'shop_id');
    }

    /**
     * Relazione polimorfica con l'oggetto
     */
    public function item()
    {
        return $this->morphTo(__FUNCTION__, 'item_type', 'item_id');
    }

    /**
     * Relazione con gli acquisti
     */
    public function purchases()
    {
        return $this->hasMany(ShopPurchase::class);
    }

    /**
     * Calcola il prezzo di vendita basato sul margine
     */
    public function calculateSellPrice(): float
    {
        return $this->base_price * (1 + ($this->profit_margin / 100));
    }

    /**
     * Aggiorna il prezzo di vendita
     */
    public function updateSellPrice(): void
    {
        $this->update([
            'sell_price' => $this->calculateSellPrice(),
        ]);
    }

    /**
     * Aggiungi stock
     */
    public function addStock(int $quantity): void
    {
        $this->increment('quantity', $quantity);
    }

    /**
     * Rimuovi stock
     */
    public function removeStock(int $quantity): bool
    {
        if ($this->quantity < $quantity) {
            return false;
        }

        $this->decrement('quantity', $quantity);
        return true;
    }

    /**
     * Riassortimento automatico
     */
    public function restock(): void
    {
        $this->increment('quantity', $this->restock_quantity);
    }

    /**
     * Verifica se l'oggetto è disponibile
     */
    public function isInStock(): bool
    {
        return $this->is_available && $this->quantity > 0;
    }

    /**
     * Boot method per calcolare automaticamente sell_price
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($inventory) {
            if (!$inventory->sell_price) {
                $inventory->sell_price = $inventory->calculateSellPrice();
            }
        });
    }

    /**
     * Aggiungi oggetto all'inventario del negozio
     */
    public static function addItem(
        LocationShop $shop,
        Model $item,
        int $quantity,
        float $basePrice,
        int $profitMargin = 20
    ): self {
        return static::create([
            'shop_id' => $shop->id,
            'item_type' => get_class($item),
            'item_id' => $item->id,
            'quantity' => $quantity,
            'base_price' => $basePrice,
            'profit_margin' => $profitMargin,
            'is_available' => true,
            'restock_quantity' => max(10, $quantity),
        ]);
    }
}
