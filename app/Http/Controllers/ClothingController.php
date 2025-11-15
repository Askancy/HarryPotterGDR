<?php

namespace App\Http\Controllers;

use App\Models\Clothing;
use App\Models\UserClothing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClothingController extends Controller
{
    /**
     * Show clothing inventory.
     */
    public function index()
    {
        return view('clothing.index');
    }

    /**
     * Equip a clothing item.
     */
    public function equip(Request $request)
    {
        $validated = $request->validate([
            'clothing_id' => 'required|exists:clothing,id',
        ]);

        $user = Auth::user();
        $clothing = Clothing::findOrFail($validated['clothing_id']);

        if ($user->equipClothing($clothing)) {
            return response()->json([
                'success' => true,
                'message' => 'Vestito equipaggiato con successo!',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Non puoi equipaggiare questo vestito.',
        ], 400);
    }

    /**
     * Unequip a clothing slot.
     */
    public function unequip(Request $request)
    {
        $validated = $request->validate([
            'slot' => 'required|string|in:hat,robe,shirt,pants,shoes,accessory,cloak',
        ]);

        $user = Auth::user();
        $user->unequipSlot($validated['slot']);

        return response()->json([
            'success' => true,
            'message' => 'Vestito rimosso.',
        ]);
    }

    /**
     * Show clothing shop.
     */
    public function shop()
    {
        $clothing = Clothing::where('is_available', true)
            ->orderBy('rarity')
            ->orderBy('required_level')
            ->get()
            ->groupBy('type');

        return view('clothing.shop', compact('clothing'));
    }

    /**
     * Purchase clothing.
     */
    public function purchase(Request $request)
    {
        $validated = $request->validate([
            'clothing_id' => 'required|exists:clothing,id',
        ]);

        $user = Auth::user();
        $clothing = Clothing::findOrFail($validated['clothing_id']);

        // Check if user already owns this clothing
        $hasClothing = $user->clothing()->where('clothing_id', $clothing->id)->exists();
        if ($hasClothing) {
            return redirect()->back()->with('error', 'Possiedi già questo vestito!');
        }

        // Check if user has enough money
        if ($user->money < $clothing->price) {
            return redirect()->back()->with('error', 'Non hai abbastanza denaro!');
        }

        // Check level requirement
        if (!$clothing->canBeWornBy($user)) {
            return redirect()->back()->with('error', 'Non soddisfi i requisiti per questo vestito!');
        }

        // Purchase clothing
        $user->money -= $clothing->price;
        $user->save();

        $user->clothing()->attach($clothing->id, [
            'quantity' => 1,
            'acquired_at' => now(),
        ]);

        return redirect()->back()->with('message', "Hai acquistato {$clothing->name}!");
    }
}
