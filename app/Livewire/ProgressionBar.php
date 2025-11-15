<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ProgressionBar extends Component
{
    public $currentExp;
    public $requiredExp;
    public $level;
    public $percentage;

    public function mount()
    {
        $this->loadProgress();
    }

    public function loadProgress()
    {
        $user = Auth::user();

        $this->currentExp = $user->current_exp;
        $this->requiredExp = $user->required_exp;
        $this->level = $user->level;
        $this->percentage = ($this->requiredExp > 0) ? round(($this->currentExp / $this->requiredExp) * 100, 2) : 0;
    }

    public function render()
    {
        return view('livewire.progression-bar');
    }
}
