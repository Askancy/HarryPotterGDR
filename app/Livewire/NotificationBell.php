<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationBell extends Component
{
    public $unreadCount = 0;
    public $notifications = [];
    public $showDropdown = false;

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $user = Auth::user();

        $this->unreadCount = $user->unreadNotificationsCount();
        $this->notifications = $user->notifications()
            ->limit(5)
            ->get()
            ->toArray();
    }

    public function markAsRead($notificationId)
    {
        $user = Auth::user();
        $notification = $user->notifications()->find($notificationId);

        if ($notification) {
            $notification->markAsRead();
            $this->loadNotifications();
        }
    }

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }

    // Auto-refresh every 30 seconds
    public function getListeners()
    {
        return [
            'echo:notifications.' . Auth::id() . ',NotificationSent' => 'loadNotifications',
        ];
    }
}
