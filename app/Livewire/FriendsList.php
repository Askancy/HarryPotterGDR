<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Friendship;
use Illuminate\Support\Facades\Auth;

class FriendsList extends Component
{
    public $searchQuery = '';
    public $activeTab = 'friends'; // friends, requests, search

    protected $listeners = ['friendshipUpdated' => '$refresh'];

    public function sendFriendRequest($userId)
    {
        $user = Auth::user();
        $targetUser = User::findOrFail($userId);

        if ($user->sendFriendRequest($targetUser)) {
            $this->dispatch('friendshipUpdated');
            session()->flash('message', 'Richiesta di amicizia inviata!');
        } else {
            session()->flash('error', 'Impossibile inviare la richiesta.');
        }
    }

    public function acceptFriendRequest($friendshipId)
    {
        $friendship = Friendship::findOrFail($friendshipId);

        if ($friendship->friend_id == Auth::id()) {
            $friendship->accept();
            $this->dispatch('friendshipUpdated');
            session()->flash('message', 'Richiesta di amicizia accettata!');
        }
    }

    public function declineFriendRequest($friendshipId)
    {
        $friendship = Friendship::findOrFail($friendshipId);

        if ($friendship->friend_id == Auth::id()) {
            $friendship->decline();
            $this->dispatch('friendshipUpdated');
            session()->flash('message', 'Richiesta di amicizia rifiutata.');
        }
    }

    public function removeFriend($userId)
    {
        $user = Auth::user();

        Friendship::where(function ($q) use ($user, $userId) {
            $q->where('user_id', $user->id)->where('friend_id', $userId);
        })->orWhere(function ($q) use ($user, $userId) {
            $q->where('user_id', $userId)->where('friend_id', $user->id);
        })->delete();

        $this->dispatch('friendshipUpdated');
        session()->flash('message', 'Amicizia rimossa.');
    }

    public function render()
    {
        $user = Auth::user();

        // Get friends
        $friends = $user->friends();

        // Get pending requests
        $pendingRequests = $user->pendingFriendRequests();

        // Search users
        $searchResults = [];
        if ($this->searchQuery && strlen($this->searchQuery) >= 3) {
            $searchResults = User::where('username', 'like', '%' . $this->searchQuery . '%')
                ->where('id', '!=', $user->id)
                ->limit(10)
                ->get();
        }

        return view('livewire.friends-list', compact('friends', 'pendingRequests', 'searchResults'));
    }
}
