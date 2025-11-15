<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SkillAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display all skills.
     */
    public function index()
    {
        $skills = Skill::orderBy('category')->orderBy('name')->paginate(20);
        return view('admin.skills.index', compact('skills'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('admin.skills.create');
    }

    /**
     * Store a new skill.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:combat,magic,defense,herbology,potions,divination,charms,transfiguration',
            'max_level' => 'required|integer|min:1|max:100'
        ]);

        $skill = Skill::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'category' => $request->category,
            'max_level' => $request->max_level,
            'icon' => $request->icon,
            'bonuses' => $request->bonuses
        ]);

        return redirect()->route('admin.skills.index')
            ->with('success', 'Abilità creata con successo!');
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $skill = Skill::findOrFail($id);
        return view('admin.skills.edit', compact('skill'));
    }

    /**
     * Update skill.
     */
    public function update(Request $request, $id)
    {
        $skill = Skill::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:combat,magic,defense,herbology,potions,divination,charms,transfiguration',
            'max_level' => 'required|integer|min:1|max:100'
        ]);

        $skill->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'category' => $request->category,
            'max_level' => $request->max_level,
            'icon' => $request->icon,
            'bonuses' => $request->bonuses
        ]);

        return redirect()->route('admin.skills.index')
            ->with('success', 'Abilità aggiornata con successo!');
    }

    /**
     * Delete skill.
     */
    public function destroy($id)
    {
        $skill = Skill::findOrFail($id);
        $skill->delete();

        return redirect()->route('admin.skills.index')
            ->with('success', 'Abilità eliminata con successo!');
    }
}
