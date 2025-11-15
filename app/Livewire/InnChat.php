<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\LocationShop;
use App\Models\InnMessage;
use Illuminate\Support\Facades\Auth;

class InnChat extends Component
{
    public $shopId;
    public $messages = [];
    public $newMessage = '';
    public $visitors = [];

    public function mount($shopId)
    {
        $this->shopId = $shopId;
        $this->loadMessages();
        $this->loadVisitors();
    }

    public function loadMessages()
    {
        $this->messages = InnMessage::where('shop_id', $this->shopId)
            ->where('is_deleted', false)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->limit(50)
            ->get()
            ->toArray();
    }

    public function loadVisitors()
    {
        $shop = LocationShop::find($this->shopId);

        if ($shop) {
            $this->visitors = $shop->activeVisitors()
                ->get()
                ->map(function ($visitor) {
                    return [
                        'id' => $visitor->user->id,
                        'username' => $visitor->user->username,
                        'avatar' => $visitor->user->avatar()
                    ];
                })
                ->toArray();
        }
    }

    public function sendMessage()
    {
        $this->validate([
            'newMessage' => 'required|string|max:500'
        ]);

        $user = Auth::user();

        // Update visitor activity
        $user->enterInn(LocationShop::find($this->shopId));

        // Create message
        InnMessage::create([
            'shop_id' => $this->shopId,
            'user_id' => $user->id,
            'message' => $this->newMessage,
            'message_type' => 'text'
        ]);

        $this->newMessage = '';
        $this->loadMessages();
        $this->dispatch('message-sent');
    }

    #[On('refresh-messages')]
    public function refreshMessages()
    {
        $this->loadMessages();
        $this->loadVisitors();
    }

    public function render()
    {
        return view('livewire.inn-chat');
    }
}
