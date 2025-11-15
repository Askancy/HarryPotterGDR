<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquippedClothing extends Model
{
    protected $table = 'equipped_clothing';

    protected $fillable = [
        'user_id',
        'slot',
        'clothing_id',
        'equipped_at',
    ];

    protected $casts = [
        'equipped_at' => 'datetime',
    ];

    /**
     * Get the user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the clothing.
     */
    public function clothing()
    {
        return $this->belongsTo(Clothing::class);
    }
}
