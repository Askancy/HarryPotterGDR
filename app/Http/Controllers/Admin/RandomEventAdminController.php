<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RandomEvent;
use Illuminate\Http\Request;

class RandomEventAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display all events.
     */
    public function index()
    {
        $events = RandomEvent::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('admin.events.create');
    }

    /**
     * Store a new event.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:location,inn,combat,treasure,social,mystery',
            'rarity' => 'required|in:common,uncommon,rare,epic,legendary',
            'required_level' => 'required|integer|min:1',
            'duration_minutes' => 'required|integer|min:1'
        ]);

        $event = RandomEvent::create([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'rarity' => $request->rarity,
            'required_level' => $request->required_level,
            'rewards' => $request->rewards,
            'choices' => $request->choices,
            'duration_minutes' => $request->duration_minutes,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.events.index')
            ->with('success', 'Evento creato con successo!');
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $event = RandomEvent::findOrFail($id);
        return view('admin.events.edit', compact('event'));
    }

    /**
     * Update event.
     */
    public function update(Request $request, $id)
    {
        $event = RandomEvent::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:location,inn,combat,treasure,social,mystery',
            'rarity' => 'required|in:common,uncommon,rare,epic,legendary',
            'required_level' => 'required|integer|min:1',
            'duration_minutes' => 'required|integer|min:1'
        ]);

        $event->update([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'rarity' => $request->rarity,
            'required_level' => $request->required_level,
            'rewards' => $request->rewards,
            'choices' => $request->choices,
            'duration_minutes' => $request->duration_minutes,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.events.index')
            ->with('success', 'Evento aggiornato con successo!');
    }

    /**
     * Delete event.
     */
    public function destroy($id)
    {
        $event = RandomEvent::findOrFail($id);
        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Evento eliminato con successo!');
    }
}
