<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Clothing;
use Illuminate\Support\Facades\Auth;

class ClothingInventory extends Component
{
    public $selectedSlot = null;
    public $showStats = true;

    protected $listeners = ['clothingEquipped' => '$refresh'];

    public function equipClothing($clothingId)
    {
        $user = Auth::user();
        $clothing = Clothing::findOrFail($clothingId);

        if ($user->equipClothing($clothing)) {
            $this->dispatch('clothingEquipped');
            session()->flash('message', 'Vestito equipaggiato con successo!');
        } else {
            session()->flash('error', 'Non puoi equipaggiare questo vestito.');
        }
    }

    public function unequipSlot($slot)
    {
        $user = Auth::user();
        $user->unequipSlot($slot);

        $this->dispatch('clothingEquipped');
        session()->flash('message', 'Vestito rimosso.');
    }

    public function render()
    {
        $user = Auth::user();

        // Get user's inventory grouped by type
        $inventory = $user->clothing()
            ->with('users')
            ->get()
            ->groupBy('type');

        // Get equipped items
        $equipped = $user->equippedClothing()->with('clothing')->get()->keyBy('slot');

        // Get clothing stats
        $stats = $user->getClothingStats();

        // Available slots
        $slots = [
            'hat' => 'Cappello',
            'cloak' => 'Mantello',
            'robe' => 'Veste',
            'shirt' => 'Camicia',
            'pants' => 'Pantaloni',
            'shoes' => 'Scarpe',
            'accessory' => 'Accessorio',
        ];

        return view('livewire.clothing-inventory', compact('inventory', 'equipped', 'stats', 'slots'));
    }
}
