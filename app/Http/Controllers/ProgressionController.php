<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\UserSkill;
use App\Models\LevelReward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display user progression.
     */
    public function index()
    {
        $user = Auth::user();
        $userSkills = $user->userSkills()->with('skill')->get();
        $availableSkills = Skill::all();
        $levelRewards = LevelReward::where('level', '>', $user->level)
            ->orderBy('level', 'asc')
            ->limit(5)
            ->get();

        return view('front.pages.progression.index', compact('user', 'userSkills', 'availableSkills', 'levelRewards'));
    }

    /**
     * Allocate skill point.
     */
    public function allocateSkillPoint(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'skill_id' => 'required|exists:skills,id'
        ]);

        if ($user->skill_points <= 0) {
            return back()->with('error', 'Non hai punti abilità disponibili!');
        }

        $skill = Skill::findOrFail($request->skill_id);

        // Get or create user skill
        $userSkill = UserSkill::firstOrCreate(
            [
                'user_id' => $user->id,
                'skill_id' => $skill->id
            ],
            [
                'level' => 0,
                'experience' => 0
            ]
        );

        // Check max level
        if ($userSkill->level >= $skill->max_level) {
            return back()->with('error', 'Hai raggiunto il livello massimo per questa abilità!');
        }

        // Upgrade skill
        $userSkill->level++;
        $userSkill->save();

        // Deduct skill point
        $user->decrement('skill_points', 1);

        $user->notify(
            'skill_upgraded',
            'Abilità Potenziata!',
            "Hai potenziato {$skill->name} al livello {$userSkill->level}!",
            'fa-star',
            '/progression'
        );

        return back()->with('success', "Hai potenziato {$skill->name}!");
    }
}
