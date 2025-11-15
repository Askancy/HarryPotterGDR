<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ActiveEvents extends Component
{
    public $activeEvents = [];

    public function mount()
    {
        $this->loadEvents();
    }

    public function loadEvents()
    {
        $user = Auth::user();

        $this->activeEvents = $user->activeEvents()
            ->with('event', 'location', 'shop')
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.active-events');
    }
}
