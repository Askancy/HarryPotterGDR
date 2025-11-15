<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthRegenerationLog extends Model
{
    protected $table = 'health_regeneration_log';

    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'source',
    ];

    /**
     * Get the user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
