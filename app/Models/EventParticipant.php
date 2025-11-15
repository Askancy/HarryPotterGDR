<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventParticipant extends Model
{
    protected $fillable = [
        'user_event_id',
        'user_id',
        'status',
        'contribution'
    ];

    protected $casts = [
        'contribution' => 'array'
    ];

    /**
     * Get the user event.
     */
    public function userEvent()
    {
        return $this->belongsTo(UserRandomEvent::class, 'user_event_id');
    }

    /**
     * Get the user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
