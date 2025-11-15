<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\RandomEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display all locations.
     */
    public function index()
    {
        $user = Auth::user();
        $locations = Location::where('is_active', true)
            ->orderBy('required_level', 'asc')
            ->get();

        return view('front.pages.locations.index', compact('locations', 'user'));
    }

    /**
     * Display a specific location.
     */
    public function show($slug)
    {
        $user = Auth::user();
        $location = Location::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Check if user can access
        if (!$location->canBeAccessedBy($user)) {
            return redirect()->route('locations.index')
                ->with('error', "Devi essere almeno livello {$location->required_level} per visitare questa località!");
        }

        // Get shops in this location
        $shops = $location->shops()->where('is_active', true)->get();

        // Get current visitors
        $visitors = $location->currentVisitors()
            ->where('id', '!=', $user->id)
            ->limit(10)
            ->get();

        return view('front.pages.locations.show', compact('location', 'shops', 'visitors', 'user'));
    }

    /**
     * Travel to a location.
     */
    public function travel($slug)
    {
        $user = Auth::user();
        $location = Location::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        if ($user->travelTo($location)) {
            // Chance to trigger a random event
            $this->triggerRandomEvent($user, $location);

            return redirect()->route('locations.show', $slug)
                ->with('success', "Sei arrivato a {$location->name}!");
        }

        return redirect()->route('locations.index')
            ->with('error', "Non puoi viaggiare verso {$location->name}!");
    }

    /**
     * Trigger a random event.
     */
    private function triggerRandomEvent($user, $location)
    {
        // 20% chance to trigger an event
        if (rand(1, 100) <= 20 && $location->can_have_events) {
            // Determine rarity based on probability
            $rand = rand(1, 100);
            if ($rand <= 5) {
                $rarity = 'legendary';
            } elseif ($rand <= 15) {
                $rarity = 'epic';
            } elseif ($rand <= 35) {
                $rarity = 'rare';
            } elseif ($rand <= 60) {
                $rarity = 'uncommon';
            } else {
                $rarity = 'common';
            }

            $event = RandomEvent::where('is_active', true)
                ->where('rarity', $rarity)
                ->where('required_level', '<=', $user->level)
                ->where('type', 'location')
                ->inRandomOrder()
                ->first();

            if ($event) {
                $userEvent = $event->triggerForUser($user, $location->id);

                $user->notify(
                    'event_started',
                    'Evento Casuale!',
                    $event->name,
                    'fa-star',
                    "/events/{$userEvent->id}"
                );
            }
        }
    }
}
