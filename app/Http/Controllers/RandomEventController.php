<?php

namespace App\Http\Controllers;

use App\Models\UserRandomEvent;
use App\Models\EventParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RandomEventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display active event.
     */
    public function show($id)
    {
        $user = Auth::user();
        $userEvent = UserRandomEvent::with('event', 'location', 'shop', 'participants.user')
            ->where('user_id', $user->id)
            ->findOrFail($id);

        // Check if expired
        if ($userEvent->isExpired() && $userEvent->status === 'active') {
            $userEvent->update(['status' => 'expired']);
        }

        return view('front.pages.events.show', compact('userEvent', 'user'));
    }

    /**
     * Make a choice in the event.
     */
    public function makeChoice(Request $request, $id)
    {
        $user = Auth::user();
        $userEvent = UserRandomEvent::where('user_id', $user->id)->findOrFail($id);

        if ($userEvent->status !== 'active') {
            return back()->with('error', 'Questo evento non è più attivo!');
        }

        if ($userEvent->isExpired()) {
            $userEvent->update(['status' => 'expired']);
            return back()->with('error', 'Questo evento è scaduto!');
        }

        $request->validate([
            'choice' => 'required|integer'
        ]);

        $choices = $userEvent->event->choices ?? [];

        if (!isset($choices[$request->choice])) {
            return back()->with('error', 'Scelta non valida!');
        }

        // Complete the event
        $userEvent->complete([$request->choice]);

        return back()->with('success', 'Evento completato! Hai ricevuto le ricompense.');
    }

    /**
     * Invite a user to participate in the event.
     */
    public function inviteUser(Request $request, $id)
    {
        $user = Auth::user();
        $userEvent = UserRandomEvent::where('user_id', $user->id)->findOrFail($id);

        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        if ($userEvent->status !== 'active') {
            return back()->with('error', 'Questo evento non è più attivo!');
        }

        // Create invitation
        $participant = EventParticipant::create([
            'user_event_id' => $userEvent->id,
            'user_id' => $request->user_id,
            'status' => 'invited'
        ]);

        // Notify invited user
        $invitedUser = \App\Models\User::find($request->user_id);
        $invitedUser->notify(
            'event_invitation',
            'Invito ad un Evento!',
            "{$user->username} ti ha invitato a partecipare a {$userEvent->event->name}!",
            'fa-envelope',
            "/events/{$userEvent->id}/join"
        );

        return back()->with('success', 'Invito inviato!');
    }

    /**
     * Join an event.
     */
    public function join($id)
    {
        $user = Auth::user();
        $userEvent = UserRandomEvent::findOrFail($id);

        $participant = EventParticipant::where('user_event_id', $userEvent->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $participant->update(['status' => 'joined']);

        return redirect()->route('events.show', $id)
            ->with('success', 'Ti sei unito all\'evento!');
    }
}
