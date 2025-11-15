<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRandomEvent extends Model
{
    protected $fillable = [
        'user_id',
        'event_id',
        'location_id',
        'shop_id',
        'status',
        'choices_made',
        'rewards_received',
        'triggered_at',
        'expires_at',
        'completed_at'
    ];

    protected $casts = [
        'choices_made' => 'array',
        'rewards_received' => 'array',
        'triggered_at' => 'datetime',
        'expires_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    /**
     * Get the user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the event.
     */
    public function event()
    {
        return $this->belongsTo(RandomEvent::class, 'event_id');
    }

    /**
     * Get the location.
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Get the shop (inn).
     */
    public function shop()
    {
        return $this->belongsTo(LocationShop::class, 'shop_id');
    }

    /**
     * Get participants.
     */
    public function participants()
    {
        return $this->hasMany(EventParticipant::class, 'user_event_id');
    }

    /**
     * Check if event is expired.
     */
    public function isExpired()
    {
        return now()->greaterThan($this->expires_at);
    }

    /**
     * Complete the event and give rewards.
     */
    public function complete($choices = [])
    {
        $this->update([
            'status' => 'completed',
            'choices_made' => $choices,
            'rewards_received' => $this->event->rewards,
            'completed_at' => now()
        ]);

        // Give rewards to user
        $rewards = $this->event->rewards;

        if (isset($rewards['exp'])) {
            $this->user->addExperience($rewards['exp']);
        }

        if (isset($rewards['money'])) {
            $this->user->increment('money', $rewards['money']);
        }

        return $this;
    }
}
