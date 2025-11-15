<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HouseChat extends Component
{
    public $houseId;
    public $messages = [];
    public $newMessage = '';
    public $onlineMembers = [];
    public $lastMessageId = 0;

    public function mount($houseId)
    {
        $this->houseId = $houseId;
        $this->loadMessages();
        $this->loadOnlineMembers();
    }

    public function loadMessages()
    {
        $query = DB::table('house_messages')
            ->join('users', 'house_messages.user_id', '=', 'users.id')
            ->where('house_messages.house_id', $this->houseId)
            ->select(
                'house_messages.*',
                'users.name as user_name',
                'users.avatar as user_avatar'
            )
            ->orderBy('house_messages.created_at', 'desc')
            ->limit(50);

        $this->messages = $query->get()
            ->map(function($msg) {
                return [
                    'id' => $msg->id,
                    'user_id' => $msg->user_id,
                    'user_name' => $msg->user_name,
                    'user_avatar' => $msg->user_avatar ?: '/images/default-avatar.png',
                    'message' => $msg->message,
                    'message_type' => $msg->message_type,
                    'is_own' => $msg->user_id == Auth::id(),
                    'created_at' => Carbon::parse($msg->created_at)->diffForHumans()
                ];
            })
            ->reverse()
            ->values()
            ->toArray();

        if (count($this->messages) > 0) {
            $this->lastMessageId = $this->messages[count($this->messages) - 1]['id'];
        }
    }

    public function loadOnlineMembers()
    {
        $onlineThreshold = Carbon::now()->subMinutes(5);

        $this->onlineMembers = DB::table('users')
            ->where('team', $this->houseId)
            ->select(
                'id',
                'name',
                'avatar',
                'level',
                DB::raw("CASE WHEN last_activity >= '{$onlineThreshold}' THEN 1 ELSE 0 END as is_online")
            )
            ->orderByRaw('is_online DESC, level DESC')
            ->get()
            ->map(function($member) {
                return [
                    'id' => $member->id,
                    'name' => $member->name,
                    'avatar' => $member->avatar ?: '/images/default-avatar.png',
                    'level' => $member->level ?? 1,
                    'is_online' => (bool)$member->is_online
                ];
            })
            ->toArray();
    }

    public function sendMessage()
    {
        if (empty(trim($this->newMessage))) {
            return;
        }

        if (strlen($this->newMessage) > 500) {
            $this->addError('newMessage', 'Il messaggio non può superare i 500 caratteri.');
            return;
        }

        if (Auth::user()->team != $this->houseId) {
            $this->addError('newMessage', 'Non autorizzato.');
            return;
        }

        $messageId = DB::table('house_messages')->insertGetId([
            'user_id' => Auth::id(),
            'house_id' => $this->houseId,
            'message' => $this->newMessage,
            'message_type' => 'text',
            'is_pinned' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $this->newMessage = '';
        $this->loadMessages();

        // Dispatch event to notify other users
        $this->dispatch('message-sent');
    }

    #[On('refresh-messages')]
    public function refreshMessages()
    {
        $this->loadMessages();
        $this->loadOnlineMembers();
    }

    public function render()
    {
        return view('livewire.house-chat');
    }
}
