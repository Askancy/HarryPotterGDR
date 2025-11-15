<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Http\Controllers\HousePointsController as BaseHousePointsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HousePointsManager extends Component
{
    public $houses = [];
    public $selectedHouse = null;
    public $points = 0;
    public $type = 'manual';
    public $reason = '';
    public $userId = null;

    public $showAwardModal = false;

    public function mount()
    {
        $this->loadHouses();
    }

    public function loadHouses()
    {
        $this->houses = DB::table('houses')
            ->orderBy('points', 'desc')
            ->get()
            ->toArray();
    }

    public function openAwardModal($houseId)
    {
        $this->selectedHouse = collect($this->houses)->firstWhere('id', $houseId);
        $this->showAwardModal = true;
        $this->reset(['points', 'type', 'reason', 'userId']);
        $this->type = 'manual';
    }

    public function closeModal()
    {
        $this->showAwardModal = false;
        $this->reset(['selectedHouse', 'points', 'type', 'reason', 'userId']);
    }

    public function awardPoints()
    {
        $this->validate([
            'points' => 'required|integer|min:-1000|max:1000',
            'type' => 'required|in:manual,quest_complete,achievement,event_win,good_behavior,rule_violation,competition,attendance,system',
            'reason' => 'required|string|max:255',
        ]);

        if (!$this->selectedHouse) {
            $this->addError('general', 'Nessuna casa selezionata');
            return;
        }

        BaseHousePointsController::awardPoints(
            $this->selectedHouse->id,
            $this->points,
            $this->type,
            $this->reason,
            $this->userId,
            Auth::id(),
            null
        );

        // Create announcement
        if ($this->points > 0) {
            DB::table('house_announcements')->insert([
                'house_id' => $this->selectedHouse->id,
                'title' => "🎉 {$this->points} Punti Guadagnati!",
                'content' => $this->reason,
                'priority' => $this->points >= 50 ? 'high' : 'medium',
                'created_at' => now(),
                'updated_at' => now(),
                'expires_at' => now()->addDays(3)
            ]);
        } elseif ($this->points < 0) {
            DB::table('house_announcements')->insert([
                'house_id' => $this->selectedHouse->id,
                'title' => "⚠️ {$this->points} Punti Persi",
                'content' => $this->reason,
                'priority' => 'urgent',
                'created_at' => now(),
                'updated_at' => now(),
                'expires_at' => now()->addDays(3)
            ]);
        }

        $this->loadHouses();
        $this->closeModal();

        session()->flash('success', "Punti assegnati con successo a {$this->selectedHouse->name}!");

        $this->dispatch('points-awarded');
    }

    public function render()
    {
        return view('livewire.admin.house-points-manager');
    }
}
