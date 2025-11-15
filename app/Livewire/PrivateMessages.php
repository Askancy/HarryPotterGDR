<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Conversation;
use App\Models\PrivateMessage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PrivateMessages extends Component
{
    public $selectedConversationId = null;
    public $newMessage = '';
    public $searchUser = '';

    protected $listeners = ['messageSent' => 'loadConversation'];

    public function mount($conversationId = null)
    {
        $this->selectedConversationId = $conversationId;
    }

    public function selectConversation($conversationId)
    {
        $this->selectedConversationId = $conversationId;
        $this->markMessagesAsRead($conversationId);
    }

    public function sendMessage()
    {
        if (empty($this->newMessage) || !$this->selectedConversationId) {
            return;
        }

        $conversation = Conversation::findOrFail($this->selectedConversationId);
        $receiver = $conversation->getOtherUser(Auth::id());

        Auth::user()->sendMessage($receiver, $this->newMessage);

        $this->newMessage = '';
        $this->dispatch('messageSent');
    }

    public function startNewConversation($userId)
    {
        $receiver = User::findOrFail($userId);
        $conversation = Conversation::findOrCreate(Auth::id(), $userId);

        $this->selectedConversationId = $conversation->id;
        $this->searchUser = '';
    }

    protected function markMessagesAsRead($conversationId)
    {
        $conversation = Conversation::findOrFail($conversationId);

        PrivateMessage::where('receiver_id', Auth::id())
            ->where(function ($q) use ($conversation) {
                $q->where('sender_id', $conversation->user_one_id)
                  ->orWhere('sender_id', $conversation->user_two_id);
            })
            ->where('is_read', false)
            ->get()
            ->each->markAsRead();
    }

    public function render()
    {
        $user = Auth::user();

        // Get all conversations
        $conversations = $user->conversations();

        // Get selected conversation details
        $selectedConversation = null;
        $messages = [];

        if ($this->selectedConversationId) {
            $selectedConversation = Conversation::find($this->selectedConversationId);
            if ($selectedConversation) {
                $messages = $selectedConversation->messages()->get();
            }
        }

        // Search users for new conversation
        $searchResults = [];
        if ($this->searchUser && strlen($this->searchUser) >= 3) {
            $searchResults = User::where('username', 'like', '%' . $this->searchUser . '%')
                ->where('id', '!=', $user->id)
                ->limit(10)
                ->get();
        }

        return view('livewire.private-messages', compact('conversations', 'selectedConversation', 'messages', 'searchResults'));
    }
}
