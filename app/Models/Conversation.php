<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'user_one_id',
        'user_two_id',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    /**
     * Get user one.
     */
    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    /**
     * Get user two.
     */
    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    /**
     * Get messages in this conversation.
     */
    public function messages()
    {
        return PrivateMessage::where(function ($q) {
            $q->where('sender_id', $this->user_one_id)
              ->where('receiver_id', $this->user_two_id);
        })->orWhere(function ($q) {
            $q->where('sender_id', $this->user_two_id)
              ->where('receiver_id', $this->user_one_id);
        })->orderBy('created_at', 'desc');
    }

    /**
     * Get the other user in conversation.
     */
    public function getOtherUser($currentUserId)
    {
        return $this->user_one_id == $currentUserId
            ? $this->userTwo
            : $this->userOne;
    }

    /**
     * Get unread count for a user.
     */
    public function getUnreadCountFor($userId)
    {
        return PrivateMessage::where('receiver_id', $userId)
            ->where(function ($q) {
                $q->where('sender_id', $this->user_one_id)
                  ->orWhere('sender_id', $this->user_two_id);
            })
            ->where('is_read', false)
            ->count();
    }

    /**
     * Find or create conversation between two users.
     */
    public static function findOrCreate($userId1, $userId2)
    {
        // Ensure consistent ordering
        $userOne = min($userId1, $userId2);
        $userTwo = max($userId1, $userId2);

        return static::firstOrCreate([
            'user_one_id' => $userOne,
            'user_two_id' => $userTwo,
        ]);
    }
}
