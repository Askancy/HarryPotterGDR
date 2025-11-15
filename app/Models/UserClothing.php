<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserClothing extends Model
{
    protected $table = 'user_clothing';

    protected $fillable = [
        'user_id',
        'clothing_id',
        'quantity',
        'acquired_at',
    ];

    protected $casts = [
        'acquired_at' => 'datetime',
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
