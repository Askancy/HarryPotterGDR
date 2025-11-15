<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InnMessage extends Model
{
    protected $fillable = [
        'shop_id',
        'user_id',
        'message',
        'message_type',
        'is_deleted'
    ];

    protected $casts = [
        'is_deleted' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the shop (inn) this message belongs to.
     */
    public function shop()
    {
        return $this->belongsTo(LocationShop::class, 'shop_id');
    }

    /**
     * Get the user who sent the message.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for non-deleted messages.
     */
    public function scopeActive($query)
    {
        return $query->where('is_deleted', false);
    }
}
