<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RandomEvent extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'rarity',
        'required_level',
        'rewards',
        'choices',
        'duration_minutes',
        'is_active'
    ];

    protected $casts = [
        'required_level' => 'integer',
        'rewards' => 'array',
        'choices' => 'array',
        'duration_minutes' => 'integer',
        'is_active' => 'boolean'
    ];

    /**
     * Get user events.
     */
    public function userEvents()
    {
        return $this->hasMany(UserRandomEvent::class, 'event_id');
    }

    /**
     * Trigger this event for a user.
     */
    public function triggerForUser(User $user, $locationId = null, $shopId = null)
    {
        return UserRandomEvent::create([
            'user_id' => $user->id,
            'event_id' => $this->id,
            'location_id' => $locationId,
            'shop_id' => $shopId,
            'status' => 'active',
            'triggered_at' => now(),
            'expires_at' => now()->addMinutes($this->duration_minutes)
        ]);
    }

    /**
     * Get random event by rarity.
     */
    public static function getRandomByRarity($rarity = null)
    {
        $query = self::where('is_active', true);

        if ($rarity) {
            $query->where('rarity', $rarity);
        }

        return $query->inRandomOrder()->first();
    }
}
