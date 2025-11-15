<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPerk extends Model
{
    protected $fillable = [
        'user_id',
        'perk_id',
        'rank',
        'is_equipped',
        'unlocked_at',
    ];

    protected $casts = [
        'is_equipped' => 'boolean',
        'unlocked_at' => 'datetime',
    ];

    /**
     * Get the user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the perk.
     */
    public function perk()
    {
        return $this->belongsTo(Perk::class);
    }
}
