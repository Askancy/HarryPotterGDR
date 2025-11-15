<?php

namespace App\Http\Controllers;

use App\Models\LocationShop;
use App\Models\Objects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LocationShopController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a specific shop.
     */
    public function show($slug)
    {
        $user = Auth::user();
        $shop = LocationShop::where('slug', $slug)
            ->where('is_active', true)
            ->with('location', 'owner')
            ->firstOrFail();

        // Check if user can access
        if ($user->level < $shop->required_level) {
            return redirect()->route('locations.show', $shop->location->slug)
                ->with('error', "Devi essere almeno livello {$shop->required_level} per entrare in questo negozio!");
        }

        // Get shop inventory
        $inventory = $shop->inventory ?? [];
        $items = [];

        if (!empty($inventory)) {
            $items = Objects::whereIn('id', $inventory)->get();
        }

        return view('front.pages.shops.show', compact('shop', 'items', 'user'));
    }

    /**
     * Purchase an item from the shop.
     */
    public function purchase(Request $request, $slug)
    {
        $user = Auth::user();
        $shop = LocationShop::where('slug', $slug)->firstOrFail();

        $request->validate([
            'item_id' => 'required|exists:objects,id'
        ]);

        $item = Objects::findOrFail($request->item_id);

        // Check if user has enough money
        if ($user->money < $item->price) {
            return back()->with('error', 'Non hai abbastanza Galleon!');
        }

        DB::beginTransaction();
        try {
            // Deduct money from user
            $user->decrement('money', $item->price);

            // Add item to inventory
            DB::table('inventory')->insert([
                'id_user' => $user->id,
                'id_obj' => $item->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Log purchase
            DB::table('logs_purchase')->insert([
                'id_user' => $user->id,
                'id_obj' => $item->id,
                'price' => $item->price,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // If shop is owned by a player, give them profit
            if ($shop->current_owner_id) {
                $profit = ($item->price * $shop->profit_percentage) / 100;
                $owner = $shop->owner;
                $owner->increment('money', $profit);

                $owner->notify(
                    'shop_sale',
                    'Vendita nel tuo negozio!',
                    "{$user->username} ha acquistato {$item->name} nel tuo negozio {$shop->name}. Hai guadagnato {$profit} Galleon!",
                    'fa-coins'
                );
            }

            // Notify user
            $user->notify(
                'shop_purchase',
                'Acquisto Completato',
                "Hai acquistato {$item->name} da {$shop->name}!",
                'fa-shopping-bag',
                '/inventory'
            );

            DB::commit();

            return back()->with('success', "Hai acquistato {$item->name}!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Errore durante l\'acquisto!');
        }
    }

    /**
     * Purchase a shop.
     */
    public function purchaseShop($slug)
    {
        $user = Auth::user();
        $shop = LocationShop::where('slug', $slug)->firstOrFail();

        if (!$shop->is_purchasable) {
            return back()->with('error', 'Questo negozio non è in vendita!');
        }

        if ($shop->current_owner_id) {
            return back()->with('error', 'Questo negozio è già di proprietà di qualcuno!');
        }

        if ($user->money < $shop->purchase_price) {
            return back()->with('error', 'Non hai abbastanza Galleon!');
        }

        DB::beginTransaction();
        try {
            // Deduct money
            $user->decrement('money', $shop->purchase_price);

            // Transfer ownership
            $shop->current_owner_id = $user->id;
            $shop->save();

            // Notify user
            $user->notify(
                'shop_purchased',
                'Negozio Acquistato!',
                "Ora sei il proprietario di {$shop->name}!",
                'fa-store',
                "/shops/{$slug}"
            );

            DB::commit();

            return back()->with('success', "Congratulazioni! Ora possiedi {$shop->name}!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Errore durante l\'acquisto del negozio!');
        }
    }
}
