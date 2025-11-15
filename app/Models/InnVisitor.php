<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InnVisitor extends Model
{
    protected $fillable = [
        'shop_id',
        'user_id',
        'entered_at',
        'last_activity'
    ];

    protected $casts = [
        'entered_at' => 'datetime',
        'last_activity' => 'datetime'
    ];

    /**
     * Get the shop (inn).
     */
    public function shop()
    {
        return $this->belongsTo(LocationShop::class, 'shop_id');
    }

    /**
     * Get the user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Update last activity timestamp.
     */
    public function updateActivity()
    {
        $this->update(['last_activity' => now()]);
    }

    /**
     * Check if visitor is still active (within last 5 minutes).
     */
    public function isActive()
    {
        return $this->last_activity >= now()->subMinutes(5);
    }
}
