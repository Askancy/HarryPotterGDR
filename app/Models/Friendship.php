<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    protected $fillable = [
        'user_id',
        'friend_id',
        'status',
        'requested_at',
        'responded_at',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    /**
     * Get the user who sent the request.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user who received the request.
     */
    public function friend()
    {
        return $this->belongsTo(User::class, 'friend_id');
    }

    /**
     * Scope for pending requests.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for accepted friendships.
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    /**
     * Accept friendship request.
     */
    public function accept()
    {
        $this->status = 'accepted';
        $this->responded_at = now();
        $this->save();

        // Create notification for sender
        $this->user->notify(
            'friendship_accepted',
            'Richiesta di Amicizia Accettata',
            "{$this->friend->username} ha accettato la tua richiesta di amicizia!",
            'fa-user-friends',
            '/profile/' . $this->friend->slug
        );

        return $this;
    }

    /**
     * Decline friendship request.
     */
    public function decline()
    {
        $this->status = 'declined';
        $this->responded_at = now();
        $this->save();

        return $this;
    }

    /**
     * Block user.
     */
    public function block()
    {
        $this->status = 'blocked';
        $this->responded_at = now();
        $this->save();

        return $this;
    }
}
