<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LocationAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display all locations.
     */
    public function index()
    {
        $locations = Location::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.locations.index', compact('locations'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('admin.locations.create');
    }

    /**
     * Store a new location.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:village,city,landmark,secret',
            'required_level' => 'required|integer|min:1',
            'image' => 'nullable|string'
        ]);

        $location = Location::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'type' => $request->type,
            'image' => $request->image,
            'required_level' => $request->required_level,
            'is_active' => $request->has('is_active'),
            'can_have_events' => $request->has('can_have_events')
        ]);

        return redirect()->route('admin.locations.index')
            ->with('success', 'Località creata con successo!');
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $location = Location::findOrFail($id);
        return view('admin.locations.edit', compact('location'));
    }

    /**
     * Update location.
     */
    public function update(Request $request, $id)
    {
        $location = Location::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:village,city,landmark,secret',
            'required_level' => 'required|integer|min:1'
        ]);

        $location->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'type' => $request->type,
            'image' => $request->image,
            'required_level' => $request->required_level,
            'is_active' => $request->has('is_active'),
            'can_have_events' => $request->has('can_have_events')
        ]);

        return redirect()->route('admin.locations.index')
            ->with('success', 'Località aggiornata con successo!');
    }

    /**
     * Delete location.
     */
    public function destroy($id)
    {
        $location = Location::findOrFail($id);
        $location->delete();

        return redirect()->route('admin.locations.index')
            ->with('success', 'Località eliminata con successo!');
    }
}
