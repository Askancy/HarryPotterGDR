<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationShop extends Model
{
    protected $fillable = [
        'location_id',
        'name',
        'slug',
        'description',
        'type',
        'owner_name',
        'image',
        'required_level',
        'is_purchasable',
        'purchase_price',
        'current_owner_id',
        'is_active',
        'inventory',
        'profit_percentage'
    ];

    protected $casts = [
        'location_id' => 'integer',
        'required_level' => 'integer',
        'is_purchasable' => 'boolean',
        'purchase_price' => 'integer',
        'is_active' => 'boolean',
        'inventory' => 'array',
        'profit_percentage' => 'decimal:2'
    ];

    /**
     * Get the location this shop belongs to.
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Get the current owner of the shop.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'current_owner_id');
    }

    /**
     * Get inn messages if this is an inn.
     */
    public function messages()
    {
        return $this->hasMany(InnMessage::class, 'shop_id');
    }

    /**
     * Get current visitors if this is an inn.
     */
    public function visitors()
    {
        return $this->hasMany(InnVisitor::class, 'shop_id');
    }

    /**
     * Check if this is an inn.
     */
    public function isInn()
    {
        return $this->type === 'inn';
    }

    /**
     * Get active visitors (in last 5 minutes).
     */
    public function activeVisitors()
    {
        return $this->visitors()
            ->where('last_activity', '>=', now()->subMinutes(5))
            ->with('user');
    }
}
