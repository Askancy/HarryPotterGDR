<?php

namespace App\Http\Controllers;

use App\Models\Perk;
use App\Models\PerkCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerkController extends Controller
{
    /**
     * Show perks tree.
     */
    public function index()
    {
        $user = Auth::user();

        $categories = PerkCategory::with(['perks' => function($query) {
            $query->where('is_active', true)->orderBy('required_level');
        }])->orderBy('order')->get();

        $userPerks = $user->perks->keyBy('id');

        return view('perks.index', compact('categories', 'userPerks', 'user'));
    }

    /**
     * Unlock a perk.
     */
    public function unlock(Request $request, $id)
    {
        $perk = Perk::findOrFail($id);
        $user = Auth::user();

        if ($user->unlockPerk($perk)) {
            return redirect()->back()->with('message', "Talento '{$perk->name}' sbloccato con successo!");
        }

        return redirect()->back()->with('error', 'Non puoi sbloccare questo talento. Verifica i requisiti e i punti talento disponibili.');
    }

    /**
     * Toggle perk equipment status.
     */
    public function toggle(Request $request, $id)
    {
        $perk = Perk::findOrFail($id);
        $user = Auth::user();

        $userPerk = $user->perks()->where('perk_id', $perk->id)->first();

        if (!$userPerk) {
            return redirect()->back()->with('error', 'Non possiedi questo talento!');
        }

        $newStatus = !$userPerk->pivot->is_equipped;
        $user->perks()->updateExistingPivot($perk->id, ['is_equipped' => $newStatus]);

        $message = $newStatus ? 'Talento attivato!' : 'Talento disattivato!';
        return redirect()->back()->with('message', $message);
    }
}
