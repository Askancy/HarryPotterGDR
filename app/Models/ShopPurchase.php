<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'buyer_id',
        'shop_inventory_id',
        'quantity',
        'unit_price',
        'total_price',
        'shop_profit',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'shop_profit' => 'decimal:2',
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
     * Relazione con il compratore
     */
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /**
     * Relazione con l'inventario
     */
    public function inventoryItem()
    {
        return $this->belongsTo(ShopInventory::class, 'shop_inventory_id');
    }

    /**
     * Processa un acquisto
     */
    public static function process(
        LocationShop $shop,
        User $buyer,
        ShopInventory $inventoryItem,
        int $quantity
    ): ?self {
        // Verifica disponibilità
        if (!$inventoryItem->isInStock() || $inventoryItem->quantity < $quantity) {
            return null;
        }

        $totalPrice = $inventoryItem->sell_price * $quantity;

        // Verifica che il compratore abbia abbastanza soldi
        $wallet = Wallet::getOrCreateForUser($buyer);
        if (!$wallet->hasEnoughMoney($totalPrice)) {
            return null;
        }

        // Sottrai denaro dal compratore
        $wallet->subtractMoney($totalPrice);

        // Registra transazione
        Transaction::log(
            $buyer,
            'shop_purchase',
            $totalPrice,
            "Acquisto da {$shop->name}",
            relatedShop: $shop
        );

        // Rimuovi stock
        $inventoryItem->removeStock($quantity);

        // Calcola profitto
        $baseTotal = $inventoryItem->base_price * $quantity;
        $profit = $totalPrice - $baseTotal;

        // Crea record acquisto
        $purchase = static::create([
            'shop_id' => $shop->id,
            'buyer_id' => $buyer->id,
            'shop_inventory_id' => $inventoryItem->id,
            'quantity' => $quantity,
            'unit_price' => $inventoryItem->sell_price,
            'total_price' => $totalPrice,
            'shop_profit' => $profit,
        ]);

        // Se il negozio ha un proprietario, aggiungi il profitto
        if ($shop->current_owner_id) {
            $owner = $shop->owner;
            $ownerWallet = Wallet::getOrCreateForUser($owner);
            $ownerWallet->addMoney($profit);

            Transaction::log(
                $owner,
                'shop_sale',
                $profit,
                "Vendita nel negozio {$shop->name}",
                relatedShop: $shop
            );
        }

        return $purchase;
    }

    /**
     * Ottieni statistiche acquisti per negozio
     */
    public static function getShopStats(LocationShop $shop, ?string $period = 'all'): array
    {
        $query = static::where('shop_id', $shop->id);

        if ($period === 'today') {
            $query->whereDate('created_at', today());
        } elseif ($period === 'week') {
            $query->where('created_at', '>=', now()->subWeek());
        } elseif ($period === 'month') {
            $query->where('created_at', '>=', now()->subMonth());
        }

        return [
            'total_sales' => $query->sum('total_price'),
            'total_profit' => $query->sum('shop_profit'),
            'total_transactions' => $query->count(),
            'unique_customers' => $query->distinct('buyer_id')->count(),
        ];
    }
}
