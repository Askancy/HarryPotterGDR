<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class HouseRanking extends Component
{
    public $houses = [];
    public $maxPoints = 0;
    public $refreshInterval = 30000; // 30 seconds

    public function mount()
    {
        $this->loadRanking();
    }

    public function loadRanking()
    {
        $this->houses = DB::table('houses')
            ->orderBy('points', 'desc')
            ->get()
            ->map(function($house, $index) {
                $memberCount = DB::table('users')->where('team', $house->id)->count();

                return [
                    'id' => $house->id,
                    'name' => $house->name,
                    'points' => $house->points ?? 0,
                    'rank' => $index + 1,
                    'members' => $memberCount,
                    'color' => $this->getHouseColor($house->id)
                ];
            })
            ->toArray();

        $this->maxPoints = collect($this->houses)->max('points') ?? 0;
    }

    private function getHouseColor($houseId)
    {
        $colors = [
            1 => '#dc2626', // Grifondoro - Red
            2 => '#166534', // Serpeverde - Green
            3 => '#1e40af', // Corvonero - Blue
            4 => '#eab308'  // Tassorosso - Yellow
        ];

        return $colors[$houseId] ?? '#6b7280';
    }

    public function render()
    {
        return view('livewire.house-ranking');
    }
}
