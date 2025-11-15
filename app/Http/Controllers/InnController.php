<?php

namespace App\Http\Controllers;

use App\Models\LocationShop;
use App\Models\InnMessage;
use App\Models\InnVisitor;
use App\Models\RandomEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InnController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display an inn.
     */
    public function show($slug)
    {
        $user = Auth::user();
        $inn = LocationShop::where('slug', $slug)
            ->where('type', 'inn')
            ->where('is_active', true)
            ->with('location')
            ->firstOrFail();

        // Enter the inn
        $user->enterInn($inn);

        // Get recent messages
        $messages = $inn->messages()
            ->where('is_deleted', false)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->reverse();

        // Get active visitors
        $visitors = $inn->activeVisitors()->get();

        return view('front.pages.inns.show', compact('inn', 'messages', 'visitors', 'user'));
    }

    /**
     * Leave an inn.
     */
    public function leave($slug)
    {
        $user = Auth::user();
        $inn = LocationShop::where('slug', $slug)
            ->where('type', 'inn')
            ->firstOrFail();

        $user->leaveInn($inn);

        return redirect()->route('locations.show', $inn->location->slug)
            ->with('success', "Hai lasciato {$inn->name}.");
    }

    /**
     * Trigger a random event in the inn.
     */
    public function triggerEvent($slug)
    {
        $user = Auth::user();
        $inn = LocationShop::where('slug', $slug)
            ->where('type', 'inn')
            ->firstOrFail();

        // Check if user has an active event
        if ($user->activeEvents()->count() > 0) {
            return back()->with('error', 'Hai già un evento attivo!');
        }

        // Get a random inn event
        $event = RandomEvent::where('is_active', true)
            ->where('type', 'inn')
            ->where('required_level', '<=', $user->level)
            ->inRandomOrder()
            ->first();

        if (!$event) {
            return back()->with('error', 'Nessun evento disponibile al momento.');
        }

        $userEvent = $event->triggerForUser($user, null, $inn->id);

        // Send system message to inn
        InnMessage::create([
            'shop_id' => $inn->id,
            'user_id' => $user->id,
            'message' => "🎲 Un evento è iniziato: {$event->name}!",
            'message_type' => 'system'
        ]);

        $user->notify(
            'event_started',
            'Evento nella Locanda!',
            $event->name,
            'fa-dice',
            "/events/{$userEvent->id}"
        );

        return back()->with('success', 'Evento avviato!');
    }
}
