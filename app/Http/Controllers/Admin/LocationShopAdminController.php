<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\LocationShop;
use App\Models\Objects;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LocationShopAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display all shops.
     */
    public function index()
    {
        $shops = LocationShop::with('location')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.shops.index', compact('shops'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $locations = Location::where('is_active', true)->get();
        $objects = Objects::all();
        return view('admin.shops.create', compact('locations', 'objects'));
    }

    /**
     * Store a new shop.
     */
    public function store(Request $request)
    {
        $request->validate([
            'location_id' => 'required|exists:locations,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:wands,potions,creatures,books,clothing,general,inn,bank',
            'required_level' => 'required|integer|min:1',
            'inventory' => 'nullable|array'
        ]);

        $shop = LocationShop::create([
            'location_id' => $request->location_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'type' => $request->type,
            'owner_name' => $request->owner_name,
            'image' => $request->image,
            'required_level' => $request->required_level,
            'is_purchasable' => $request->has('is_purchasable'),
            'purchase_price' => $request->purchase_price,
            'is_active' => $request->has('is_active'),
            'inventory' => $request->inventory,
            'profit_percentage' => $request->profit_percentage ?? 10
        ]);

        return redirect()->route('admin.shops.index')
            ->with('success', 'Negozio creato con successo!');
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $shop = LocationShop::findOrFail($id);
        $locations = Location::where('is_active', true)->get();
        $objects = Objects::all();
        return view('admin.shops.edit', compact('shop', 'locations', 'objects'));
    }

    /**
     * Update shop.
     */
    public function update(Request $request, $id)
    {
        $shop = LocationShop::findOrFail($id);

        $request->validate([
            'location_id' => 'required|exists:locations,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:wands,potions,creatures,books,clothing,general,inn,bank',
            'required_level' => 'required|integer|min:1'
        ]);

        $shop->update([
            'location_id' => $request->location_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'type' => $request->type,
            'owner_name' => $request->owner_name,
            'image' => $request->image,
            'required_level' => $request->required_level,
            'is_purchasable' => $request->has('is_purchasable'),
            'purchase_price' => $request->purchase_price,
            'is_active' => $request->has('is_active'),
            'inventory' => $request->inventory,
            'profit_percentage' => $request->profit_percentage ?? 10
        ]);

        return redirect()->route('admin.shops.index')
            ->with('success', 'Negozio aggiornato con successo!');
    }

    /**
     * Delete shop.
     */
    public function destroy($id)
    {
        $shop = LocationShop::findOrFail($id);
        $shop->delete();

        return redirect()->route('admin.shops.index')
            ->with('success', 'Negozio eliminato con successo!');
    }
}
